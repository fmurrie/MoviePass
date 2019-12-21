<main class="d-flex align-items-center justify-content-center mb-5">
     <div class="container mb-4" style="background-color: rgba(34, 34, 34, 0.767) !important; margin-top:130px;">
          <div class="modal-header mb-3">
               <h5 class="modal-title text-white"><span><i class="fas fa-tools mr-2"></i></span> Movie theaters management</h5>
               <button type="button" class="btn btn-outline-success" style="border-radius: 20px;" data-toggle="modal" data-target="#create-mt">Add</button>
          </div>
          <!-- Table -->
          <div class="table-responsive mb-1 mt-1">
               <table class="table table-hover table-sm table-dark text-center">
                    <thead class="thead-dark">
                         <tr>
                              <th>State</th>
                              <th>Name</th>
                              <th>Address</th>
                              <th>Opening time</th>
                              <th>Closing time</th>
                              <th>Delete</th>
                              <th>Update</th>
                              <th>Auditoriums</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php foreach($listOfMovieTheaters as $movieTheater) { ?>
                              <tr>
                                   <td>
                                   <?php if($movieTheater->getState() == 1) { ?>
                                   <img src="<?php echo BASE; ?>Views/img/icons/active.png" width="25" height="25">
                                   <?php } else { ?>
                                   <img src="<?php echo BASE; ?>Views/img/icons/no-active.png" width="25" height="25">
                                   <?php } ?>
                                   </td>
                                   <td> <?= $movieTheater->getName(); ?> </td>
                                   <td> <?= $movieTheater->getAddress(); ?> </td>
                                   <td> <?= $movieTheater->getOpeningTime(); ?> </td>
                                   <td> <?= $movieTheater->getClosingTime(); ?> </td>
                                   <td>
                                        <a href="<?php echo BASE; ?>MovieTheater/deleteMovieTheater/<?php echo "?id=" . $movieTheater->getId() . "&pageno=" . $pageno; ?>" class="btn btn-light">
                                             <img src="<?php echo BASE; ?>Views/img/icons/trash-2.svg" width="16" height="16">
                                        </a>
                                   </td>
                                   <td class="text-center justify-content-center">
                                        <a href="<?php echo BASE; ?>Home/update_movietheater/<?php echo "?id=" . $movieTheater->getId() . "&pageno=" . $pageno; ?>" class="btn btn-light">
                                             <img src="<?php echo BASE; ?>Views/img/icons/edit.svg" width="16" height="16">
                                        </a>
                                   </td>
                                   <td class="text-center justify-content-center">
                                        <a href="<?php echo BASE; ?>Home/admin_auditoriums/<?php echo "?idMovieTheater=" . $movieTheater->getId() . "&pageno=1" . "&pagenoMovieTheater=" . $pageno; ?>" class="btn btn-light">
                                             <img src="<?php echo BASE; ?>Views/img/icons/airplay.svg" width="16" height="16">
                                        </a>
                                   </td>
                              </tr>
                         <?php } ?>
                    </tbody>
               </table>
          </div>
          <!-- Paging -->
          <ul class="pagination justify-content-center mb-4">
               <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo BASE."Home/admin_movietheaters?pageno=".($pageno - 1); } ?>" tabindex="-1">Prev</a>
               </li>

               <?php for($i=1; $i<=$total_pages; $i++) {

                    if($pageno != $i) { ?>
                         <li class="page-item"><a class="page-link" href="<?php echo BASE; ?>Home/admin_movietheaters?pageno=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } else { ?>
                    <li class="page-item active">
                         <a class="page-link" href="<?php echo BASE; ?>Home/admin_movietheaters?pageno=<?php echo $i; ?>"><?php echo $i; ?><span class="sr-only">(actual)</span></a>
                    </li>
                    <?php } ?>
               <?php } ?>

               <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo BASE."Home/admin_movietheaters?pageno=".($pageno + 1); } ?>">Next</a>
               </li>
          </ul>
          <!-- Paging  -->
     </div>
</main>

<!-- Add movie theater -->
<div class="modal fade" id="create-mt" tabindex="-1" userRolee="dialog" aria-labelledby="sign-up" aria-hidden="true">
     <div class="modal-dialog" userRolee="document">
            <form class="modal-content mt-5" style="background-color: rgba(34, 34, 34, 0.911);"action="<?php echo BASE; ?>MovieTheater/createMovieTheater" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="fas fa-tools mr-2"></i></span>Add movie theater</h5>
                </div>
                <div class="modal-body text-white">
                    <div class="form-group">
                         <label>Name</label>
                         <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                         <label>Address</label>
                         <input type="text" name="address" class="form-control" required>  
                    </div>
                    <div class="form-group">
                         <label>Opening time</label>   
                         <input type="time" name="openingTime" class="form-control" value="00:00" required>
                    </div>
                    <div class="form-group">
                         <label>Closing time</label>
                         <input type="time" name="closingTime" class="form-control" value="00:00" required>
                    </div>
                    <div class="modal-footer">
                         <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                    <input type="hidden" name="pageno" value=<?php echo $pageno; ?>>
               </div>
            </form>
     </div>
</div>
