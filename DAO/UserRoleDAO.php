<?php namespace DAO;
use Models\UserRole as UserRole;
use DAO\Connection as Connection;
use \PDOException as PDOException;

class UserRoleDAO {

    private $connection;

    public function create(UserRole $userRole) {
        $value = 0;

        try
        {
            $query = "INSERT INTO user_roles (id, description) VALUES (:id, :description)";
            $parameters["id"] = $userRole->getId();
            $parameters["description"] = $userRole->getDescription();

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
        $userRole = new UserRole();
        $userRole->setId($row["id"]);
        $userRole->setDescription($row["description"]);
        return $userRole;
    }

    public function retrieveAll() {
        $userRoleList = array();

        try
        {
            $query = "SELECT * FROM user_roles";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $userRole = $this->read($row);
                    array_push($userRoleList, $userRole);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $userRoleList;
    }

    public function retrieveOne($id) {
        $userRole = null;

        try
        {
            $parameters['id'] = $id;

            $query = "SELECT * FROM user_roles WHERE id=:id";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $userRole = $this->read($resultSet[0]);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $userRole;
    }
}