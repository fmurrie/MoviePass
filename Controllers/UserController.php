<?php
namespace Controllers;

/*Alias - Models*/
use Models\User as M_User;

/*Alias - DAO*/
use DAO\UserDAO as DAO_User;
use DAO\UserRoleDAO as DAO_UserRole;
use DAO\UserOnlineManager as UserOnlineManager;

/*Alias - Controllers*/
use Controllers\HomeController as C_Home;

/*Alias - Exceptions*/
use \PDOException as PDOException;

class UserController {

    private $userDAO;
    private $homeController;

	function __construct() {
        $this->userDAO = new DAO_User();
        $this->homeController = new C_Home();
    }

    /*The users session is checked. If a user exists in session, it is returned, otherwise it returns null*/
	public function checkSession() {
		if (session_status() == PHP_SESSION_NONE)
            session_start();

        $user = null;

        if(isset($_SESSION["user"]) && isset($_SESSION['sessionTime'])) {
            UserOnlineManager::setUserLastTimeOnline($_SESSION['user']->getId());
            $diffTime = time() - $_SESSION['sessionTime'];

            if ($diffTime > 600) {
                //10 minutes of session = 600 seconds
                $this->logout(1);
            }
            else {
                $user = $_SESSION["user"];
                $_SESSION['sessionTime'] = time();
            }
        }

        return $user;
    }

    /*User is saved in session*/
	public function setSession($user) {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
		
	$_SESSION['user'] = $user;
	$_SESSION['sessionTime'] = time();
	}

    /*User is deleted in session*/
    public function logout($index = null) {

		if (session_status() == PHP_SESSION_NONE)
            session_start();
        
        if(isset($_SESSION['user'])) {
            UserOnlineManager::setUserOffline($_SESSION['user']->getId());
            unset($_SESSION['user']);
        }

        if($index == null)
            $this->homeController->index();
	}

    /*The email and pass entered in a login are brought by POST. Check if the email exists, and if the pass matches,
    you log in*/
    public function loginUser($email = null, $password = null) {

        if($email != null && $password != null) {
            try
            {
                $user = $this->userDAO->retrieveOneByEmail($email);
                if($user != null) {
                    if(password_verify($password, $user->getPassword())) 
                    {
                        try
                        {
                            UserOnlineManager::setUserOnline($user->getId());
                            $this->setSession($user);
                            $message = "Welcome " . $user->getFirstName() . "!";
                            $this->homeController->index($message, 3);
                        }
                        catch(PDOException $e)
                        {
                            $message = "User has already logged!";
                            $this->homeController->index($message, 0);
                        }
                    }
                    else {
                        $message = "The password entered is not correct. Try again";
                        $this->homeController->login($message, 0);
                    }
                }
                else {
                    $message = "The email entered is not correct. Try again";
                    $this->homeController->login($message, 0);
                }
            }
            catch(PDOException $e)
            {
                $message = "A database error ocurred";
                $this->homeController->login($message, 0);
            }
        }
        else {
            $this->homeController->login();
        }
    }

