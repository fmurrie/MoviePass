<?php namespace DAO;
use Models\Purchase as Purchase;
use Models\Ticket as Ticket;
use DAO\TicketDAO as DAO_Ticket;
use DAO\ShowtimeDAO as DAO_Showtime;
use DAO\UserDAO as DAO_User;
use DAO\PaymentDAO as DAO_Payment;
use DAO\Connection as Connection;
use \PDOException as PDOException;

class PurchaseDAO {

    public function create(Purchase $purchase) {
        $value = 0;

        try
        {
            //1. Insert - Purchase
            $query = "INSERT INTO purchases (purchased_tickets, date_purchase, discount, id_user)
            VALUES (:totalTickets, :date, :discount, :idUser)";
            $parameters["totalTickets"] = $purchase->getTotalTickets();
            $parameters["date"] = $purchase->getDate();
            $parameters["discount"] = $purchase->getDiscount();
            $parameters["idUser"] = $purchase->getUser()->getId();
            $this->connection = Connection::getInstance();
            $value = $this->connection->executeNonQuery($query, $parameters);
            //
            
            //2. Insert - Payment
            $daoPayment = new DAO_Payment();
            $query = "SELECT MAX(id_purchase) as max_id FROM purchases ORDER BY id_purchase";
            $resultSet = $this->connection->execute($query);
            $idPurchase = $resultSet[0]["max_id"];
            $purchase->setId($idPurchase);
            $purchase->getPayment()->setIdPurchase($idPurchase);
            $daoPayment->create($purchase->getPayment());
            //

            //3. Insert - Ticket/s
            $daoTicket = new DAO_Ticket();
            foreach($purchase->getTickets() as $ticket) {
                $ticket->setIdPurchase($idPurchase);
                $daoTicket->create($ticket);
            }
            //

            //4. Update id_payment in purchases
            $queryId = "SELECT pay.id_payment as id_payment FROM payments as pay where pay.id_purchase=" . $purchase->getId();
            $resultSet = $this->connection->execute($queryId);
            $idPayment = $resultSet[0]["id_payment"];
            $parametersUpdate["id"] = $purchase->getId();
            $parametersUpdate["idPayment"] = $idPayment;
            $query = "UPDATE purchases SET id_payment=:idPayment where id_purchase=:id";
            $value = $this->connection->executeNonQuery($query, $parametersUpdate);
            $value = $idPurchase;
            //
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    public function retrieveByPageAndDate($offset, $no_of_records_per_page, $date, $user) {
        $purchaseList = array();

        try
        {
            $parameters['date'] = $date;

            $query = "SELECT * FROM tickets t
            INNER JOIN purchases p on p.id_purchase = t.id_purchase
            INNER JOIN payments on p.id_payment = p.id_payment
            WHERE p.date_purchase>=:date AND p.id_user=" . $user->getId() . "
            ORDER BY p.date_purchase";
           
            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {

                $previousPurchase = null;
    
                foreach ($resultSet as $row) {

                    //Ticket
                    $ticket = new Ticket();
                    $ticket->setId($row["id_ticket"]);
                    $ticket->setIdPurchase($row["id_purchase"]);
                    $ticket->setNumber($row["number"]);
                    $idShowtime = $row["id_showtime"];
                    //Showtime
                    $showtimeDAO = new DAO_Showtime();
                    $showtime = $showtimeDAO->retrieveOne($idShowtime);
                    $ticket->setShowtime($showtime);

                    if($previousPurchase == null) {

                        $purchase = new Purchase();
                        $purchase->setId($row["id_purchase"]);
                        $purchase->setTotalTickets($row["purchased_tickets"]);
                        $purchase->setDate($row["date_purchase"]);
                        $purchase->setDiscount($row["discount"]);
                        $purchase->setQr($row["qr"]);
                        $idUser = $row["id_user"];
                        $idPayment = $row["id_payment"];
                        //User
                        $userDAO = new DAO_User();
                        $user = $userDAO->retrieveOne($idUser);
                        $purchase->setUser($user);
                        //Payment
                        $paymentDAO = new DAO_Payment();
                        $payment = $paymentDAO->retrieveOne($idPayment);
                        $purchase->setPayment($payment);

                        $purchase->addTicket($ticket);

                        $previousPurchase = $purchase;
                    }
                    else if($previousPurchase != null && $row["id_purchase"] != $previousPurchase->getId()) {

                        array_push($purchaseList, $previousPurchase);

                        $purchase = new Purchase();
                        $purchase->setId($row["id_purchase"]);
                        $purchase->setTotalTickets($row["purchased_tickets"]);
                        $purchase->setDate($row["date_purchase"]);
                        $purchase->setDiscount($row["discount"]);
                        $purchase->setQr($row["qr"]);
                        $idUser = $row["id_user"];
                        $idPayment = $row["id_payment"];
                        //User
                        $userDAO = new DAO_User();
                        $user = $userDAO->retrieveOne($idUser);
                        $purchase->setUser($user);
                        //Payment
                        $paymentDAO = new DAO_Payment();
                        $payment = $paymentDAO->retrieveOne($idPayment);
                        $purchase->setPayment($payment);

                        $purchase->addTicket($ticket);

                        $previousPurchase = $purchase;
                    }
                    else if($previousPurchase != null && $row["id_purchase"] == $previousPurchase->getId()) {

                        $previousPurchase->addTicket($ticket);
                    }
                }
                if(isset($previousPurchase))
                    array_push($purchaseList, $previousPurchase);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }

        return $purchaseList;
    }

    public function retrieveByPageAndDateNoTickets($offset, $no_of_records_per_page, $actualDate, $user, $filter) {
        $purchaseList = array();

        try
        {
            $parameters['date'] = $actualDate;

            if($filter == "1") { //per date
                $query = "SELECT * FROM tickets t
                INNER JOIN purchases p on p.id_purchase = t.id_purchase
                INNER JOIN payments pa on pa.id_payment = p.id_payment
                INNER JOIN showtimes s on s.id = t.id_showtime
                WHERE s.date>=:date AND p.id_user=" . $user->getId() . " GROUP BY p.id_purchase
                ORDER BY p.date_purchase
                LIMIT " . $offset . " , " . $no_of_records_per_page;
            }
            else if($filter == "0") { //per movie
                $query = "SELECT * FROM tickets t
                INNER JOIN purchases p on p.id_purchase = t.id_purchase
                INNER JOIN payments pa on pa.id_payment = p.id_payment
                INNER JOIN showtimes s on s.id = t.id_showtime
                INNER JOIN movies m on m.id_movie = s.id_movie
                WHERE s.date>=:date AND p.id_user=" . $user->getId() . " GROUP BY p.id_purchase
                ORDER BY m.name_movie, p.date_purchase
                LIMIT " . $offset . " , " . $no_of_records_per_page;
            }

   
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($query, $parameters);
           
            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                //Ticket
                $ticket = new Ticket();
                $ticket->setId($row["id_ticket"]);
                $ticket->setIdPurchase($row["id_purchase"]);
                $ticket->setNumber($row["number"]);
                $idShowtime = $row["id_showtime"];
                //Showtime
                $showtimeDAO = new DAO_Showtime();
                $showtime = $showtimeDAO->retrieveOne($idShowtime);
                $ticket->setShowtime($showtime);
                $purchase = new Purchase();
                $purchase->setId($row["id_purchase"]);
                $purchase->setTotalTickets($row["purchased_tickets"]);
                $purchase->setDate($row["date_purchase"]);
                $purchase->setDiscount($row["discount"]);
                $purchase->setQr($row["qr"]);
                $idUser = $row["id_user"];
                $idPayment = $row["id_payment"];
                //User
                $userDAO = new DAO_User();
                $user = $userDAO->retrieveOne($idUser);
                $purchase->setUser($user);
                //Payment
                $paymentDAO = new DAO_Payment();
                $payment = $paymentDAO->retrieveOne($idPayment);
                $purchase->setPayment($payment);
                $purchase->addTicket($ticket);

                array_push($purchaseList, $purchase);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }

        return $purchaseList;
    }

    public function retrieveNumberOfRowsByDate($date, $user) {
        $value = 0;

        try
        {
            $parameters['date'] = $date;

            $query = "SELECT COUNT(*) FROM purchases p
            WHERE p.id_purchase in 
            (select t.id_purchase from tickets t
            INNER JOIN showtimes s on s.id = t.id_showtime
            WHERE s.date>=:date)
            AND p.id_user=" . $user->getId() . ";";

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

    public function retrieveOne($id) {
        $purchase = array();

        try
        {
            $query = "SELECT * FROM purchases
            WHERE id_purchase=" . $id;
            
            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {

                $purchase = new Purchase();
                $purchase->setId($resultSet[0]["id_purchase"]);
                $purchase->setTotalTickets($resultSet[0]["purchased_tickets"]);
                $purchase->setDate($resultSet[0]["date_purchase"]);
                $purchase->setDiscount($resultSet[0]["discount"]);
                $purchase->setQr($resultSet[0]["qr"]);
                $idUser = $resultSet[0]["id_user"];
                $idPayment = $resultSet[0]["id_payment"];
                //User
                $userDAO = new DAO_User();
                $user = $userDAO->retrieveOne($idUser);
                $purchase->setUser($user);
                //Payment
                $paymentDAO = new DAO_Payment();
                $payment = $paymentDAO->retrieveOne($idPayment);
                $purchase->setPayment($payment);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }

        return $purchase;
    }

    public function loadQr($imagetmp, $id) {
        $parameters['id'] = $id;
        $parameters['imagetmp'] = $imagetmp;
        $query = "UPDATE purchases SET qr=:imagetmp WHERE id_purchase=:id";

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

    public function loadTotal($date, $selection, $id) {
        $value = 0;
        $parameters['date'] = $date;

        if($selection == "movie") {
            $query =
            "SELECT SUM(pa.total) as total
            FROM purchases p
            INNER JOIN payments pa on pa.id_payment = p.id_payment
            WHERE p.date_purchase>=:date AND p.id_purchase in (
                                                                SELECT t.id_purchase
                                                                FROM tickets t
                                                                INNER JOIN showtimes s on s.id = t.id_showtime
                                                                INNER JOIN movies m on s.id_movie = m.id_movie
                                                                WHERE m.id_movie=" . $id . "
                                                                GROUP BY t.id_purchase
                                                               )";
        }
        else if($selection == "movieTheater") {
            $query =
            "SELECT SUM(pa.total) as total
            FROM purchases p
            INNER JOIN payments pa on pa.id_payment = p.id_payment
            WHERE p.date_purchase>=:date AND p.id_purchase in (
                                                                SELECT t.id_purchase
                                                                FROM tickets t
                                                                INNER JOIN showtimes s on s.id = t.id_showtime
                                                                INNER JOIN auditoriums a on s.id_auditorium = a.id
                                                                INNER JOIN movie_theaters m on a.id_movieTheater = m.id
                                                                WHERE m.id=" . $id . "
                                                                GROUP BY t.id_purchase
                                                               )";
        }

        try
        {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($query, $parameters);
            $value = $resultSet[0]["total"];
        }
        catch(PDOException $e)
        {
            throw $e;
        }

        return $value;
    }
}
?>
