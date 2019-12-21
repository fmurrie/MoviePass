<?php
namespace Models;
class Showtime {
    private $id;
    private $date;
    private $openingTime;
    private $closingTime;
    private $ticketsSold;
    private $totalTickets;
    private $ticketPrice;
    private $auditorium;
    private $movie;

    public function __construct() {
        $this->ticketsSold = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getOpeningTime() {
        return $this->openingTime;
    }

    public function setOpeningTime($openingTime) {
        $this->openingTime = $openingTime;
    }

    public function getClosingTime() {
        return $this->closingTime;
    }
    
    public function setClosingTime($closingTime) {
        $this->closingTime = $closingTime;
    }

    public function generateClosingTime(int $minutes) {

        $minutes += 15; //+15 min
        $openingTime = $this->openingTime;

        list($hour, $minute) = explode(':', $openingTime);
        $minutes += $hour * 60;
        $minutes += $minute;

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;
    
        $closingTime = sprintf('%02d:%02d', $hours, $minutes);
        $this->closingTime = $closingTime;
    }
    
    public function getTicketsSold() {
        return $this->ticketsSold;
    }

    public function setTicketsSold($ticketsSold) {
        $this->ticketsSold = $ticketsSold;
    }
    
    public function getTotalTickets() {
        return $this->totalTickets;
    }
    
    public function setTotalTickets($totalTickets) {
        $this->totalTickets = $totalTickets;
    }

    public function getTicketPrice() {
        return $this->ticketPrice;
    }

    public function setTicketPrice($ticketPrice) {
        $this->ticketPrice = $ticketPrice;
    }
    
    public function getAuditorium() {
        return $this->auditorium;
    }

    public function setAuditorium($auditorium) {
        $this->auditorium = $auditorium;
    }

    public function getMovie() {
        return $this->movie;
    }

    public function setMovie($movie) {
        $this->movie = $movie;
    }
}
?>