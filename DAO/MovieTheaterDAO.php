<?php namespace DAO;
use Models\MovieTheater as MovieTheater;
use DAO\Connection as Connection;
use \PDOException as PDOException;

class MovieTheaterDAO {

    private $connection;

    public function create(MovieTheater $movieTheater) {
        $value = 0;

        try
        {
            $query = "INSERT INTO movie_theaters (state, name, address, opening_time, closing_time) VALUES (:state, :name, :address, :openingTime, :closingTime)";
            $parameters["state"] = $movieTheater->getState();
            $parameters["name"] = $movieTheater->getName();
            $parameters["address"] = $movieTheater->getAddress();
            $parameters["openingTime"] = $movieTheater->getOpeningTime();
            $parameters["closingTime"] = $movieTheater->getClosingTime();

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
        $movieTheater = new MovieTheater();
        $movieTheater->setID($row["id"]);
        $movieTheater->setState($row["state"]);
        $movieTheater->setName($row["name"]);
        $movieTheater->setAddress($row["address"]);
        $movieTheater->setOpeningTime($row["opening_time"]);
        $movieTheater->setClosingTime($row["closing_time"]);
        return $movieTheater;
    }

    public function retrieveAll() {
        $movieTheaterList = array();

        try
        {
            $query = "SELECT * FROM movie_theaters";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $movieTheater = $this->read($row);
                    array_push($movieTheaterList, $movieTheater);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieTheaterList;
    }

    public function retrieveOne($id) {
        $movieTheater = null;

        try
        {
            $parameters['id'] = $id;

            $query = "SELECT * FROM movie_theaters WHERE id=:id";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $movieTheater = $this->read($resultSet[0]);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieTheater;
    }

    public function retrieveNumberOfRows() {
        $value = 0;

        try
        {
            $query = "SELECT COUNT(*) FROM movie_theaters";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            $value = $resultSet[0]["COUNT(*)"];
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    public function retrieveByPage($offset, $no_of_records_per_page) {
        $movieTheaterList = array();

        try
        {
            $query = "SELECT * FROM movie_theaters LIMIT " . $offset . " , " . $no_of_records_per_page;

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $movieTheater = $this->read($row);
    
                    array_push($movieTheaterList, $movieTheater);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieTheaterList;
    }

    public function updateState($id, $state) {
        $parameters['id'] = $id;
        $parameters['state'] = $state;
        $query = "UPDATE movie_theaters SET state=:state WHERE id=:id";
  
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

    public function updateName($id, $name) {
        $parameters['id'] = $id;
        $parameters['name'] = $name;
        $query = "UPDATE movie_theaters SET name=:name WHERE id=:id";
  
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

    public function updateAddress($id, $address) {
        $parameters['id'] = $id;
        $parameters['address'] = $address;
        $query = "UPDATE movie_theaters SET address=:address WHERE id=:id";

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

    public function updateOpeningTime($id, $openingTime) {
        $parameters['id'] = $id;
        $parameters['openingTime'] = $openingTime;
        $query = "UPDATE movie_theaters SET opening_time=:openingTime WHERE id=:id";
  
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

    public function updateClosingTime($id, $closingTime) {
        $parameters['id'] = $id;
        $parameters['closingTime'] = $closingTime;
        $query = "UPDATE movie_theaters SET closing_time=:closingTime WHERE id=:id";
  
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

    public function delete($id) {
        $query = "UPDATE movie_theaters SET state=:state WHERE id=:id";
        $parameters['id'] = $id;
        $parameters['state'] = 0;

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