<?php
namespace Models;
class Ticket {
    private $id;
    private $idPurchase;
    private $number;
    private $showtime;

    public function __construct() {

    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getIdPurchase() {
        return $this->idPurchase;
    }

    public function setIdPurchase($idPurchase) {
        $this->idPurchase = $idPurchase;
    }
    
    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }
    
    public function getShowtime() {
        return $this->showtime;
    }

    public function setShowtime($showtime) {
        $this->showtime = $showtime;
    }
}
?>