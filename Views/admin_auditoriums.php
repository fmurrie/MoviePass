<main class="d-flex align-items-center justify-content-center mb-5">
     <div class="container mb-4" style="background-color: rgba(34, 34, 34, 0.767) !important; margin-top:130px;">
          <div class="modal-header mb-3">
               <h5 class="modal-title text-white"><span><i class="fas fa-tools mr-2"></i></span> Auditoriums management - Movie theater: <?php echo $movieTheater->getName();?></h5>
               <button type="button" class="btn btn-outline-success" style="border-radius: 20px;" data-toggle="modal" data-target="#create-auditorium">Add</button>
          </div>
          <!-- Table -->
          <div class="table-responsive mb-1 mt-1">
               <table class="table table-hover table-sm table-dark text-center">
                    <thead class="thead-dark">
                         <tr>
                              <th>State</th>
                              <th>Name</th>
                              <th>Capacity</th>
                              <th>Ticket price</th>
                              <th>Delete</th>
                              <th>Update</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php foreach($auditoriumList as $auditorium) { ?>
                              <tr>
                                   <td>
                                   <?php if($auditorium->getState() == 1) { ?>
                                   <img src="<?php echo BASE; ?>Views/img/icons/active.png" width="25" height="25">
                                   <?php } else { ?>
                                   <img src="<?php echo BASE; ?>Views/img/icons/no-active.png" width="25" height="25">
                                   <?php } ?>
                                   </td>
                                   <td> <?= $auditorium->getName(); ?> </td>
                                   <td> <?= $auditorium->getCapacity(); ?> </td>
                                   <td> <?= $auditorium->getTicketPrice(); ?> </td>
                                   <td>
                                        <a href="<?php echo BASE; ?>Auditorium/deleteAuditorium/<?php echo '?id=' . $auditorium->getId() . '&pageno=' . $pageno . '&pagenoMovieTheater=' . $pagenoMovieTheater . '&idMovieTheater=' . $movieTheater->getId(); ?>" class="btn btn-light">
                                             <img src="<?php echo BASE; ?>Views/img/icons/trash-2.svg" width="16" height="16">
                                        </a>
                                   </td>
                                   <td class="text-center justify-content-center">
                                        <a href="<?php echo BASE; ?>Home/update_auditorium/<?php echo "?id=" . $auditorium->getId() . "&pageno=" . $pageno . "&pagenoMovieTheater=" . $pagenoMovieTheater?>" class="btn btn-light">
                                             <img src="<?php echo BASE; ?>Views/img/icons/edit.svg" width="16" height="16">
                                        </a>
                                   </td>
                              </tr>
                         <?php } ?>
                    </tbody>
               </table>
          </div>
          <!-- -->
          <!-- Paging -->
          <ul class="pagination justify-content-center mb-4">
               <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo BASE."Home/admin_auditoriums?idMovieTheater=" . $movieTheater->getId() . "&pageno=" . ($pageno - 1) . "&pagenoMovieTheater=" . $pagenoMovieTheater; } ?>" tabindex="-1">Prev</a>
               </li>

               <?php for($i=1; $i<=$total_pages; $i++) {

                    if($pageno != $i) { ?>
                         <li class="page-item"><a class="page-link" href="<?php echo BASE; ?>Home/admin_auditoriums?idMovieTheater=<?php echo $movieTheater->getId() . "&pageno=" . $i . "&pagenoMovieTheater=" . $pagenoMovieTheater; ?>"><?php echo $i; ?></a></li>
                    <?php } else { ?>
                    <li class="page-item active">
                         <a class="page-link" href="<?php echo BASE; ?>Home/admin_auditoriums?idMovieTheater=<?php echo $movieTheater->getId() . "&pageno=" . $i . "&pagenoMovieTheater=" . $pagenoMovieTheater; ?>"><?php echo $i; ?><span class="sr-only">(actual)</span></a>
                    </li>
                    <?php } ?>
               <?php } ?>

               <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo BASE."Home/admin_auditoriums?idMovieTheater=" . $movieTheater->getId() . "&pageno=". ($pageno + 1) . "&pagenoMovieTheater=" . $pagenoMovieTheater; } ?>">Next</a>
               </li>
          </ul>
          <!-- -->
          <div class="justify-content-center text-center mb-4">
               <a type="button" class="btn btn-outline-secondary mr-2 text-white" style="border-radius: 20px;" href="<?php echo BASE; ?>Home/admin_movietheaters?pageno=<?php echo $pagenoMovieTheater; ?>">Back</a>
          </div>
     </div>
</main>

<!-- Add auditorium -->
<div class="modal fade" id="create-auditorium" tabindex="-1" userRolee="dialog" aria-labelledby="sign-up" aria-hidden="true">
     <div class="modal-dialog" userRolee="document">
            <form class="modal-content mt-5" style="background-color: rgba(34, 34, 34, 0.911);" action="<?php echo BASE; ?>Auditorium/createAuditorium" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="fas fa-tools mr-2"></i></span>Add auditorium</h5>
                </div>
                <div class="modal-body text-white">
                    <div class="form-group">
                         <label>Name</label>
                         <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                         <label>Capacity</label>
                         <input type="number" name="capacity" class="form-control" min=1 max=3000 required>  
                    </div>
                    <div class="form-group">
                         <label>Ticket price</label>
                         <input type="number" name="ticketPrice" class="form-control" min=1 required>
                         <input type="hidden" name="pageno" class="form-control" value="<?php echo $pageno; ?>" required>
                         <input type="hidden" name="pagenoMovieTheater" class="form-control" value="<?php echo $pagenoMovieTheater ?>" required>
                         <input type="hidden" name="idMovieTheater" class="form-control" value="<?php echo $movieTheater->getId(); ?>" required>
                    </div>
                    <div class="modal-footer">
                         <button type="submit" class="btn btn-primary">Add</button>
                    </div>
               </div>
            </form>
     </div>
</div>