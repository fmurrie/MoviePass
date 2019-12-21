<?php namespace DAO;
use Models\Payment as Payment;
use DAO\Connection as Connection;
use \PDOException as PDOException;

class PaymentDAO {

    private $connection;

    public function create(Payment $payment) {
        $value = 0;

        try
        {
            $query = "INSERT INTO payments (total, id_purchase) VALUES (:total, :idPurchase)";
            $parameters["total"] = $payment->getTotal();
            $parameters["idPurchase"] = $payment->getIdPurchase();

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
        $payment = new Payment();
        $payment->setId($row["id_payment"]);
        $payment->setIdPurchase($row["id_purchase"]);
        $payment->setTotal($row["total"]);
        return $payment;
    }

    public function retrieveAll() {
        $paymentList = array();

        try
        {
            $query = "SELECT * FROM payments";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $payment = $this->read($row);
    
                    array_push($paymentList, $payment);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $paymentList;
    }

    public function retrieveOne($id) {
        $payment = null;

        try
        {
            $parameters['id'] = $id;

            $query = "SELECT * FROM payments WHERE id_payment=:id";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $payment = $this->read($resultSet[0]);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $payment;
    }
}