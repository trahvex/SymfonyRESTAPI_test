<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Movie::class);
        $this->manager = $manager;
    }

    //called from MovieController (add function)
    public function saveMovie($name, $rating, $watched){

        $newMovie = new Movie();
        $newMovie
            -> setName($name)
            -> setRating($rating)
            -> setWatched($watched);

        $this->manager->persist($newMovie);
        $this->manager->flush();
    }

    //save movie without rating (!watched)
    public function saveMovieToWatch($name, $watched){

        $newMovie = new Movie();
        $newMovie
            -> setName($name)
            -> setWatched($watched);

        $this->manager->persist($newMovie);
        $this->manager->flush();
    }

    //update data from a movie
    public function updateMovie(Movie $movie): Movie{
        $this->manager->persist($movie);
        $this->manager->flush();
        return $movie;
    }

    //delete one movie of the DB
    public function removeMovie(Movie $movie){
        $this->manager->remove($movie);
        $this->manager->flush();
    }

}
