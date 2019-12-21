<?php
namespace Controllers;

/*Alias - Models*/
use Models\Purchase as M_Purchase;
use Models\Ticket as M_Ticket;
use Models\Payment as M_Payment;

/*Alias - DAO*/
use DAO\PurchaseDAO as DAO_Purchase;

/*Alias - Controllers*/
use Controllers\UserController as C_User;
use Controllers\ShowtimeController as C_Showtime;
use Controllers\HomeController as C_Home;

/*Alias - Exceptions*/
use \PDOException as PDOException;

/*Alias - QR*/
use phpqrcode\QRcode as QRcode;

/*PHP Mailer*/
use Mailer\PHPMailer as PHPMailer;
use Mailer\Exception as MailerException;

class PurchaseController {
    
    private $purchaseDAO;
    private $homeController;

	function __construct() {
        $this->purchaseDAO = new DAO_Purchase();
        $this->homeController = new C_Home();
    }

    function createPurchase($totalTickets = null, $total = null, $idUser = null, $idShowtime = null, $discount = null) {

        //For Mercado Pago API
        if($_POST) {
            $totalTickets = $_POST["quantity_tickets"];
            $total = $_POST["total"];
            $idUser = $_POST["id_user"];
            $idShowtime = $_POST["id_showtime"];
            $discount = $_POST["discount"];
        }

        if($totalTickets != null && $total != null && $idUser != null && $idShowtime != null && $discount != null) {
            try
            {
                //We bring the corresponding User object from the BD
                $userController = new C_User();
                $user = $userController->retrieveUser($idUser);

                //We bring the corresponding Showtime object from the BD
                $showtimeController = new C_Showtime();
                $showtime = $showtimeController->loadShowtimeById($idShowtime);

                //Checking that there are enough tickets available ..
                $remainingTickets = $showtime->getTotalTickets() - $showtime->getTicketsSold();
                if($totalTickets <= $remainingTickets) {
                    //We create and load the Payment object with info
                    $payment = new M_Payment();
                    $payment->setTotal($total);
                    //payment->setIdPurchase ---> not having the purchase ID yet

                    //We create and load Ticket objects
                    $arrayOfTickets = array();
                    $counter = 1;
                    for($i=0; $i<$totalTickets; $i++) {
                        $number = $showtime->getTicketsSold() + $counter;
                        $ticket = new M_Ticket();
                        $ticket->setNumber($number);
                        $ticket->setShowtime($showtime);
                        array_push($arrayOfTickets, $ticket);
                        $counter++;
                    }
                
                    //We create and load the Purchase object
                    $purchase = new M_Purchase();
                    $purchase->setTickets($arrayOfTickets);
                    $date = date('Y-m-d', time());
                    $purchase->setDate($date);
                    $purchase->setDiscount($discount);
                    $purchase->setTotalTickets($totalTickets);
                    $purchase->setUser($user);
                    $purchase->setPayment($payment);

                    $idPurchase = $this->purchaseDAO->create($purchase);

                    //We generate and save the QR in the BD
                    $qr = $this->generateRandomQr($idPurchase);
                    $this->purchaseDAO->loadQr($qr, $idPurchase);

                    //Finally, we update the purchased tickets in the function
                    $updatedAmountOfTickets = $showtime->getTicketsSold()+$totalTickets;
                    if($showtimeController->updateTicketsSold($showtime->getId(), $updatedAmountOfTickets)) {
                        $this->mailTicket($purchase->getUser()->getEmail(), $purchase->getUser()->getFirstName(), $purchase->getUser()->getLastName(), $qr, $showtime->getMovie()->getName(), $showtime->getDate(), $showtime->getOpeningTime(), $showtime->getAuditorium()->getMovieTheater()->getName(), $showtime->getAuditorium()->getName());
                        $message = "Purchase made successfully. Check in the section My purchases or in your email the QR code required to print the tickets in the movie theater";
                        $this->homeController->index($message, 1);
                    }
                    else {
                        $message = "A database error ocurred";
                        $this->homeController->index($message, 0);
                    }
                }
                else {
                    $message = "The purchase could not be completed. There are not enough tickets available. Try again";
                    $messageType = 0;
                    $this->homeController->index($message, $messageType);
                }
            }
            catch(PDOException $e)
            {
                $message = "A database error ocurred";
                $this->homeController->index($message, 0);
            }
        }
        else {
            $this->homeController->index();
        }
    }

