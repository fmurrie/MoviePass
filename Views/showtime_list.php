<main class="d-flex align-items-center justify-content-center mb-5">
     <div class="container mb-4" style="background-color: rgba(34, 34, 34, 0.767) !important; margin-top:130px;">
          <div class="modal-header mb-3">
               <h5 class="modal-title text-white"><span><i class="fas fa-tools mr-2"></i></span> Active showtimes by date</h5>
          </div>
          <!-- Tabla -->
          <div class="table-responsive mb-1 mt-1">
               <table class="table table-hover table-sm table-dark text-center">
                    <thead class="thead-dark">
                         <tr>
                              <th>Date (YYYY/MM/DD)</th>
                              <th>Opening time</th>
                              <th>Closing time</th>
                              <th>Tickets sold</th>
                              <th>Remaining tickets</th>
                              <th>Movie theater</th>
                              <th>Movie</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php foreach($listOfShowtimes as $showtime) { ?>
                              <tr>
                                   <td> <?= $showtime->getDate(); ?> </td>
                                   <td> <?= $showtime->getOpeningTime(); ?> </td>
                                   <td> <?= $showtime->getClosingTime(); ?> </td>
                                   <td> <?= $showtime->getTicketsSold(); ?> </td>
                                   <td> <?= $showtime->getTotalTickets()-$showtime->getTicketsSold(); ?> </td>
                                   <td> <?= $showtime->getAuditorium()->getMovieTheater()->getName(); ?> - Auditorium: <?= $showtime->getAuditorium()->getName(); ?> </td>
                                   <td> <?= $showtime->getMovie()->getName(); ?> </td>
                              </tr>
                         <?php } ?>
                    </tbody>
               </table>
          </div>
          <!-- Paging -->
          <ul class="pagination justify-content-center mb-4">
               <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo BASE."Home/showtime_list?pageno=".($pageno - 1); } ?>" tabindex="-1">Prev</a>
               </li>

               <?php for($i=1; $i<=$total_pages; $i++) {

                    if($pageno != $i) { ?>
                         <li class="page-item"><a class="page-link" href="<?php echo BASE; ?>Home/showtime_list?pageno=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } else { ?>
                    <li class="page-item active">
                         <a class="page-link" href="<?php echo BASE; ?>Home/showtime_list?pageno=<?php echo $i; ?>"><?php echo $i; ?><span class="sr-only">(actual)</span></a>
                    </li>
                    <?php } ?>
               <?php } ?>

               <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo BASE."Home/showtime_list?pageno=".($pageno + 1); } ?>">Next</a>
               </li>
          </ul>
          <!-- Paging -->
     </div>
</main>