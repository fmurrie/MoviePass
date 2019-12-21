<?php
namespace Models;
class Movie {
    private $idBd;
    private $id; //ID API
    private $name;
    private $synopsis;
    private $poster;
    private $background;
    private $score;
    private $uploadingDate;
    private $genres;

    public function __construct() {
        $this->genres = array();
    }

    public function getIdBd() {
        return $this->idBd;
    }

    public function setIdBd($idBd) {
        $this->idBd = $idBd;
    }

    public function getID(){
        return $this->id;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSynopsis() {
        return $this->synopsis;
    }

    public function setSynopsis($synopsis) {
        $this->synopsis = $synopsis;
    }

    public function getPoster() {
        return $this->poster;
    }

    public function setPoster($poster) {
        $this->poster = $poster;
    }

    public function getBackground() {
        return $this->background;
    }

    public function setBackground($background) {
        $this->background = $background;
    }

    public function getScore() {
        return $this->score;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function getUploadingDate() {
        return $this->uploadingDate;
    }

    public function setUploadingDate($uploadingDate) {
        $this->uploadingDate = $uploadingDate;
    }

    public function getGenres() {
        return $this->genres;
    }

    public function getNameGenres() {
        $string = "";
        foreach($this->genres as $genre) {
            $string = $string . " | " . $genre->getName();
        }
        $string = $string . " | "; 
        return $string;
    }

    public function setGenres($genres) {
        $this->genres = $genres;
    }

    public function addGenre($genre) {
        array_push($this->genres, $genre);
    }
}
?>