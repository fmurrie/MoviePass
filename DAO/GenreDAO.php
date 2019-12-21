<?php namespace DAO;
use Models\Genre as Genre;
use \PDOException as PDOException;

class GenreDAO {

    public function retrieveAllGenresFromApi() {
        //API The Movie Database
        $json = file_get_contents("https://api.themoviedb.org/3/genre/movie/list?api_key=" . API_KEY);
        $APIArray = json_decode($json, true);
        $APIDataArray = $APIArray["genres"];

        $arrayOfGenres = array();
        for($i=0; $i<count($APIDataArray); $i++) {
            $dataGenre = $APIDataArray[$i];
            $name_genre = $dataGenre["name"];
            $id = $dataGenre["id"];
            $genre = new Genre();
            $genre->setId($id);
            $genre->setName($name_genre);
            array_push($arrayOfGenres, $genre);
        }

        return $arrayOfGenres;
    }

    public function create(Genre $genre) {
        $value = 0;

        try
        {
            $query = "INSERT INTO genres (id_api_genre, name_genre) VALUES (:id, :nameGenre)";
            $parameters["id"] = $genre->getID();
            $parameters["nameGenre"] = $genre->getName();

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
        $genre = new Genre();
        $genre->setID($row["id_api_genre"]);
        $genre->setIdBd($row["id_genre"]);
        $genre->setName($row["name_genre"]);
        return $genre;
    }

    public function retrieveAll() {
        $genreList = array();

        try
        {
            $query = "SELECT * FROM genres";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                foreach ($resultSet as $row) {
                    $genre = $this->read($row);
                    array_push($genreList, $genre);
                }
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $genreList;
    }

    public function updateName($id_api, $name_genre) {
        $parameters['id_api'] = $id_api;
        $parameters['name_genre'] = $name_genre;
        $query = "UPDATE genres SET name_genre=:name_genre WHERE id_api_genre=:id_api";
  
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
}
?>