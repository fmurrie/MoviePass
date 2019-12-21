<?php
namespace Controllers;

/*Alias - DAO*/
use DAO\GenreDAO as DAO_Genre;

/*Alias - Controllers*/
use Controllers\HomeController as C_Home;
use Controllers\UserController as C_User;
use Controllers\MovieController as C_Movie;

/*Alias - Exceptions*/
use \PDOException as PDOException;

class GenreController {
    
    private $genreDAO;
    private $homeController;

	function __construct() {
        $this->genreDAO = new DAO_Genre();
        $this->homeController = new C_Home();
    }

    /*All genres are loaded from the database and returned in an array*/
    public function loadGenres() {
        $listOfGenresToReturn = array();
        try
        {
            $listOfGenresToReturn = $this->genreDAO->retrieveAll();
        }
        catch(PDOException $e)
        {
            $listOfGenresToReturn = null;
        }
        return $listOfGenresToReturn;
    }

    public function loadGenresIntoBD() {
        $listOfGenres = $this->genreDAO->retrieveAllGenresFromApi();
        foreach($listOfGenres as $genre) {
            try
            {
                $this->genreDAO->create($genre);
            }
            catch(PDOException $e)
            {
                if($e->errorInfo[1] == 1062) {
                    $this->genreDAO->updateName($genre->getId(), $genre->getName());
                }
            }
        }
    }

    public function dataCrossing($arrayOfMovies, $arrayOfGenres) {
        
        foreach($arrayOfMovies as $movie) {
            $arrayOfGenresID = $movie->getGenres();
            $arrayOfGenresDeCadaMovie = array();
    
            foreach($arrayOfGenresID as $genreID) {
    
                foreach($arrayOfGenres as $genre) {
                    if($genreID == ($genre->getID())) {
                        //Load genres on each Movie object
                        array_push($arrayOfGenresDeCadaMovie, $genre);
                        //
                    }
                }
    
            }
            $movie->setGenres($arrayOfGenresDeCadaMovie);
        }
        return $arrayOfMovies;
    }
}
?>