<?php namespace DAO;
use Models\Movie as Movie;
use Models\Genre as Genre;

class MovieDAO {
    
    /* ------ API ------ */

    public function retrieveAllMoviesNowPlayingFromApi() {
        
        //API The Movie Database
        $json = file_get_contents("https://api.themoviedb.org/3/movie/now_playing?api_key=" . API_KEY);
        $arregloAPI = json_decode($json, true);
        $APIDataArray = $arregloAPI["results"];
        $arrayOfMovies = array();
        for($i=0; $i<count($APIDataArray); $i++) {
            $dataMovie = $APIDataArray[$i];
            $name = $dataMovie["title"];
            $id = $dataMovie["id"];
            $synopsis = $dataMovie["overview"];
            $poster = "http://image.tmdb.org/t/p/original" . $dataMovie["poster_path"];
            $background = "http://image.tmdb.org/t/p/original" . $dataMovie["backdrop_path"];
            $score = $dataMovie["vote_average"];
            $arrayOfGenresID = $dataMovie["genre_ids"];
            $movie = new Movie();
            $movie->setName($name);
            $movie->setId($id);
            $movie->setSynopsis($synopsis);
            $movie->setPoster($poster);
            $movie->setBackground($background);
            $movie->setScore($score);
            $movie->setGenres($arrayOfGenresID);
              
            $date = date('Y/m/d', time());
            $movie->setUploadingDate($date);
            array_push($arrayOfMovies, $movie);
        }

        return $arrayOfMovies;
    }

    public function retrieveDurationOneMovieFromApi($id) {
        $json = file_get_contents("https://api.themoviedb.org/3/movie/" . $id . "?api_key=" . API_KEY);
        $APIDataArray = json_decode($json, true);
        $runtime = $APIDataArray["runtime"];
        if($runtime == null) {
            $runtime = 90;
        }
        return $runtime;
    }

    public function retrieveTrailerOneMovieFromApi($id) {
        $json = file_get_contents("https://api.themoviedb.org/3/movie/" . $id . "/videos?api_key=" . API_KEY);
        $APIDataArray = json_decode($json, true);
        $link = $APIDataArray["results"][0]["key"];
        return "https://www.youtube.com/embed/" . $link;
    }

    public function retrievePhotosOneMovieFromApi($id) {
        $json = file_get_contents("https://api.themoviedb.org/3/movie/" . $id . "/images?api_key=" . API_KEY);
        $APIDataArray = json_decode($json, true);
        $photos = $APIDataArray["backdrops"];
        return $photos;
    }

    /* ------ DATABASE ------ */

    public function create(Movie $movie) {
        $value = 0;

        try
        {
            $query = "INSERT INTO movies (id_api_movie, name_movie, synopsis, poster, background, score, uploading_date) VALUES (:id, :name, :synopsis, :poster, :background, :score, :uploadingDate)";
            $parameters["id"] = $movie->getId();
            $parameters["name"] = $movie->getName();
            $parameters["synopsis"] = $movie->getSynopsis();
            $parameters["poster"] = $movie->getPoster();
            $parameters["background"] = $movie->getBackground();
            $parameters["score"] = $movie->getScore();
            $parameters["uploadingDate"] = $movie->getUploadingDate();

            $this->connection = Connection::getInstance();

            $value = $this->connection->executeNonQuery($query, $parameters);

            $arrayOfGenres = $movie->getGenres();
            
            foreach($arrayOfGenres as $genre) {
         
                $queryIdBd = "SELECT p.id_movie FROM movies p where p.id_api_movie=" . $movie->getID();
                $resultSet = $this->connection->execute($queryIdBd);
                $idBd = $resultSet[0]["id_movie"];

                $parametersGenres["idMovie"] = $idBd;
                $parametersGenres["idGenre"] = $genre->getIdBd();

                $queryGenres = "INSERT INTO genres_per_movie (id_movie, id_genre) VALUES (:idMovie, :idGenre)";

                $value = $this->connection->executeNonQuery($queryGenres, $parametersGenres);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $value;
    }

    private function read($row) {
        $movie = new Movie();
        $movie->setIdBd($row["id_movie"]);
        $movie->setId($row["id_api_movie"]);
        $movie->setName($row["name_movie"]);
        $movie->setSynopsis($row["synopsis"]);
        $movie->setPoster($row["poster"]);
        $movie->setBackground($row["background"]);
        $movie->setScore($row["score"]);
        $movie->setUploadingDate($row["uploading_date"]);
        return $movie;
    }

    private function readGenreData($row) {
        $genre = new Genre();
        $genre->setIdBd($row["id_genre"]);
        $genre->setId($row["id_api_genre"]);
        $genre->setName($row["name_genre"]);
        return $genre;
    }

    public function retrieveAll() {
        $movieList = array();

        try
        {
            $parameters['date'] = $this->getMaxDate();

            $query = "SELECT * FROM movies p INNER JOIN genres_per_movie ge on ge.id_movie = p.id_movie INNER JOIN genres g on g.id_genre = ge.id_genre where p.uploading_date=:date order by p.id_movie";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);

            if(!empty($resultSet)) {
                $previousMovie = null;
      
                foreach ($resultSet as $row) {

                    $genre = $this->readGenreData($row);

                    if($previousMovie == null) {

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] != $previousMovie->getIdBd()) {

                        array_push($movieList, $previousMovie);

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] == $previousMovie->getIdBd()) {

                        $previousMovie->addGenre($genre);
                    }
                }
                if(isset($previousMovie))
                    array_push($movieList, $previousMovie);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieList;
    }

    public function retrieveAllNoCheckMovieDate() {
        $movieList = array();

        try
        {
            $query = "SELECT * FROM movies p INNER JOIN genres_per_movie ge on ge.id_movie = p.id_movie INNER JOIN genres g on g.id_genre = ge.id_genre order by p.id_movie";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                $previousMovie = null;
      
                foreach ($resultSet as $row) {

                    $genre = $this->readGenreData($row);

                    if($previousMovie == null) {

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] != $previousMovie->getIdBd()) {

                        array_push($movieList, $previousMovie);

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] == $previousMovie->getIdBd()) {

                        $previousMovie->addGenre($genre);
                    }
                }
                if(isset($previousMovie))
                    array_push($movieList, $previousMovie);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieList;
    }

