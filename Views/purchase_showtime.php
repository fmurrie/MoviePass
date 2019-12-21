<link type="text/css" rel="stylesheet" href="<?php echo BASE; ?>Views/css/ticketStyle.css"/>
<link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="booking-bg" style="background-image: url(<?php echo $showtimesToPurchase[0]->getMovie()->getPoster(); ?>);"></div>
                    <form action="<?php echo BASE; ?>Home/purchase_tickets" method="post">
                        <div class="form-header">
                            <h2>Buy movie tickets</h2>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <span class="form-label">Showtimes - <?php echo date("M jS, Y",strtotime($showtimesToPurchase[0]->getDate())) . ":";?></span>
                                    <select class="form-control" style="height: 40px;" name="id_showtime" required>
                                        <?php foreach($showtimesToPurchase as $showtime) { ?>
                                        <option value="<?php echo $showtime->getId(); ?>"><?php echo $showtime->getAuditorium()->getMovieTheater()->getName() . " - " . $showtime->getOpeningTime();?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="select-arrow"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-btn">
                            <button class="submit-btn float-right ml-2 mb-4" style="width: 100%;">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>