<main class="d-flex align-items-center justify-content-center mb-5">
     <div class="container mb-4" style="background-color: rgba(34, 34, 34, 0.767) !important; margin-top:130px;">
          <div class="modal-header mb-3">
               <h5 class="modal-title text-white"><span><i class="fas fa-money-check-alt mr-2"></i></span> My purchases</h5>
          </div>
          <!-- Table -->
          <div class="table-responsive mb-1 mt-1">
               <table class="table table-hover table-sm table-secondary text-center">
                    <thead class="thead-dark">
                         <tr>
                              <th>Purchase date <a href="<?php echo BASE; ?>Home/purchase_list?pageno=<?php echo $pageno . "&filter=1"?>"><i class="fas fa-sort-alpha-down ml-1" style="color: #d69212;;"></i></a></th>
                              <th>Number of tickets</th>
                              <th>Seats</th>
                              <th>Total price</th>
                              <th>Movie theater</th>
                              <th>Movie <a href="<?php echo BASE; ?>Home/purchase_list?pageno=<?php echo $pageno . "&filter=0"?>"><i class="fas fa-sort-amount-down-alt ml-1" style="color: #d69212;;"></i></a></th>
                              <th>Showtime date</th>
                              <th>QR code</th>
                         </tr>
                    </thead>
                    <tbody>
                         <form method="POST" action="<?php echo BASE; ?>Home/qr">
                         <?php if(isset($listOfPurchases)) { ?>
                         <?php foreach($listOfPurchases as $purchase) { ?>
                              <tr>
                                   <td> <?= $purchase->getDate(); ?> </td>
                                   <td> <?= $purchase->getTotalTickets(); ?> </td>
                                   <td> <?php 
                                   $number = $purchase->getTickets()[0]->getNumber();
                                   $seats = $number;
                                   $u = 1;
                                   for($i=1;$i<$purchase->getTotalTickets();$i++) {
                                        $number = $number + $u;
                                        $seats = $seats . ", " . $number;
                                   }
                                   echo $seats;
                                   ?> </td>
                                   <td> <?= $purchase->getPayment()->getTotal(); ?> </td>
                                   <td> <?= $purchase->getTickets()[0]->getShowtime()->getAuditorium()->getMovieTheater()->getName(); ?> - Auditorium: <?= $purchase->getTickets()[0]->getShowtime()->getAuditorium()->getName(); ?> </td>
                                   <td> <?= $purchase->getTickets()[0]->getShowtime()->getMovie()->getName();?> </td>
                                   <td> <?= $purchase->getTickets()[0]->getShowtime()->getDate() . " - " . $purchase->getTickets()[0]->getShowtime()->getOpeningTime(); ?> </td>
                                   <td><a href="<?php echo BASE; ?>Home/qr/<?php echo "?id=" . $purchase->getId() . "&pageno=" . $pageno . "&filter=" . $filter; ?>" class="btn btn-primary">Ver</a> </td>
                              </tr>
                         <?php } } ?>
                         </form>
                    </tbody>
               </table>
          </div>
          <!-- Paging -->
          <ul class="pagination justify-content-center mb-4">
               <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo BASE."Home/purchase_list?pageno=" . ($pageno - 1) . "&filter=" . $filter; } ?>" tabindex="-1">Prev</a>
               </li>
               <?php if(isset($total_pages)) { ?>
               <?php for($i=1; $i<=$total_pages; $i++) {

                    if($pageno != $i) { ?>
                         <li class="page-item"><a class="page-link" href="<?php echo BASE; ?>Home/purchase_list?pageno=<?php echo $i . "&filter=" . $filter; ?>"><?php echo $i; ?></a></li>
                    <?php } else { ?>
                    <li class="page-item active">
                         <a class="page-link" href="<?php echo BASE; ?>Home/purchase_list?pageno=<?php echo $i . "&filter=" . $filter; ?>"><?php echo $i;?><span class="sr-only">(actual)</span></a>
                    </li>
                    <?php } ?>
               <?php } } ?>

               <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo BASE."Home/purchase_list?pageno=".($pageno + 1) . "&filter=" . $filter; } ?>">Next</a>
               </li>
          </ul>
          <!-- Paging -->
     </div>
</main>