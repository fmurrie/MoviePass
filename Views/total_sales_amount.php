<main class="d-flex align-items-center justify-content-center">
    <div class="container mt-4 mb-5">
        <div class="d-flex h-70 mt-5 mb-3" style="width: 400px; margin: 0 auto;">
            <form class="modal-content mt-5" style="background-color: rgba(34, 34, 34, 0.767);" method="POST" action="<?php echo BASE; ?>Home/total_sales_amount">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="fas fa-tools mr-2"></i></span>Sales amount</h5>
            </div>
            <div class="modal-body text-white text-center align-items-center justify-content-center">
                <div class="form-row mb-3 text-center align-items-center justify-content-center">
                    <div class="col">

                        <a href="<?php echo BASE; ?>Home/total_sales_amount/?id=null&option=movie" class="btn btn-danger btn-sm <?php if ($option=='movie') { ?> disabled <?php } ?> }" style="border-radius: 10px;">Movie</a>
                        <a href="<?php echo BASE; ?>Home/total_sales_amount/?id=null&option=movieTheater" class="btn btn-danger btn-sm <?php if ($option=='movieTheater') { ?> disabled <?php } ?> }" style="border-radius: 10px;">Movie theater</a>

                    </div>
                </div>
                <div class="form-row mb-3 text-center align-items-center justify-content-center">
                    <div class="col">
                        <select class="form-control" id="name" name="id" required>
                            <?php if($option == "movie") { 
                            foreach($listOfMovies as $movie) { ?>
                                <option value="<?= $movie->getIdBd()?>"><?= $movie->getName() ?> </option>
                            <?php } ?>
                            <input type="hidden" value="movie" name="option">
                            <?php } else if($option == "movieTheater") { 
                            foreach($listOfMovieTheaters as $movieTheater) { ?>
                                <option value="<?= $movieTheater->getId()?>"><?= $movieTheater->getName() ?> </option>
                            <?php } ?>
                            </select>
                            <input type="hidden" value="movieTheater" name="option">
                            <?php } ?>
                    </div>
                </div> 
                <div class="form-row mb-4 text-center align-items-center justify-content-center">
                    <div class="col">
                        <label>Initial date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                </div>
                <div class="form-row mb-3 text-center align-items-center justify-content-center">
                    <div class="col">
                        <button type="submit" style="border-radius: 50px;" class="form-control ml-1 btn btn-success text-center">Load</button> 
                    </div>
                </div>
                </form>
                <div class="form-row mb-3 text-center align-items-center justify-content-center"> 
                    <div class="col text-center">
                        <label>Total amount ($)</label>
                        <input type="text" value="<?php echo $total;?>" name="total" class="form-control text-center" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
