<?php
namespace Controllers;

/*Alias - Models*/
use Models\MovieTheater as M_MovieTheater;

/*Alias - DAO*/
use DAO\MovieTheaterDAO as DAO_MovieTheater;

/*Alias - Controllers*/
use Controllers\HomeController as C_Home;
use Controllers\UserController as C_User;
use Controllers\ShowtimeController as C_Showtime;

/*Alias - Exceptions*/
use \PDOException as PDOException;

class MovieTheaterController {
    
    private $movieTheaterDAO;
    private $homeController;
    private $userController;

	function __construct() {
        $this->userController = new C_User();
        $this->homeController = new C_Home();
        $this->movieTheaterDAO = new DAO_MovieTheater();
    }

    public function loadMovieTheaters() {
        $listOfMovieTheaters = array();
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $listOfMovieTheaters = $this->movieTheaterDAO->retrieveAll();
            }
            catch(PDOException $e)
            {
                $listOfMovieTheaters = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $listOfMovieTheaters;
    }

    public function loadMovieTheaterById($id = null) {
        $movieTheater = null;
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $movieTheater = $this->movieTheaterDAO->retrieveOne($id);
            }
            catch(PDOException $e)
            {
                $movieTheater = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $movieTheater;
    }

    public function loadMovieTheatersByPage($offset = null, $no_of_records_per_page = null) {
        $listOfMovieTheaters = array();
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $listOfMovieTheaters = $this->movieTheaterDAO->retrieveByPage($offset, $no_of_records_per_page);
            }
            catch(PDOException $e)
            {
                $listOfMovieTheaters = null;
            }
        }
        else {
            $this->homeController->index();
        }
        return $listOfMovieTheaters;
    }

    public function loadNumberOfRows() {
        $numberOfRows = 0;
        $user = $this->userController->checkSession();
        if($user!= null && $user->getUserRoleDescription()=="admin") {
            try
            {
                $numberOfRows = $this->movieTheaterDAO->retrieveNumberOfRows();
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

	public function createMovieTheater($name = null, $address = null, $openingTime = null, $closingTime = null, $pageno = null) {
        
        if(($name != null) && ($address != null) && ($openingTime != null) && ($closingTime != null) && ($pageno != null)) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                $movieTheater = new M_MovieTheater();
                $movieTheater->setState(1);
                $movieTheater->setName($name);
                $movieTheater->setAddress($address);
                $movieTheater->setOpeningTime($openingTime);
                
                if($movieTheater->setClosingTime($closingTime) != 0) {

                    if($movieTheater->getOpeningTime() < $movieTheater->getClosingTime()) {
                        try
                        {
                            $this->movieTheaterDAO->create($movieTheater);
                            $message = "Movie theater added successfully";
                            $this->homeController->admin_movietheaters($pageno, $message, 1);
                        }
                        catch(PDOException $e)
                        {
                            if ($e->errorInfo[1] == 1062) {
                                $message = "There is already a movie theater with the name entered";
                                $this->homeController->admin_movietheaters($pageno, $message, 0);
                            }
                            else {
                                $message = "A database error ocurred";
                                $this->homeController->admin_movietheaters($pageno, $message, 0);
                            }
                        }
                    }
                    else {
                        $message = "The closing time must be after the opening time";
                        $this->homeController->admin_movietheaters($pageno, $message, 0);
                    }
                }
                else {
                    $message = "The closing time should not be greater than 23:59";
                    $this->homeController->admin_movietheaters($pageno, $message, 0);
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

    public function deleteMovieTheater($id = null, $pageno = null) {

        if(($id != null) && ($pageno != null)) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                try
                {
                    if($this->movieTheaterDAO->delete($id)){
                        $message = "Successful deletion";
                        $this->homeController->admin_movietheaters($pageno, $message, 1);
                    }
                    else {
                        $message = "The movie theater is already disabled";
                        $this->homeController->admin_movietheaters($pageno, $message, 0);
                    }
                }
                catch(PDOException $e)
                {
                    $message = "A database error ocurred";
                    $this->homeController->admin_movietheaters($pageno, $message, 0);
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

    public function updateMovieTheater($id = null, $pageno = null, $name = null, $address = null, $openingTime = null, $closingTime = null, $state = 0) {

        if(($id != null) && ($pageno != null) && ($name != null) && ($address != null) && ($openingTime != null) && ($closingTime != null)) {
            $user = $this->userController->checkSession();
            if($user!= null && $user->getUserRoleDescription()=="admin") {
                
                if($closingTime != "00:00") {
                    if($closingTime > $openingTime)
                    {
                        try
                        {
                            $this->movieTheaterDAO->updateName($id, $name);
                            $this->movieTheaterDAO->updateState($id, $state);
                            $this->movieTheaterDAO->updateAddress($id, $address);
                            $this->movieTheaterDAO->updateOpeningTime($id, $openingTime);
                            $this->movieTheaterDAO->updateClosingTime($id, $closingTime);
                            $message = "Data updated successfully";
                            $this->homeController->admin_movietheaters($pageno, $message, 1);
                        }
                        catch(PDOException $e)
                        {
                            if ($e->errorInfo[1] == 1062) {
                                $message = "There is already a movie theater with the name entered";
                                $this->homeController->update_movietheater($id, $pageno, $message, 0);
                            }
                            else {
                                $message = "A database error ocurred";
                                $this->homeController->update_movietheater($id, $pageno, $message, 0);
                            }
                        }
                    }
                    else
                    {
                        $message = "The closing time must be after the opening time";
                        $this->homeController->update_movietheater($id, $pageno, $message, 0);
                    }
                }
                else {
                    $message = "The closing time should not be greater than 23:59";
                    $this->homeController->update_movietheater($id, $pageno, $message, 0);
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
