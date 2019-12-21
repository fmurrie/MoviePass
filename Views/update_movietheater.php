<main class="d-flex align-items-center justify-content-center">
    <div class="container mt-5 mb-5">
        <div class="d-flex h-70 mt-4 mb-3" style="width: 700px; margin: 0 auto;">
            <form class="modal-content mt-5" style="background-color: rgba(34, 34, 34, 0.767);" action="<?php echo BASE; ?>MovieTheater/updateMovieTheater" method="POST">
                <input type="hidden" class="form-control m-1" value="<?= $movieTheater->getId(); ?>" name="id"/>
                <input type="hidden" class="form-control m-1" value="<?= $pageno; ?>" name="pageno"/>
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="fas fa-tools mr-2"></i></span>Update movie theater</h5>
                </div>
                <div class="modal-body text-white">
                    
                    <div class="form-row mb-3">     
                        <div class="col">
                            <label>Name</label>
                            <input type="text" class="form-control" value="<?= $movieTheater->getName(); ?>" name="name" required/>
                        </div>
                        <div class="col" style="margin-left: 25px;">
                            <label>Address</label>
                            <input type="text" class="form-control" value="<?= $movieTheater->getAddress();?>" name="address" required/>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">      
                            <label>Opening time</label>           
                            <input type="time" name="openingTime" class="form-control" value="<?= $movieTheater->getOpeningTime();?>" required>
                        </div>
                        <div class="col" style="margin-left: 20px;">
                            <label>Closing time</label>
                            <input type="time" name="closingTime" class="form-control" value="<?= $movieTheater->getClosingTime();?>" required>
                        </div>
                        <div class="col">
                            <label class="ml-5">State</label>
                            <div class="mt-1 ml-5">
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