    /*The data entered in a signup are brought by POST. The corresponding checks are made.
    If everything is in order, the user is created and saved in the database*/
    public function createUser($firstName = null, $lastName = null, $email = null, $password = null, $confirmPassword = null) {

        if($firstName != null && $lastName != null && $email != null && $password != null && $confirmPassword != null) {
            if($password == $confirmPassword) {
                    try
                    {
                        $user = $this->userDAO->retrieveOneByEmail($email);
                        if($user == null) {


                            //Password hash
                            $options = [
                                'cost' => 12,
                            ];
                            $unencryptedPassword = $password;
                            $password = password_hash($unencryptedPassword, PASSWORD_BCRYPT, $options);
                            //
            
                            $userRoleDAO = new DAO_UserRole();
                            $userRoleList = $userRoleDAO->retrieveAll();
                            if(!empty($userRoleList)) {
                                foreach($userRoleList as $userRole) {
                                    if($userRole->getDescription() == "user") {
                                        $userRole = $userRole;
                                        break;
                                    }
                                }
                                $user = new M_User();
                                $user->setEmail($email);
                                $user->setPassword($password);
                                $user->setFirstName($firstName);
                                $user->setLastName($lastName);
                                $user->setUserRole($userRole);
                                $this->userDAO->create($user);
                                $this->loginUser($email, $unencryptedPassword);
                                //A login is made to, with the email and password, load the user from the database and bring the ID
                            }
                            else {
                                $message = "There was a problem creating the user. Try again";
                                $this->homeController->signup($message, 0);
                            }
                        }
                        else {
                            $message = "The email entered already exists in the system. Try again";
                            $this->homeController->signup($message, 0);
                        }   
                    }
                    catch(PDOException $e)
                    {
                        $message = "A database error ocurred";
                        $this->homeController->signup($message, 0);
                    }            
                }
                else {
                    $message = "The passwords entered do not match. Try again";
                    $this->homeController->signup($message, 0);
                }
        }
         else {
            $this->homeController->signup();
        }
    }
    
    /*The data entered in a form are brought by POST. The corresponding checks are made.
    If everything is in order, the user is updated and saved in the database*/
    public function updateUser($id = null, $firstName = null, $lastName = null) {

        if($id != null && $firstName != null && $lastName != null) {
            try
                {
                    $this->userDAO->updateFirstName($id, $firstName);
                    $this->userDAO->updateLastName($id, $lastName);
                    
                    $user = $this->userDAO->retrieveOne($id);
                    
                    $this->setSession($user); //Seteo la sesión con el user levantado de la BD.
                    //Si no seteo la sesión, quedará guardada en la misma el mismo user pero con los datos viejos, sin actualizar
                    $message = "Data updated successfully";
                    $this->homeController->account($message, 1);
                }
                catch(PDOException $e)
                {
                    $message = "A database error ocurred";
                    $this->homeController->signup($message, 0);
                }   
        }
        else {
            $this->homeController->account();
        }
    }

    public function retrieveUser($id) {

        $user = null;

        try
        {
            $user = $this->userDAO->retrieveOne($id);
        }
        catch(PDOException $e)
        {
            $message = "A database error ocurred";
            $this->homeController->index($message, 0);
        }   
        return $user;
    }

