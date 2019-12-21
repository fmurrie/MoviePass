<?php namespace DAO;
use Models\Ticket as Ticket;
use DAO\Connection as Connection;
use \PDOException as PDOException;

class TicketDAO {

    private $connection;

    public function create(Ticket $ticket) {
        $value = 0;

        try
        {
            $query = "INSERT INTO tickets (number, id_purchase, id_showtime) VALUES (:number, :idPurchase, :idShowtime)";
            $parameters["number"] = $ticket->getNumber();
            $parameters["idPurchase"] = $ticket->getIdPurchase();
            $parameters["idShowtime"] = $ticket->getShowtime()->getId();

            $this->connection = Connection::getInstance();

            $value = $this->connection->executeNonQuery($query, $parameters);
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $value;
    }
}