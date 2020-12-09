<?php
namespace App\Controller;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovieController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */

class MovieController
{
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->MovieRepository = $movieRepository;
    }

    /**
     * @Route("addMovie", name="add_movie", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //check name and watched parameters are added
        if (empty($data['name']) || is_null($data['watched'])) {
            return new JsonResponse('Expecting mandatory parameters! You should include at least name of the movie and if you have watched it');
        }

        $name = $data['name'];
        $watched = (bool)$data['watched'];

        //if watched, check rating restrictions
        if ($watched) {
            if (empty($data['rating'])){
                return new JsonResponse('If you have watched the movie, you should include a rating (accepted values goes from 1 to 10)');
            }
            $rating = (int)$data['rating'];

            if ($rating > 10 || $rating < 1) {
                return new JsonResponse('Rating error: value should be between 0 and 10');
            }

            $this->MovieRepository->saveMovie($name, $rating, $watched);
        }
        else {
            $this->MovieRepository->saveMovieToWatch($name, $watched);
        }

        return new JsonResponse('Movie saved successfully!', Response::HTTP_OK);
    }

    /**
     * @Route("getMovies", name="get_saved_movies", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $savedMovies = $this->MovieRepository->findAll();
        $data = [];

        foreach ($savedMovies as $movie) {
            $data[] = [
                'id' => $movie->getId(),
                'name' => $movie->getName(),
                'watched' => $movie->getWatched(),
                'rating' => $movie->getRating(),
            ];
        }

        return new JsonResponse(['Movies saved:', sizeof($data), 'Data:', $data], Response::HTTP_OK);
    }

    /**
     * @Route("updateMovie/{id}", name="update_Movie", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $movie = $this->MovieRepository->findOneBy(['id' => $id]);
        if(is_null($movie)){
            return new JsonResponse('Movie ID not found in the database');
        }

        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $movie->setName($data['name']);

        if(!is_null($data['watched'])){

            $movie->setWatched((bool)$data['watched']);

            if((bool)$data['watched']){
                if(is_null($data['rating']) || ((int)$data['rating'])>10 || ((int)$data['rating'])<1){
                    return new JsonResponse('Missing or incorrect rating');
                }
                $movie->setRating($data['rating']);
            }
            else {
                $movie->setRating(null);
                $data['rating'] = null;
            }
        }

        $updatedMovie = $this->MovieRepository->updateMovie($movie);

		return new JsonResponse( ['Movie updated successfully:', $data], Response::HTTP_OK);
    }

    /**
     * @Route("deleteMovie/{id}", name="delete_Movie", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $movie = $this->MovieRepository->findOneBy(['id' => $id]);
        if(is_null($movie)){
            return new JsonResponse('Movie ID not found in the database');
        }

        $this->MovieRepository->removeMovie($movie);

        return new JsonResponse('Movie deleted successfully', Response::HTTP_OK);
    }
}

?>
