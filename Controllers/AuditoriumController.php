<?php
namespace Controllers;

/*Alias - Models*/
use Models\Auditorium as M_Auditorium;

/*Alias - DAO*/
use DAO\AuditoriumDAO as DAO_Auditorium;
use DAO\MovieTheaterDAO as DAO_MovieTheater;

/*Alias - Controllers*/
use Controllers\HomeController as C_Home;
use Controllers\UserController as C_User;
use Controllers\ShowtimeController as C_Showtime;

/*Alias - Exceptions*/
use \PDOException as PDOException;

class AuditoriumController {
    
    private $auditoriumDAO;
    private $homeController;
    private $userController;

	function __construct() {
        $this->userController = new C_User();
        $this->homeController = new C_Home();
        $this->auditoriumDAO = new DAO_Auditorium();
    }

    public function loadAuditoriums($idMovieTheater = null) {
        $listOfAuditoriums = array();
        if($idMovieTheater!=null) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                try
                {
                    $listOfAuditoriums = $this->auditoriumDAO->retrieveAll($idMovieTheater);
                }
                catch(PDOException $e)
                {
                    $listOfAuditoriums = null;
                }
            }
            else {
                $this->homeController->index();
            }
        }
        return $listOfAuditoriums;
    }
	
    public function loadAuditoriumsActive($idMovieTheater = null) {
        $listOfAuditoriums = array();
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $listOfAuditoriums = $this->auditoriumDAO->retrieveAllActive($idMovieTheater);
            }
            catch(PDOException $e)
            {
                $listOfAuditoriums = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $listOfAuditoriums;
    }

    public function loadAuditoriumById($idAuditorium = null) {
        $auditorium = null;
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $auditorium = $this->auditoriumDAO->retrieveOne($idAuditorium);
            }
            catch(PDOException $e)
            {
                $auditorium = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $auditorium;
    }

    public function loadAuditoriumsByPage($offset = null, $no_of_records_per_page = null, $idMovieTheater = null) {
        $listOfAuditoriums = array();
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $listOfAuditoriums = $this->auditoriumDAO->retrieveByPage($offset, $no_of_records_per_page, $idMovieTheater);
            }
            catch(PDOException $e)
            { 
                $listOfAuditoriums = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $listOfAuditoriums;
    }

    public function loadNumberOfRows($idMovieTheater = null) {
        $numberOfRows = 0;
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $numberOfRows = $this->auditoriumDAO->retrieveNumberOfRows($idMovieTheater);
            }
            catch(PDOException $e)
            {
                $numberOfRows = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $numberOfRows;
    }

	public function createAuditorium($name = null, $capacity = null, $ticketPrice = null, $pageno = null, $pagenoMovieTheater = null, $idMovieTheater = null) {

        if(($name != null)&&($capacity != null)&&($ticketPrice != null)&&($pageno != null)&&($pagenoMovieTheater != null)&&($idMovieTheater != null)) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                $auditorium = new M_Auditorium();
                
                $daoMovieTheater = new DAO_MovieTheater();
                $movieTheater = $daoMovieTheater->retrieveOne($idMovieTheater);
                $auditorium->setMovieTheater($movieTheater);

                $auditorium->setState(1);
                $auditorium->setName($name);
                $auditorium->setCapacity($capacity);
                $auditorium->setTicketPrice($ticketPrice);
                
                if($auditorium->setCapacity($capacity) != 0 && $auditorium->setTicketPrice($ticketPrice) != 0) {

                    try
                    {
                        $this->auditoriumDAO->create($auditorium);
                        $message = "Auditorium added successfully";
                        $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 1);
                    }
                    catch(PDOException $e)
                    {
                        if ($e->errorInfo[1] == 1062) {
                            $message = "There is already an auditorium with the name entered";
                            $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 0);
                        }
                        else {
                            $message = "A database error ocurred";
                            $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 0);
                        }
                    }
                }
                else {

                    if(($auditorium->setValorTicket($ticketPrice) == 0)) {
                        $message = "The ticket value must be greater than 0";
                    }
                    else if(($auditorium->setCapacidad($capacity) == 0)) {
                        $message = "The capacity must be greater than 0";
                    }
                    $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 0);
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

    public function deleteAuditorium($id = null, $pageno = 0, $pagenoMovieTheater = 0, $idMovieTheater = 0) {
        
        if($id != null) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                try
                {
                    if($this->auditoriumDAO->delete($id)){
                        $message = "Successful deletion";
                        $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 1);
                    }
                    else {
                        $message = "The auditorium is already disabled";
                        $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 0);
                    }
                }
                catch(PDOException $e)
                {
                    $message = "A database error ocurred";
                    $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 0);
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

    public function updateAuditorium($id = null, $pageno = null, $pagenoMovieTheater = null, $idMovieTheater = null, $name = null, $capacity = null, $ticketPrice = null, $state = 0) {

        if(($id != null)&&($pageno != null)&&($pagenoMovieTheater != null)&&($idMovieTheater != null)&&($name != null)&&($capacity != null)&&($ticketPrice != null)) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                
                if(($capacity > 0) && ($ticketPrice > 0)) {
                    try
                    {
                        $this->auditoriumDAO->updateName($id, $idMovieTheater, $name);
                        $this->auditoriumDAO->updateState($id, $state);
                        $this->auditoriumDAO->updateCapacity($id, $capacity);
                        $this->auditoriumDAO->updateTicketPrice($id, $ticketPrice);
                        $message = "Data updated successfully";
                        $this->homeController->admin_auditoriums($idMovieTheater, $pageno, $pagenoMovieTheater, $message, 1);
                    }
                    catch(PDOException $e)
                    {
                        if ($e->errorInfo[1] == 1062) {
                            $message = "There is already an auditorium with the name entered";
                            $this->homeController->update_auditorium($id, $pageno, $pagenoMovieTheater, $message, 0);
                        }
                        else {
                            $message = "A database error ocurred";
                            $this->homeController->update_auditorium($id, $pageno, $pagenoMovieTheater, $message, 0);
                        }
                    }
                }
                else {
                    if(($ticketPrice <= 0) && ($capacity > 0)) {
                        $message = "The ticket value must be greater than 0";
                    }
                    else if(($capacity <= 0) && ($ticketPrice > 0)) {
                        $message = "The capacity must be greater than 0";
                    }
                    $this->homeController->update_auditorium($id, $pageno, $pagenoMovieTheater, $message, 0);
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

    /*For jQuery*/
    public function loadAuditoriumsInJson($idMovieTheater = null) {

        if($idMovieTheater != null) {
            $listOfAuditoriums = array();
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                try
                {
                    $listOfAuditoriums = $this->auditoriumDAO->retrieveAllActive($idMovieTheater);
                    //Encoding array in JSON format:
                    $arrayToEncode = array();
                    if($listOfAuditoriums != null) {
                        foreach($listOfAuditoriums as $auditorium) {
                            $valuesArray["id"] = $auditorium->getId();
                            $valuesArray["name"] = $auditorium->getName();
                            array_push($arrayToEncode, $valuesArray);
                        }
                        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
                        echo json_encode($jsonContent);
                    }
                }
                catch(PDOException $e)
                {
                    $arrayToEncode = array();
                    $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
                    echo json_encode($jsonContent);
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
}
?>