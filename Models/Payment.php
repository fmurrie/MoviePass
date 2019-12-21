<?php
namespace Models;
class Payment {
    private $id;
    private $idPurchase;
    private $total;

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
    
    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }
}
?>