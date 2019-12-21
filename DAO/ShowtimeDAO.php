<?php namespace DAO;
use Models\Auditorium as Auditorium;
use Models\Movie as Movie;
use Models\Showtime as Showtime;
use DAO\AuditoriumDAO as DAO_Auditorium;
use DAO\MovieDAO as DAO_Movie;
use DAO\Connection as Connection;
use \PDOException as PDOException;

class ShowtimeDAO {

    private $connection;

    public function create(Showtime $showtime) {
        $value = 0;

        try
        {
            $query = "INSERT INTO showtimes (date, opening_time, closing_time, id_auditorium, id_movie, tickets_sold, total_tickets, ticket_price) VALUES (:date, :openingTime, :closingTime, :idAuditorium, :idMovie, :ticketsSold, :totalTickets, :ticketPrice)";
            $parameters["date"] = $showtime->getDate();
            $parameters["openingTime"] = $showtime->getOpeningTime();
            $parameters["closingTime"] = $showtime->getClosingTime();
            $parameters["idAuditorium"] = $showtime->getAuditorium()->getId();
            $parameters["idMovie"] = $showtime->getMovie()->getIdBd();
            $parameters["ticketsSold"] = 0;
            $parameters["totalTickets"] = $showtime->getTotalTickets();
            $parameters["ticketPrice"] = $showtime->getTicketPrice();

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
        $showtime = new Showtime();
        $showtime->setID($row["id"]);
        $showtime->setDate($row["date"]);
        $showtime->setOpeningTime($row["opening_time"]);
        $showtime->setClosingTime($row["closing_time"]);
        $showtime->setTicketsSold($row["tickets_sold"]);
        $showtime->setTotalTickets($row["total_tickets"]);
        $showtime->setTicketPrice($row["ticket_price"]);
        return $showtime;
    }

    public function retrieveAll() {
        $showtimeList = array();

        try
        {
            $query = "SELECT * FROM showtimes ORDER BY date,opening_time";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $showtime = $this->read($row);

                    $idAuditorium = $row["id_auditorium"];
                    $idMovie = $row["id_movie"];

                    $auditoriumDAO = new DAO_Auditorium();
                    $auditorium = $auditoriumDAO->retrieveOne($idAuditorium);

                    $movieDAO = new DAO_Movie();
                    $movie = $movieDAO->retrieveOne($idMovie);

                    $showtime->setAuditorium($auditorium);
                    $showtime->setMovie($movie);

                    array_push($showtimeList, $showtime);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $showtimeList;
    }

    public function retrieveAllByDate($date) {
        $showtimeList = array();

        try
        {
            $parameters['date'] = $date;
            $query = "SELECT * FROM showtimes where date=:date ORDER BY date,opening_time";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $showtime = $this->read($row);

                    $idAuditorium = $row["id_auditorium"];
                    $idMovie = $row["id_movie"];

                    $auditoriumDAO = new DAO_Auditorium();
                    $auditorium = $auditoriumDAO->retrieveOne($idAuditorium);

                    $movieDAO = new DAO_Movie();
                    $movie = $movieDAO->retrieveOne($idMovie);

                    $showtime->setAuditorium($auditorium);
                    $showtime->setMovie($movie);

                    array_push($showtimeList, $showtime);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $showtimeList;
    }

    public function retrieveAllByDateNoCheckMovieDate($date) {
        $showtimeList = array();

        try
        {
            $parameters['date'] = $date;
            $query = "SELECT * FROM showtimes where date=:date and total_tickets > tickets_sold ORDER BY date,opening_time";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $showtime = $this->read($row);

                    $idAuditorium = $row["id_auditorium"];
                    $idMovie = $row["id_movie"];

                    $auditoriumDAO = new DAO_Auditorium();
                    $auditorium = $auditoriumDAO->retrieveOne($idAuditorium);

                    $movieDAO = new DAO_Movie();
                    $movie = $movieDAO->retrieveOneNoCheckMovieDate($idMovie);

                    $showtime->setAuditorium($auditorium);
                    $showtime->setMovie($movie);

                    array_push($showtimeList, $showtime);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $showtimeList;
    }

    public function retrieveOne($id) {
        $showtime = null;

        try
        {
            $parameters['id'] = $id;

            $query = "SELECT * FROM showtimes WHERE id=:id";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $showtime = $this->read($resultSet[0]);

                $idAuditorium = $resultSet[0]["id_auditorium"];
                $idMovie = $resultSet[0]["id_movie"];

                $auditoriumDAO = new DAO_Auditorium();
                $auditorium = $auditoriumDAO->retrieveOne($idAuditorium);

                $movieDAO = new DAO_Movie();
                $movie = $movieDAO->retrieveOneNoCheckMovieDate($idMovie);

                $showtime->setAuditorium($auditorium);
                $showtime->setMovie($movie);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $showtime;
    }

    public function retrieveNumberOfRowsByDate($date) {
        $value = 0;

        try
        {
            $parameters['date'] = $date;

            $query = "SELECT COUNT(*) FROM showtimes WHERE date>=:date";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            $value = $resultSet[0]["COUNT(*)"];
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    public function retrieveByPageAndDate($offset, $no_of_records_per_page, $date, $tickets) {
        $showtimeList = array();

        try
        {
            $parameters['date'] = $date;

            if($tickets == 1)
                $query = "SELECT * FROM showtimes WHERE date>=:date and total_tickets > tickets_sold ORDER BY date,opening_time LIMIT " . $offset . " , " . $no_of_records_per_page;
            else if($tickets == 0)
                $query = "SELECT * FROM showtimes WHERE date>=:date ORDER BY date,opening_time LIMIT " . $offset . " , " . $no_of_records_per_page;
           
            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $showtime = $this->read($row);

                    $idAuditorium = $row["id_auditorium"];
                    $idMovie = $row["id_movie"];

                    $auditoriumDAO = new DAO_Auditorium();
                    $auditorium = $auditoriumDAO->retrieveOne($idAuditorium);

                    $movieDAO = new DAO_Movie();
                    $movie = $movieDAO->retrieveOneNoCheckMovieDate($idMovie);

                    $showtime->setAuditorium($auditorium);
                    $showtime->setMovie($movie);

                    array_push($showtimeList, $showtime);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }

        return $showtimeList;
    }

    public function updateTicketsSold($id, $ticketsSold) {
        $parameters['id'] = $id;
        $parameters['ticketsSold'] = $ticketsSold;
        $query = "UPDATE showtimes SET tickets_sold=:ticketsSold WHERE id=:id";
  
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