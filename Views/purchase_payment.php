<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo BASE; ?>Views/css/ticketStyle.css"/>
<link rel="stylesheet" href="<?php echo BASE; ?>Views/style.css">
<div id="booking" class="section">
<div class="section-center mt-3">
<div class="container align-items-center justify-content-center" style="margin: 0 auto; margin-bottom: 25px;">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">
						<div class="row">
							<div class="col-xs-10">
								<h1><span class="glyphicon glyphicon-shopping-cart"></span> Finalize your purchase</h1>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-1 mb-3"><img style="background-size: cover" class="img-purchase" src="<?php echo $showtime->getMovie()->getPoster();?>">
						</div>
						<div class="col-xs-6">
							<h4 class="product-name"><strong><?php echo $showtime->getMovie()->getName();?></strong></h4><h4><small>Movie theater: <?php echo $showtime->getAuditorium()->getMovieTheater()->getName(); ?> | Date: <?php echo date("M jS, Y",strtotime($date));?> - <?php echo $showtime->getOpeningTime();?> | <?php if($discount == 0) { echo "No discount"; } else { echo "With 25% discount (on Tuesday and Wednesday, buying at least 2 tickets) 
                            "; } ?> </small></h4>
						</div>
						<div class="col-xs-4">
							<div class="col-xs-12 text-right">
								<h1><?php echo $quantityTickets;?> <span class="text-muted">x </span>  $<?php echo $showtime->getTicketPrice();?></h1>
							</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row text-center">
						<div class="col-xs-2 mb-5">
							<form action="<?php echo BASE; ?>Home/now_playing" id="timeLeft" method="POST">
								<br>
								<h3>Time left</h3>
								<h3 align="center" id="time">2:30</h3>
								<input type="hidden" name="choosenDate" value="today">
								<input type="hidden" name="choosenGenre" value="total">
								<input type="text" name="timeOut" value="fail" style="display:none">
							</form>
						</div>
								
						<div class="col-xs-9 float-right text-right">
							<h2 style="margin: 10px;">Total:  $ <strong><?php echo $total;?></strong></h2>
                            <form action="<?php echo BASE; ?>Purchase/createPurchase" method="POST">

                                <input type="hidden" value="<?php echo $quantityTickets;?>" name="quantity_tickets">
								<input type="hidden" value="<?php echo $total;?>" name="total">
								<input type="hidden" value="<?php echo $idUser;?>" name="id_user">
								<input type="hidden" value="<?php echo $idShowtime;?>" name="id_showtime">
								<input type="hidden" value="<?php echo $discount;?>" name="discount">

								<!-- data-public-key = API KEY for Mercado Pago -->
								<script
									src="https://www.mercadopago.com.ar/integrations/v1/web-tokenize-checkout.js"
									data-public-key="TEST-08acee35-e67b-4ab8-91bc-c53eed0a6cf0"
									data-transaction-amount="<?php echo $total; ?>"
									data-header-color="#8e44ad"
									data-elements-color="#8e44ad"
									data-button-label="Finalize and pay"
                                >
								</script>
                            </form>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>
</div>
</div>

<script>
var exit = document.getElementById("time"),
    minutes = 2,
    seconds = 30,
    interval = setInterval(function(){
        if (--seconds < 0){
            seconds = 59;
            minutes--;
        }
      
        if (!minutes && !seconds)
            clearInterval(interval);
  
        exit.innerHTML = minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);

        if (!minutes && !seconds){
            clearInterval(interval);
            document.getElementById("timeLeft").submit();
        }

    }, 1000);
</script>