    public function retrieveAllNoFilter() {
        $movieList = array();

        try
        {
            $query = "SELECT * FROM movies p INNER JOIN genres_per_movie ge on ge.id_movie = p.id_movie INNER JOIN genres g on g.id_genre = ge.id_genre order by p.id_movie";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);

            if(!empty($resultSet)) {
                $previousMovie = null;
      
                foreach ($resultSet as $row) {

                    $genre = $this->readGenreData($row);

                    if($previousMovie == null) {

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] != $previousMovie->getIdBd()) {

                        array_push($movieList, $previousMovie);

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] == $previousMovie->getIdBd()) {

                        $previousMovie->addGenre($genre);
                    }
                }
                if(isset($previousMovie))
                    array_push($movieList, $previousMovie);
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieList;
    }

    public function retrieveOne($idBd) {
        $movieToReturn = null;

        try
        {
            $parameters['date'] = $this->getMaxDate();

            $query = "SELECT * FROM movies p INNER JOIN genres_per_movie ge on ge.id_movie = p.id_movie 
            INNER JOIN genres g on g.id_genre = ge.id_genre WHERE p.uploading_date=:date and p.id_movie=" . $idBd . " ORDER BY p.id_movie";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query, $parameters);
            
            if(!empty($resultSet)) {
                $previousMovie = null;
                foreach ($resultSet as $row) {

                    $genre = $this->readGenreData($row);

                    if($previousMovie == null) {

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] == $previousMovie->getIdBd()) {

                        $previousMovie->addGenre($genre);
                    }
                }
                if(isset($previousMovie))
                    $movieToReturn = $previousMovie;
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieToReturn;
    }

    public function retrieveOneNoCheckMovieDate($idBd) {
        $movieToReturn = null;

        try
        {
            $query = "SELECT * FROM movies p INNER JOIN genres_per_movie ge on ge.id_movie = p.id_movie 
            INNER JOIN genres g on g.id_genre = ge.id_genre WHERE p.id_movie=" . $idBd . " ORDER BY p.id_movie";

            $this->connection = Connection::getInstance();

            $resultSet = $this->connection->execute($query);
            
            if(!empty($resultSet)) {
                $previousMovie = null;
                foreach ($resultSet as $row) {

                    $genre = $this->readGenreData($row);

                    if($previousMovie == null) {

                        $movie = $this->read($row);

                        $movie->addGenre($genre);

                        $previousMovie = $movie;
                    }
                    else if($previousMovie != null && $row["id_movie"] == $previousMovie->getIdBd()) {

                        $previousMovie->addGenre($genre);
                    }
                }
                if(isset($previousMovie))
                    $movieToReturn = $previousMovie;
            }
        }
        catch (PDOException $e)
        {
            throw $e;
        }
        return $movieToReturn;
    }

    public function updateUploadingDate($idApi, $uploadingDate) {
        $parameters['uploadingDate'] = $uploadingDate;
        $parameters['idApi'] = $idApi;

        $query = "UPDATE movies SET uploading_date=:uploadingDate WHERE id_api_movie=:idApi";
  
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

    public function getMaxDate() {
        $value = null;

        try
        {            
            $query = "SELECT max(p.uploading_date) as max_date from movies p";
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($query);

            $value = $resultSet[0]["max_date"];
        }
        catch(PDOException $e)
        {
            throw $e;
        }
        return $value;
    }
}
?>