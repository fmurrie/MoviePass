<?php
namespace Models;
class Auditorium {
    private $id;
    private $movieTheater;
    private $state;
    private $name;
    private $capacity;
    private $ticketPrice;

    public function __construct() {

    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getMovieTheater() {
        return $this->movieTheater;
    }

    public function setMovieTheater($movieTheater) {
        $this->movieTheater = $movieTheater;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function setCapacity($capacity) {
        $return = 1;
        if($capacity > 0)
            $this->capacity = $capacity;
        else
            $return = 0;
        return $return;
    }

    public function getTicketPrice() {
        return $this->ticketPrice;
    }

    public function setTicketPrice($ticketPrice) {
        $return = 1;
        if($ticketPrice > 0)
            $this->ticketPrice = $ticketPrice;
        else
            $return = 0;
        return $return;
    }
}
?>