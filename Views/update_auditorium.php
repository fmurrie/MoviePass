<main class="d-flex align-items-center justify-content-center">
    <div class="container mt-5 mb-5">
        <div class="d-flex h-70 mt-4 mb-3" style="width: 700px; margin: 0 auto;">
            <form class="modal-content mt-5" style="background-color: rgba(34, 34, 34, 0.767);" action="<?php echo BASE; ?>Auditorium/updateAuditorium" method="POST">
                <input type="hidden" class="form-control m-1" value="<?= $auditorium->getId(); ?>" name="id"/>
                <input type="hidden" class="form-control m-1" value="<?= $pageno; ?>" name="pageno"/>
                <input type="hidden" class="form-control m-1" value="<?= $pagenoMovieTheater; ?>" name="pagenoMovieTheater"/>
                <input type="hidden" class="form-control m-1" value="<?= $auditorium->getMovieTheater()->getId(); ?>" name="idMovieTheater"/>
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="fas fa-tools mr-2"></i></span>Update auditorium - MovieTheater: <?php echo $movieTheater->getName();?></h5>
                </div>
                <div class="modal-body text-white">
                    
                    <div class="form-row mb-3">     
                        <div class="col">
                            <label>Name</label>
                            <input type="text" class="form-control" value="<?= $auditorium->getName(); ?>" name="name" required/>
                        </div>
                        <div class="col">
                            <label>Capacity</label>
                            <input type="number" class="form-control m" value="<?= $auditorium->getCapacity();?>" name="capacity" min=1 max=3000 required/>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">
                            <label>Ticket price</label>
                            <input type="number" class="form-control" value="<?= $auditorium->getTicketPrice();?>" name="ticketPrice" min=1 required/>
                        </div>
                        <div class="col">
                            <label style="margin-left: 120px;">State</label>
                            <div class="mt-1" style="margin-left: 120px;">
                                <label class="switch">
                                    <input type="checkbox" name="state" value="1">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
            </form>
        </div>
    </div>
</main>