<?php namespace DAO;
use Models\User as User;
use DAO\Connection as Connection;
use DAO\UserRoleDAO as DAO_UserRole;
use \PDOException as PDOException;

class UserDAO {

    private $connection;

    public function create(User $user) {
        $value = 0;

        try
        {
            $query = "INSERT INTO users (email, password, first_name, last_name, id_user_role, id_facebook) VALUES (:email, :password, :firstName, :lastName, :idUserRole, :id_facebook)";
            $parameters["email"] = $user->getEmail();
            $parameters["password"] = $user->getPassword();
            $parameters["firstName"] = $user->getFirstName();
            $parameters["lastName"] = $user->getLastName();
            $parameters["idUserRole"] = $user->getUserRoleId();
            $parameters["id_facebook"] = $user->getIdFacebook();

            $this->connection = Connection::getInstance();

            $value = $this->connection->executeNonQuery($query, $parameters);
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    private function read($row) {
        $user = new User();
        $user->setId($row["id"]);
        $user->setEmail($row["email"]);
        $user->setPassword($row["password"]);
        $user->setFirstName($row["first_name"]);
        $user->setLastName($row["last_name"]);
        $user->setPhoto($row["photo"]);
        $user->setIdFacebook($row["id_facebook"]);

        /*User Role*/
        $idUserRole = $row["id_user_role"];
        $userRoleDAO = new DAO_UserRole();
        $userRole = $userRoleDAO->retrieveOne($idUserRole);
        $user->setUserRole($userRole);
        return $user;
    }

    public function retrieveAll() {
        $userList = array();

        try
        {
            $query = "SELECT * FROM users";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $user = $this->read($row);
                    array_push($userList, $user);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $userList;
    }

    public function retrieveOne($id) {
        $user = null;

        try
        {
            $parameters['id'] = $id;

            $query = "SELECT * FROM users WHERE id=:id";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $user = $this->read($resultSet[0]);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $user;
    }

    public function retrieveOneByEmail($email) {
        $user = null;

        try
        {
            $parameters['email'] = $email;

            $query = "SELECT * FROM users WHERE email=:email";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $user = $this->read($resultSet[0]);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $user;
    }

    public function updateFirstName($id, $firstName) {
        $parameters['id'] = $id;
        $parameters['firstName'] = $firstName;
        $query = "UPDATE users SET first_name=:firstName WHERE id=:id";
  
        $value = 0;

        try
        {
            $this->connection = Connection::getInstance();
            $value = $this->connection->executeNonQuery($query, $parameters);
        }
        catch(PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    public function updateLastName($id, $lastName) {
        $parameters['id'] = $id;
        $parameters['lastName'] = $lastName;
        $query = "UPDATE users SET last_name=:lastName WHERE id=:id";
  
        $value = 0;

        try
        {
            $this->connection = Connection::getInstance();
            $value = $this->connection->executeNonQuery($query, $parameters);
        }
        catch(PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    public function updatePhoto($imagetmp, $id) {
        $parameters['id'] = $id;
        $parameters['imagetmp'] = $imagetmp;
        $query = "UPDATE users SET photo=:imagetmp WHERE id=:id";

        $value = 0;
  
        try
        {
            $this->connection = Connection::getInstance();
            $value = $this->connection->executeNonQuery($query, $parameters);
        }
        catch(PDOException $e)
        {
            throw $e;
        }
        return $value;
    }
}