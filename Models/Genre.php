<?php
namespace Models;
class Genre {
    private $idBd;
    private $id; //ID API
    private $name;
    private $idMovies;

    public function __construct() {
        $this->idMovies = array();
    }

    public function getIdBd() {
        return $this->idBd;
    }

    public function setIdBd($idBd) {
        $this->idBd = $idBd;

        return $this;
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
    
    public function getIdMovies() {
        return $this->idMovies;
    }

    public function setIdMovies($idMovies) {
        $this->idMovies = $idMovies;
    }
    
    public function addIdMovie($idMovie) {
        array_push($this->idMovies, $idMovie);
    }
}
?>