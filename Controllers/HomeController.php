<?php
namespace Controllers;

/*Alias - Controllers*/
use Controllers\UserController as C_User;
use Controllers\MovieTheaterController as C_MovieTheater;
use Controllers\AuditoriumController as C_Auditorium;
use Controllers\MovieController as C_Movie;
use Controllers\GenreController as C_Genre;
use Controllers\ShowtimeController as C_Showtime;
use Controllers\PurchaseController as C_Purchase;

class HomeController {
    
    function __construct() {

    }

    public function index($message = null, $messageType = null) {
        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        include(VIEWS . "home.php");
        include(VIEWS . "footer.php");
    }

    public function login($message = null, $messageType = null) {
        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "login.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function account($message = null, $messageType = null) {
        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function signup($message = null, $messageType = null) {
        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "signup.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function admin_movietheaters($pageno = 0, $message = null, $messageType = null) {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {

            if($pageno == 0) {
                $pageno = 1;
            }

            $no_of_records_per_page = 5;
            $offset = ($pageno-1) * $no_of_records_per_page;

            $movieTheaterController = new C_MovieTheater();

            $total_rows = $movieTheaterController->loadNumberOfRows();

            $total_pages = ceil($total_rows / $no_of_records_per_page);

            $listOfMovieTheaters = $movieTheaterController->loadMovieTheatersByPage($offset, $no_of_records_per_page);
            include(VIEWS . "admin_movietheaters.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }
    
    public function update_movietheater($idMovieTheater = null, $pageno = null, $message = null, $messageType = null) {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {
            $movieTheaterController = new C_MovieTheater();
            $movieTheater = $movieTheaterController->loadMovieTheaterById($idMovieTheater);

            include(VIEWS . "update_movietheater.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function admin_auditoriums($idMovieTheater = null, $pageno = 0, $pagenoMovieTheater = 0, $message = null, $messageType = null) {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {

            $movieTheaterController = new C_MovieTheater();
            $movieTheater = $movieTheaterController->loadMovieTheaterById($idMovieTheater);

            if($pageno == 0) {
                $pageno = 1;
            }

            $no_of_records_per_page = 5;
            $offset = ($pageno-1) * $no_of_records_per_page;

            $auditoriumController = new C_Auditorium();

            $total_rows = $auditoriumController->loadNumberOfRows($idMovieTheater);

            $total_pages = ceil($total_rows / $no_of_records_per_page);

            $auditoriumList = $auditoriumController->loadAuditoriumsByPage($offset, $no_of_records_per_page, $idMovieTheater);
            include(VIEWS . "admin_auditoriums.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }
    
    public function update_auditorium($id = null, $pageno = 0, $pagenoMovieTheater = 0, $message = null, $messageType = null) {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {

            $auditoriumController = new C_Auditorium();
            $auditorium = $auditoriumController->loadAuditoriumById($id);

            $movieTheaterController = new C_MovieTheater();
            $movieTheater = $movieTheaterController->loadMovieTheaterById($auditorium->getMovieTheater()->getId());
            include(VIEWS . "update_auditorium.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function showtime_list($pageno = 0) {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {

            if($pageno == 0) {
                $pageno = 1;
            }

            $no_of_records_per_page = 5;
            $offset = ($pageno-1) * $no_of_records_per_page;

            $showtimeController = new C_Showtime();

              
            $date = date('Y-m-d', time());
            
            $total_rows = $showtimeController->loadNumberOfRowsByDate($date);

            $total_pages = ceil($total_rows / $no_of_records_per_page);

            $listOfShowtimes = $showtimeController->loadShowtimesByPageAndDate($offset, $no_of_records_per_page, $date, 0);

            include(VIEWS . "showtime_list.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function new_showtime($message = null, $messageType = null) {
        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {

            /*Loading movies ..*/
            $movieController = new C_Movie();
            $movieController->loadMoviesNowPlayingIntoBD();
            $arrayOfMovies = $movieController->loadMovies();

            /*Loading movie theaters ..*/
            $movieTheaterController = new C_MovieTheater();
            $totalListOfMT = $movieTheaterController->loadMovieTheaters();
            $auditoriumController = new C_Auditorium();
            $listOfMovieTheaters = array();

            /*Filtering movie theaters (state = 1 and with at least one auditorium) ..*/
            foreach($totalListOfMT as $movieTheater) {
                if($movieTheater->getState() == 1) {

                    $value = false;
                    $auditoriums = $auditoriumController->loadAuditoriumsActive($movieTheater->getId());
                    if($auditoriums != null)
                        $value = true;

                    if($value == true)
                        array_push($listOfMovieTheaters, $movieTheater);
                }
            }

            include(VIEWS . "new_showtime.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function total_sales_amount($id = null, $option = "movie", $date = null) {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {

            $total = "0";
            if($option == "movie") {
                /*Loading ALL movies .. no date filter*/
                $movieController = new C_Movie();
                $movieController->loadMoviesNowPlayingIntoBD();
                $listOfMovies = $movieController->getAllMovies();
                //
            }
            else if($option == "movieTheater") {
                /*Loading ALL movie theaters (state = 1 or 0) ..*/
                $movieTheaterController = new C_MovieTheater();
                $listOfMovieTheaters = $movieTheaterController->loadMovieTheaters();
                //
            }

            if(isset($id) && isset($date) && isset($option)) {
                
                $purchaseController = new C_Purchase();

                $total = $purchaseController->loadTotalSalesAmount($date, $option, $id);

                if($total == null) {
                    $total = "0";
                }
                include(VIEWS . "total_sales_amount.php");
            }
            else {
                include(VIEWS . "total_sales_amount.php");
            }
        }
        else if ($user->getUserRoleDescription()=="user") {
            include(VIEWS . "account.php");
        }
        include(VIEWS . "footer.php");
    }

    public function now_playing($choosenDate = "today", $choosenGenre = "total", $timeOut = null) {

        $userController = new C_User();
        $user = $userController->checkSession();

        /*Update (or no) of movies*/
        $movieController = new C_Movie();
        $movieController->loadMoviesNowPlayingIntoBD();
        //

        /*Loading showtimes to create a movie array ..*/
        if($choosenDate == "today") {
            $choosenDate = date('Y-m-d', time());
        }

        $showtimeController = new C_Showtime();
        $arrayOfShowtimes = $showtimeController->loadShowtimesByDate($choosenDate);
        $arrayOfMovies = array();

          
        $date = date('Y-m-d', time());
        $time = date('H:i', time());

        foreach($arrayOfShowtimes as $showtime) {
            $value = true;

            foreach($arrayOfMovies as $movie) {
                if($movie->getID() == $showtime->getMovie()->getID()) {
                    $value = false;
                    break;
                }
            }

            if($value == true) {
                if($showtime->getDate() == $date && $showtime->getOpeningTime() > $time) {
                    array_push($arrayOfMovies, $showtime->getMovie());
                }

                else if ($showtime->getDate() > $date) {
                    array_push($arrayOfMovies, $showtime->getMovie());   
                }               
            }
        }
        //

        /*Loading genres ..*/
        $genreController = new C_Genre();
        $arrayOfGenres = $genreController->loadGenres();
        //

        /*Filtering genres ..*/
        $arrayOfChoosenMovies = array();
        if($choosenGenre == "total") {
            $arrayOfChoosenMovies = $arrayOfMovies;
        }
        else {
            foreach($arrayOfMovies as $movie) {
                $genresMovie = $movie->getGenres();
                foreach($genresMovie as $genre) {
                    if($genre->getIdBd() == $choosenGenre) {
                        array_push($arrayOfChoosenMovies, $movie);
                        break;
                    }
                }
            }
        }
        //

        /*Loading dates ..*/ 
        $date = date('Y-m-d', time());
        $arrayOfShowtimes = $showtimeController->loadShowtimesByPageAndDate(0, 500, $date, 1);
        $arrayOfDates = array();
        foreach($arrayOfShowtimes as $showtime) {
            $value = true;
            foreach($arrayOfDates as $dateArray) {
                if($dateArray == $showtime->getDate()) {
                    $value = false;
                    break;
                }
            }
            if($value == true && $showtime->getDate() != $date)
                array_push($arrayOfDates, $showtime->getDate());
        }

        if(isset($timeOut)) {
            $message = "The time for pay is over";
            $messageType = 0;
        }
        
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        include(VIEWS . "now_playing.php");
        include(VIEWS . "footer.php");
    }

    public function movie_description($idMovie, $date) {

        $userController = new C_User();
        $user = $userController->checkSession();
        
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");

        /*Loading movie ..*/
        $movieController = new C_Movie();
        $movie = $movieController->loadMovieById($idMovie, 1);

        /*Loading trailer ..*/
        $linkTrailer = $movieController->loadTrailerOneMovie($movie->getID());

        /*Loading background photos ..*/
        $arrayFotos = $movieController->loadPhotosOneMovie($movie->getID());
        $photosToShow = array();
        foreach($arrayFotos as $key => $photo) {
            if($photo["width"] > 1900) {
                array_push($photosToShow, "http://image.tmdb.org/t/p/original" . $arrayFotos[$key]["file_path"]);
            }
        }

        include(VIEWS . "movie_description.php");
        include(VIEWS . "footer.php");
    }

    public function purchase_showtime($idMovie, $date) {

        $userController = new C_User();
        $user = $userController->checkSession();

        if($user == null) {
            $message = "You must be identified to purchase tickets";
            $messageType = 0;
            $this->login($message, $messageType);
        }
        else if($user->getUserRoleDescription()=="user") {
            /*Loading showtimes ..*/
            $showtimeController = new C_Showtime();
            $arrayOfShowtimes = $showtimeController->loadShowtimesByDate($date);
            
            /*Verification 1/2:
            The function must be in the future ..
            Deleting showtimes that do not apply ..*/
            $date = date('Y-m-d', time());
            $time = date('H:i', time());
            $showtimesToPurchase = array();
            foreach($arrayOfShowtimes as $key => $showtime) {
                if($showtime->getMovie()->getIdBd() == $idMovie) {
                    if ($showtime->getDate() == $date && $showtime->getOpeningTime() > $time)
                        array_push($showtimesToPurchase, $showtime);
                    else if ($showtime->getDate() != $date)
                        array_push($showtimesToPurchase, $showtime);
                }
            }
            
            /*Verification 2/2:
            The functions must have tickets*/
            $value = false;

            foreach($showtimesToPurchase as $showtime) {
                if($showtime->getTicketsSold() >= $showtime->getTotalTickets()) {
                    $value = false;
                    break;
                }
                else {
                    $value = true;
                }
            }

            if($value) {
                include(VIEWS . "head.php");
                include(VIEWS . "nav.php");
                include(VIEWS . "purchase_showtime.php");
                include(VIEWS . "footer.php");
            }
            else {
                $message = "The time to buy the tickets has expired or there are not enough tickets available. Try again";
                $messageType = 0;
                $this->index($message, $messageType);
            }
        }
        else if($user->getUserRoleDescription()=="admin") {
            $this->index();
        }
    }

    public function purchase_tickets($idShowtime) {
        
        $userController = new C_User();
        $user = $userController->checkSession();

        if($user == null) {
            $message = "You must be identified to purchase tickets";
            $messageType = 0;
            $this->login($message, $messageType);
        }
        else if($user->getUserRoleDescription()=="user") {
            /*Loading showtime ..*/
            $showtimeController = new C_Showtime();
            $showtime = $showtimeController->loadShowtimeById($idShowtime);

            /*Verification:
            The function must be in the future - The function must have tickets*/
            $actualDate = date('Y-m-d', time());
            $actualTime = date('H:i', time());
            
            if($showtime->getTicketsSold() < $showtime->getTotalTickets() && (($actualDate < $showtime->getDate()) || ($actualDate == $showtime->getDate() && $actualTime < $showtime->getOpeningTime()))) {
                $ticketsSold = $showtime->getTicketsSold();
                $freeTickets = $showtime->getTotalTickets() - $ticketsSold;

                $idMovie = $showtime->getMovie()->getIdBd();
                $date =  $showtime->getDate();

                include(VIEWS . "head.php");
                include(VIEWS . "nav.php");
                include(VIEWS . "purchase_tickets.php");
                include(VIEWS . "footer.php");
            }
            else {
                $message = "The time to buy the tickets has expired or there are not enough tickets available. Try again";
                $messageType = 0;
                $this->index($message, $messageType);
            }
        }
        else if($user->getUserRoleDescription()=="admin") {
            $this->index();
        }
    }

    public function purchase_payment($idShowtime, $date, $quantityTickets, $total) {
        
        $userController = new C_User();
        $user = $userController->checkSession();
 
        if($user == null) {
            $message = "You must be identified to purchase tickets";
            $messageType = 0;
            $this->login($message, $messageType);
        }
        else if($user->getUserRoleDescription()=="user") {
            /*Loading showtime ..*/
            $showtimeController = new C_Showtime();
            $showtime = $showtimeController->loadShowtimeById($idShowtime);

            /*Verification:
            The function must be in the future - The function must have tickets*/
            $actualDate = date('Y-m-d', time());
            $actualTime = date('H:i', time());
            $endTime = strtotime("+3 minutes", strtotime($actualTime));
            $endTime = date('H:i', $endTime);
            
            $remainingTickets = $showtime->getTotalTickets() - $showtime->getTicketsSold();
            
            if($quantityTickets <= $remainingTickets && (($actualDate < $showtime->getDate()) || ($actualDate == $showtime->getDate() && $endTime < $showtime->getOpeningTime()))) {
                $idUser = $user->getId();
                $purchaseController = new C_Purchase();
    
                /*Loading discount (-25%) ..*/
                //2 = tuesday, 3 = wednesday
                if((date('w') == 2 OR date('w') == 3) && $quantityTickets >= 2) {
                    $discount = 25;
                    $total = $total * ( (100 - $discount) * 0.01 );
                }
                else
                    $discount = 0;
                    
                include(VIEWS . "head.php");
                include(VIEWS . "nav.php");
                include(VIEWS . "purchase_payment.php");
                include(VIEWS . "footer.php");
            }
            else {
                $message = "The time to buy the tickets has expired or there are not enough tickets available. Try again";
                $messageType = 0;
                $this->index($message, $messageType);
            }
        }
        else if($user->getUserRoleDescription()=="admin") {
            $this->index();
        }
    }

    public function purchase_list($pageno = 0, $filter = "1") {

        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="user") {

            if($pageno == null) {
                if (isset($pageNumber)) {
                    $pageno = $pageNumber;
                } else {
                    $pageno = 1;
                }
            }

            $no_of_records_per_page = 5;
            $offset = ($pageno-1) * $no_of_records_per_page;

            $purchaseController = new C_Purchase();

              
            $date = date('Y-m-d', time());
            
            $total_rows = $purchaseController->loadNumberOfRowsByDate($date, $user);

            $total_pages = ceil($total_rows / $no_of_records_per_page);
            $listOfPurchases = array();
            $listOfPurchases = $purchaseController->loadPurchasesByPageAndDate($offset, $no_of_records_per_page, $date, $user, $filter);

            include(VIEWS . "purchase_list.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {
            include(VIEWS . "home.php");
        }
        include(VIEWS . "footer.php");
    }

    public function qr($id, $pageno, $filter) {
        $userController = new C_User();
        $user = $userController->checkSession();
        include(VIEWS . "head.php");
        include(VIEWS . "nav.php");
        if($user == null) {
            include(VIEWS . "home.php");
        }
        else if ($user->getUserRoleDescription()=="user") {
            $purchaseController = new C_Purchase();

            $purchase = $purchaseController->loadPurchaseById($id);
            include(VIEWS . "qr.php");
        }
        else if ($user->getUserRoleDescription()=="admin") {
            include(VIEWS . "home.php");
        }
        include(VIEWS . "footer.php");
    }
}
?>
