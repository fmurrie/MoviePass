<?php
namespace Controllers;

/*Alias - DAO*/
use DAO\MovieDAO as DAO_Movie;

/*Alias - Controllers*/
use Controllers\HomeController as C_Home;
use Controllers\GenreController as C_Genre;
use Controllers\UserController as C_User;

/*Alias - Exceptions*/
use \PDOException as PDOException;

class MovieController {
    
    private $movieDAO;
    private $homeController;
    private $genreController;

	function __construct() {
        $this->movieDAO = new DAO_Movie();
        $this->homeController = new C_Home();
        $this->genreController = new C_Genre();
    }

    /*All movies are loaded from the BD and returned to an array.*/
    public function loadMovies() {
        $listOfMovies = array();
        try
        {
            $listOfMovies = $this->movieDAO->retrieveAll();
        }
        catch(PDOException $e)
        {
            $listOfMovies = null;
        }
        return $listOfMovies;
    }

    /*A BD movie is returned by bringing its corresponding ID into the database (NOT the one from the api).
    IT IS NOT CHECKED THAT THE FILM FOLLOWS IN BILLBOARD "NOW PLAYING"*/
    public function loadMovieById($idBd, $check = null) {
        try
        {
            if($check == null)
                $movie = $this->movieDAO->retrieveOne($idBd);
            else
                $movie = $this->movieDAO->retrieveOneNoCheckMovieDate($idBd);
        }
        catch(PDOException $e)
        {
            $movie = null;
        }
        return $movie;
    }

    /*All movies are loaded from the API and saved to the BD.
    Method for the ADMIN, without date check. The loading of current movies is forced*/
    public function loadMoviesIntoBD() {
        $userController = new C_User();
        $user = $userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            $arrayOfMovies = $this->movieDAO->retrieveAllMoviesNowPlayingFromApi();

            /*Loading genres ..*/
            $this->genreController->loadGenresIntoBD();
            $arrayOfGenres = $this->genreController->loadGenres();
            
            $arrayOfMovies = $this->genreController->dataCrossing($arrayOfMovies, $arrayOfGenres);
    
            foreach($arrayOfMovies as $movie) {
                try
                {
                    $this->movieDAO->create($movie);
                }
                catch(PDOException $e)
                {
                    if ($e->errorInfo[1] == 1062) {
                        $this->movieDAO->updateUploadingDate($movie->getId(), $movie->getUploadingDate());
                    }
                }
            }
            $message = "The movies have been updated successfully";
            $this->homeController->index($message, 1);
        }
        else {
            $this->homeController->index();
        }
    }

    /*All movie DAO movies are loaded from the API and saved to the BD.
    Method to load billboard "NOW PLAYING", for the USER. With date check*/
    public function loadMoviesNowPlayingIntoBD() {
        $dateMax = $this->getMaxDate();
          
        $date = date('Y/m/d', time());
        $date1 = new \DateTime($date);
        $date2 = new \DateTime($dateMax);
        $interval = $date1->diff($date2);

        if($dateMax == null || $interval->days > 7) {

            $arrayOfMovies = $this->movieDAO->retrieveAllMoviesNowPlayingFromApi();
    
            /*Loading genres ..*/
            $this->genreController->loadGenresIntoBD();
            $arrayOfGenres = $this->genreController->loadGenres();
            
            $arrayOfMovies = $this->genreController->dataCrossing($arrayOfMovies, $arrayOfGenres);
    
            foreach($arrayOfMovies as $movie) {
                try
                {
                    $this->movieDAO->create($movie);
                }
                catch(PDOException $e)
                {
                    if ($e->errorInfo[1] == 1062) {
                        $this->movieDAO->updateUploadingDate($movie->getId(), $movie->getUploadingDate());
                    }
                }
            }
        }
    }

    public function loadDurationOneMovie($id) {
        $duracion = $this->movieDAO->retrieveDurationOneMovieFromApi($id);
        return $duracion;
    }

    public function loadTrailerOneMovie($id) {
        $trailer = $this->movieDAO->retrieveTrailerOneMovieFromApi($id);
        return $trailer;
    }

    public function loadPhotosOneMovie($id) {
        $arrayFotos = $this->movieDAO->retrievePhotosOneMovieFromApi($id);
        return $arrayFotos;
    }

    public function getMaxDate() {
        try
        {
            $maxDate = $this->movieDAO->getMaxDate();
        }
        catch(PDOException $e)
        {
            $maxDate = null;
        }
        return $maxDate;
    }

    /*No filter. ALL MOVIES*/
    public function getAllMovies() {
        $listOfMovies = array();
        try
        {
            $listOfMovies = $this->movieDAO->retrieveAllNoFilter();
        }
        catch(PDOException $e)
        {
            $listOfMovies = null;
        }
        return $listOfMovies;
    }
}
?>