    public function mailTicket($email, $firstName, $lastName, $image, $nameMovie, $dateMovie, $timeMovie, $movieTheater, $auditorium) {
        
        $value = false;
        $Mail = new PHPMailer(true);
        $body = "Thanks " . $firstName . " " . $lastName . " for choosing MoviePass. We sent you the QR code required to print the tickets in the movie theater. <br/>
        Enjoy the movie! <br/> <br/>" .
        $nameMovie . " on " . $dateMovie . " - " . $timeMovie . " at " . $movieTheater . " - Auditorium: " . $auditorium .
        "<br/> <img src='cid:qrcode'/>";

        try
        {
            //Server settings
            $Mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            //Server 
            $Mail->SMTPDebug = 0;
            $Mail->IsSMTP(); //Use SMTP
            $Mail->Host        = "smtp.gmail.com";
            $Mail->SMTPAuth    = true; //Enable SMTP authentication
            //Server will access from this
            $Mail->Username    = '@youraccount@gmail.com'; //SMTP account username (add)
            $Mail->Password    = ""; //SMTP account password (add)
            $Mail->SMTPSecure  = "tls"; //Secure conection
            $Mail->Port        = 587; //Set the SMTP port
            
            //And send from us to the recipient
            $Mail->setFrom('youraccount@gmail.com', 'MoviePass'); //Account username (add)
            $Mail->addAddress($email); //Recipient of the email

            //Image (QR)
            $Mail->addStringEmbeddedImage($image, 'qrcode', 'qrcode.jpg');
            //The content of the email we are sending.
            $Mail->isHTML(true);
            $Mail->Subject = 'Tickets from MoviePass';
            $Mail->Body=$body;
            $Mail->AltBody=$body;
            $Mail->send();
            $value = true;
        } 
        catch (MailerException $e)
        {
            $value = $e;
        }
        return $value;
    }

    /*From the date (actual date) brought by parameter onwards*/
    public function loadPurchasesByPageAndDate($offset, $no_of_records_per_page, $date, $user, $filter) {

        $listOfPurchases = array();
        try
        {
            $listOfPurchases = $this->purchaseDAO->retrieveByPageAndDateNoTickets($offset, $no_of_records_per_page, $date, $user, $filter);
        }
        catch(PDOException $e)
        {
            $listOfPurchases = null;
        }
        return $listOfPurchases;
    }

    public function loadNumberOfRowsByDate($date, $user) {

        $numberOfRows = 0;
        try
        {
            $numberOfRows = $this->purchaseDAO->retrieveNumberOfRowsByDate($date, $user);
        }
        catch(PDOException $e)
        {
            $numberOfRows = null;
        }
        return $numberOfRows;
    }

    public function generateRandomQr($id){

        $filePath = ROOT . $id . ".png";
        $content = "Purchase code: MP" . $id;
		$size = 10;
		$level = 'L';
        $framSize = 3;
        
        QRcode::png($content, $filePath, $level, $size, $framSize);
        $qrImage = file_get_contents($filePath);
        unlink($filePath);

        return $qrImage;
    }
    
    public function loadPurchaseById($id) {

        try
        {
            $purchase = $this->purchaseDAO->retrieveOne($id);
        }
        catch(PDOException $e)
        {
            $purchase = null;
        }
        return $purchase;
    }

    /*$selection = "movie" o "movieTheater"*/
    public function loadTotalSalesAmount($date, $selection, $id) {

        $totalSalesAmount = 0;
        try
        {
            $totalSalesAmount = $this->purchaseDAO->loadTotal($date, $selection, $id);
        }
        catch(PDOException $e)
        {
            $totalSalesAmount = null;
        }
        return $totalSalesAmount;
    }
}
?>