    public function loadAvatar($FILES = null) {

        if($FILES != null) {
            $allowed =  array('png','jpg','jpeg'); //Allowed formats

                $filename = $FILES['image']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION); //Format in which the photo is located

                if(!in_array($ext,$allowed)) { //Allowed formats are compared with the format in which the photo is located
                    $message = "Only png, jpg and jpeg formats are allowed";
                    $this->homeController->account($message, 0);
                }
                else {
                    if(($FILES['image']['size']) > 0 && $FILES['image']['size'] < 1048576) {
                        //1048576 bytes = 1 MB
                        //Photo upload from $FILES
                        $fileName = $FILES['image']['name'];
                        $tmpName = $FILES['image']['tmp_name'];
                        $fileSize = $FILES['image']['size'];
                        $fileType = $FILES['image']['type'];
                        $fp = fopen($tmpName, 'r');
                        $imgContent  = fread($fp, filesize($tmpName));
                        fclose($fp);
                        //
            
                        $user = $_SESSION["user"]; //User ID

                        try
                        {
                            $this->userDAO->updatePhoto($imgContent, $user->getId());
                            $user = $this->userDAO->retrieveOne($user->getId());
                            $this->setSession($user); //Set the session with the user raised from the BD
                            //If I do not set the session, the same user will be saved in it but with the old data, without updating
                            $message = "Data updated successfully";
                            $this->homeController->account($message, 1);
                        }
                        catch(PDOException $e)
                        {
                            $message = "A database error ocurred";
                            $this->homeController->signup($message, 0);
                        }   
                    }
                    else {
                        $message = "The photo size must be less than 1 MB. Try again";
                        $this->homeController->account($message, 0);
                    }
                }
        }
        else {
            $this->homeController->account();
        }
    }

    /*Methods for Facebook API*/

    public function loginWithFacebook($fbUserData = null) {

        if($fbUserData != null) {
            if($this->verifyIfTheUserEmailBeUsing($this->userDAO->retrieveAll(),$fbUserData["email"]))
            {
                $userLoggerFB=$this->userDAO->retrieveOneByEmail($fbUserData["email"]);
                if($userLoggerFB != null) 
                {
                    $this->setSession($userLoggerFB);
                    $message = "Welcome "  . $fbUserData["first_name"] . "!";
                    $this->homeController->index($message, 3);
                }
            }
            else
            {
                $accountRegisterByFB["id"]=$fbUserData["id"];
                $accountRegisterByFB["email"]=$fbUserData["email"];
                $accountRegisterByFB["password"]=$fbUserData["password"];
                $accountRegisterByFB["confirm_password"]=$fbUserData["password"];
                $accountRegisterByFB["firstName"]=$fbUserData["first_name"];
                $accountRegisterByFB["lastName"]=$fbUserData["last_name"];
                $accountRegisterByFB["photo"]=$fbUserData["picture"];
                $this->createUserUsingFacebook($accountRegisterByFB);
            }
        }
        else {
            $this->homeController->login();
        }
    }

    private function verifyIfTheUserEmailBeUsing($accountsList, $userEmail) {
        $result=false;

        foreach($accountsList as $value)
        {
            if($value->getEmail()==$userEmail)
            {
                $result=true;
                break;
            }       
        }
        return $result;
    }

    private function createUserUsingFacebook($array = null) {

        if($array != null) {
            $email = $array["email"];
            try
            {
                $idUserFacebook=$array["id"];
                $firstName = $array["firstName"];
                $lastName = $array["lastName"];
                $email = $array["email"];
                $photo = $array["photo"];

                //Password hash
                $options = [
                    'cost' => 12,
                ];
                $unencryptedPassword = $array["password"];;
                $password = password_hash($unencryptedPassword, PASSWORD_BCRYPT, $options);
                //

                $userRoleDAO = new DAO_UserRole();
                $userRoleList = $userRoleDAO->retrieveAll();
                if(!empty($userRoleList)) {
                    foreach($userRoleList as $userRole) {
                        if($userRole->getDescription() == "user") {
                            $userRole = $userRole;
                            break;
                        }
                    }
                    $user = new M_User();
                    $user->setEmail($email);
                    $user->setPassword($password);
                    $user->setFirstName($firstName);
                    $user->setLastName($lastName);
                    $user->setUserRole($userRole);
                    $user->setIdFacebook($idUserFacebook);
                    

                    $this->userDAO->create($user);
                    $this->prepareFBprofileImg($idUserFacebook,$user->getEmail());

                    $this->loginUser($email, $unencryptedPassword);
                    //A login is made to, with the email and password, load the user from the database and bring the ID
                }
                else {
                    $message = "There was a problem creating the user. Try again";
                    $this->homeController->login($message, 0);
                } 
            }
            catch(PDOException $e)
            {
                $message = "A database error ocurred";
                $this->homeController->login($message, 0);
            }            
        }
        else {
            $this->homeController->login();
        } 
    }

    private function prepareFBprofileImg($idUserFacebook,$email)  {
        $userSearched=$this->userDAO->retrieveOneByEmail($email);
        $url="https://graph.facebook.com/".$idUserFacebook."/picture?type=large&redirect=true&width=600&height=600";
        $data = file_get_contents($url);
        
        $this->userDAO->updatePhoto($data,$userSearched->getId());
    }
}
?>
