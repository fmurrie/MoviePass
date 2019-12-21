<?php
namespace Models;
class Purchase {
    private $id;
    private $totalTickets;
    private $date;
    private $discount; //%
    private $qr;
    private $user;
    private $payment;
    private $tickets;

    public function __construct() {
        $this->tickets = array();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTotalTickets()
    {
        return $this->totalTickets;
    }

    public function setTotalTickets($totalTickets) {
        $this->totalTickets = $totalTickets;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }
    
    public function getQr() {
        return $this->qr;
    }

    public function setQr($qr) {
        $this->qr = $qr;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getPayment() {
        return $this->payment;
    }

    public function setPayment($payment) {
        $this->payment = $payment;
    }

    public function getTickets() {
        return $this->tickets;
    }

    public function setTickets($tickets) {
        $this->tickets = $tickets;
    }

    public function addTicket($ticket) {
        array_push($this->tickets, $ticket);
    }
}
?>