<link type="text/css" rel="stylesheet" href="<?php echo BASE; ?>Views/css/ticketStyle.css"/>
<link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
<div id="booking" class="section">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="booking-bg" style="background-image: url(<?php echo $showtime->getMovie()->getPoster(); ?>);"></div>
                    <form action="<?php echo BASE; ?>Home/purchase_payment" method="POST">
                        <div class="form-header">
                            <h2>Buy movie tickets</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Tickets:</span>
                                    <input type="hidden" value="<?php echo $showtime->getId(); ?>" name="id_showtime">
                                    <input type="hidden" value="<?php echo $date; ?>" name="date">
                                    <select class="form-control" id="quantityfield" name="quantity_tickets" style="height: 40px;">
                                        <?php for($i=1; $i<=$freeTickets; $i++) { ?>
                                            <option><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="select-arrow"></span>
                                </div>
                             </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Subtotal ($):</span>
                                    <input class="form-control" style="height: 40px;" name="total" type="text" id="totalfield" value="<?php echo $showtime->getTicketPrice();?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-btn">
                            <button class="float-right submit-btn">Confirm</button>
                            <a href="<?php echo BASE; ?>Home/purchase_showtime/?id=<?php echo $idMovie . "&date=" . $date;?>" class="float-left submit-btn">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery for subtotal -->
<script type="text/javascript">
    $("#quantityfield").change(function() {
        var value = parseFloat(<?php echo $showtime->getTicketPrice(); ?>);
        var quantity = parseInt($("#quantityfield").val());
        var total = value * quantity;
        $("#totalfield").val(total.toString());
    });
</script>