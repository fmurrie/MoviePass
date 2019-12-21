<?php
namespace Controllers;

/*Alias - Models*/
use Models\Showtime as M_Showtime;

/*Alias - DAO*/
use DAO\ShowtimeDAO as DAO_Showtime;

/*Alias - Controller*/
use Controllers\HomeController as C_Home;
use Controllers\UserController as C_User;
use Controllers\AuditoriumController as C_Auditorium;
use Controllers\MovieController as C_Movie;

/*Alias - Exceptions*/
use \PDOException as PDOException;

class ShowtimeController {
    
    private $showtimeDAO;
    private $homeController;
    private $userController;

	function __construct() {
        $this->showtimeDAO = new DAO_Showtime();
        $this->homeController = new C_Home();
        $this->userController = new C_User();
    }

    public function createShowtime($idMovieTheater = null, $idAuditorium = null, $idMovie = null, $choosenDate = null, $choosenTime = null) {

        if(($idMovieTheater != null) && ($idAuditorium != null) && ($idMovie != null) && ($choosenDate != null) && ($choosenTime != null)) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {

                $date = date('Y-m-d', time());
                $time = date('H:i', time());
                /*Verification 1/4:
                The function must be in the future*/
                if($choosenDate > $date  || ($choosenDate == $date && $choosenTime >= $time)) {
                    /*Verification 2/4:
                    We bring the showtimes of the chosen date and check that the same movie is not in other movie theater
                    in the date except that it is in the same movie theater and that it is not reproduced in more than one
                    auditorium of the movie theater*/
                    $arrayOfShowtimes = $this->loadShowtimesByDate($choosenDate);
                    $value = true;
        
                    foreach($arrayOfShowtimes as $showtimeArray) {
                        if($showtimeArray->getMovie()->getIdBd() == $idMovie && $showtimeArray->getAuditorium()->getMovieTheater()->getId() != $idMovieTheater) {
                            $value = false;
                            break;
                        }
                        else if($showtimeArray->getMovie()->getIdBd() == $idMovie && $showtimeArray->getAuditorium()->getMovieTheater()->getId() == $idMovieTheater && $showtimeArray->getAuditorium()->getId() != $idAuditorium) {
                            $value = false;
                            break;
                        }
                    }
        
                    if($value) {
                        //Preparing showtime
                        $auditoriumController = new C_Auditorium();
                        $auditorium_choosen = $auditoriumController->loadAuditoriumById($idAuditorium);
                        //Preparing movie
                        $movieController = new C_Movie();
                        $choosenMovie = $movieController->loadMovieById($idMovie);
        
                        $showtime = new M_Showtime();
                        $showtime->setDate($choosenDate);
                        $showtime->setOpeningTime($choosenTime);
                        $showtime->setAuditorium($auditorium_choosen);
                        $showtime->setMovie($choosenMovie);
                        $showtime->setTotalTickets($auditorium_choosen->getCapacity());
                        $showtime->setTicketPrice($auditorium_choosen->getTicketPrice());
                        $movieController = new C_Movie();
                        $duracionMovie = $movieController->loadDurationOneMovie($choosenMovie->getID());
                        $showtime->generateClosingTime($duracionMovie);
        
                        $openingTime = $showtime->getOpeningTime();
                        $closingTime = $showtime->getClosingTime();
        
                        /*Verification 3/4:
                        We check that the initial and final schedules of the function are within the cinema hours*/
                        if($openingTime >= $auditorium_choosen->getMovieTheater()->getOpeningTime() && $openingTime < $auditorium_choosen->getMovieTheater()->getClosingTime()
                        && $closingTime < $auditorium_choosen->getMovieTheater()->getClosingTime() && $closingTime > $auditorium_choosen->getMovieTheater()->getOpeningTime()) {
                            
                            /*Verification 4/4:
                            EndDate2 > StartDate1 AND EndDate1 > StartDate2*/
                            $value = true;
                            foreach($arrayOfShowtimes as $showtimeArray) {
                                if($showtimeArray->getAuditorium()->getId() == $showtime->getAuditorium()->getId()) {
                                    if($showtimeArray->getClosingTime() > $openingTime && $closingTime > $showtimeArray->getOpeningTime()) {
                                        $value = false;
                                        break;
                                    }
                                }
                            }
                            
                            if($value) {
                                try
                                {
                                    $this->showtimeDAO->create($showtime);
                                    $message = "Showtime added successfully";
                                    $this->homeController->new_showtime($message, 1);
                                }
                                catch(PDOException $e)
                                {
                                    $message = "A database error ocurred";
                                    $this->homeController->new_showtime($message, 0);
                                }
                            }
                            else {
                                $message = "The chosen schedule overlaps with another function";
                                $this->homeController->new_showtime($message, 0);
                            }
                        }
                        else {
                            $message = "The chosen times are not within the cinema schedule";
                            $this->homeController->new_showtime($message, 0);
                        }
                    }
                    else {
                        $message = "The movie on the chosen date is already taken by another cinema or auditorium";
                        $this->homeController->new_showtime($message, 0);
                    }
                }
                else {
                    $message = "The function must be in the future";
                    $this->homeController->new_showtime($message, 0);
                }
            }
            else {
                $this->homeController->index();
            }
        }
        else {
            $this->homeController->index();
        }
    }

    /*All showtimes are loaded from the database and returned in an array.
    It is not checked that the movie is currently in "NOW PLAYING"*/
    public function loadShowtimesByDate($date) {
        $listOfShowtimes = array();
        try
        {
            $listOfShowtimes = $this->showtimeDAO->retrieveAllByDateNoCheckMovieDate($date);
        }
        catch(PDOException $e)
        {
            $listOfShowtimes = null;
        }
        return $listOfShowtimes;
    }

    /*From the date brought by parameter onwards*/
    public function loadShowtimesByPageAndDate($offset, $no_of_records_per_page, $date, $tickets) {
        $listOfShowtimes = array();
        try
        {
            $listOfShowtimes = $this->showtimeDAO->retrieveByPageAndDate($offset, $no_of_records_per_page, $date, $tickets);
        }
        catch(PDOException $e)
        {
            $listOfShowtimes = null;
        }
        return $listOfShowtimes;
    }

    public function loadShowtimeById($id) {
        $listOfShowtimes = array();
        try
        {
            $listOfShowtimes = $this->showtimeDAO->retrieveOne($id);
        }
        catch(PDOException $e)
        {
            $listOfShowtimes = null;
        }
        return $listOfShowtimes;
    }

    public function loadNumberOfRowsByDate($date) {
        $numberOfRows = 0;
        try
        {
            $numberOfRows = $this->showtimeDAO->retrieveNumberOfRowsByDate($date);
        }
        catch(PDOException $e)
        {
            $numberOfRows = null;
        }
        return $numberOfRows;
    }

    public function updateTicketsSold($id, $ticketsPurchasedas) {
        $success = true;
        try
        {
            $this->showtimeDAO->updateTicketsSold($id, $ticketsPurchasedas);
        }
        catch(PDOException $e)
        {
            $success = false;
        }
        return $success;
    }
}
?>