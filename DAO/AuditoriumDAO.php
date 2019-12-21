<?php namespace DAO;
use Models\Auditorium as Auditorium;
use DAO\Connection as Connection;
use \PDOException as PDOException;
use DAO\MovieTheaterDAO as MovieTheaterDAO;

class AuditoriumDAO {

    private $connection;

    public function create(Auditorium $auditorium) {
        $value = 0;

        try
        {
            $query = "INSERT INTO auditoriums (id_movietheater, state, name, capacity, ticket_price) VALUES (:idMovieTheater, :state, :name, :capacity, :ticketPrice)";
            $parameters["idMovieTheater"] = $auditorium->getMovieTheater()->getId();
            $parameters["state"] = $auditorium->getState();
            $parameters["name"] = $auditorium->getName();
            $parameters["capacity"] = $auditorium->getCapacity();
            $parameters["ticketPrice"] = $auditorium->getTicketPrice();

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
        $auditorium = new Auditorium();
        $auditorium->setId($row["id"]);
        $idMovieTheater = ($row["id_movietheater"]);

        $daoMovieTheater = new MovieTheaterDAO();
        $movieTheater = $daoMovieTheater->retrieveOne($idMovieTheater);
        $auditorium->setMovieTheater($movieTheater);

        $auditorium->setState($row["state"]);
        $auditorium->setName($row["name"]);
        $auditorium->setCapacity($row["capacity"]);
        $auditorium->setTicketPrice($row["ticket_price"]);
        return $auditorium;
    }

    public function retrieveAll($idMovieTheater) {
        $auditoriumList = array();

        try
        {
            $query = "SELECT * FROM auditoriums WHERE id_movietheater=" . $idMovieTheater;

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $auditorium = $this->read($row);
                    array_push($auditoriumList, $auditorium);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $auditoriumList;
    }

    public function retrieveAllActive($idMovieTheater) {
        $auditoriumList = array();

        try
        {
            $query = "SELECT * FROM auditoriums WHERE id_movietheater=" . $idMovieTheater . " AND state=1";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $auditorium = $this->read($row);
                    array_push($auditoriumList, $auditorium);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $auditoriumList;
    }

    public function retrieveOne($id) {
        $auditorium = null;

        try
        {
            $parameters['id'] = $id;

            $query = "SELECT * FROM auditoriums WHERE id=:id";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $auditorium = $this->read($resultSet[0]);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $auditorium;
    }

    public function retrieveNumberOfRows($idMovieTheater) {
        $value = 0;

        try
        {
            $query = "SELECT COUNT(*) FROM auditoriums WHERE id_movietheater=" . $idMovieTheater;

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

    public function retrieveByPage($offset, $no_of_records_per_page, $idMovieTheater) {
        $auditoriumList = array();

        try
        {
            $query = "SELECT * FROM auditoriums WHERE id_movietheater=" . $idMovieTheater . " LIMIT " . $offset . " , " . $no_of_records_per_page;

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $auditorium = $this->read($row);
    
                    array_push($auditoriumList, $auditorium);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $auditoriumList;
    }

    public function updateState($id, $state) {
        $parameters['id'] = $id;
        $parameters['state'] = $state;
        $query = "UPDATE auditoriums SET state=:state WHERE id=:id";
  
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

    public function updateName($id, $idMovieTheater, $name) {
        $parameters['id'] = $id;
        $parameters["name"] = $name;

        $query = "UPDATE auditoriums SET name=:name WHERE id=:id";
  
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

    public function updateCapacity($id, $capacity) {
        $parameters['id'] = $id;
        $parameters['capacity'] = $capacity;
        $query = "UPDATE auditoriums SET capacity=:capacity WHERE id=:id";

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

    public function updateTicketPrice($id, $ticketPrice) {
        $parameters['id'] = $id;
        $parameters['ticketPrice'] = $ticketPrice;
        $query = "UPDATE auditoriums SET ticket_price=:ticketPrice WHERE id=:id";
  
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
        $query = "UPDATE auditoriums SET state=:state WHERE id=:id";
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