<main class="d-flex align-items-center justify-content-center">
    <div class="container mt-5 mb-5">
        <div class="d-flex h-70 mt-4 mb-3" style="width: 700px; margin: 0 auto;">
            <form class="modal-content mt-5" style="background-color: rgba(34, 34, 34, 0.767);" action="<?php echo BASE; ?>Showtime/createShowtime" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title text-white"><i class="fas fa-tools mr-2"></i></span>Add showtime</h5>
                </div>
                <div class="modal-body text-white">
                    <div class="form-row mb-3">     
                        <div class="col">
                            <label>Movie theater</label>
                            <select class="form-control" id="movieTheaters" name="movieTheater_choosen" required>
                                <option value="" selected disabled>Please select</option>
                                <?php foreach($listOfMovieTheaters as $movieTheater) { ?>
								    <option value="<?= $movieTheater->getId(); ?>"><?= $movieTheater->getName(); ?></option>
                                <?php } ?>
							</select>
                        </div>
                        <div class="col">
                            <label>Auditorium</label>
                            <select class="form-control" id="auditoriums" name="auditorium_choosen" required>
							</select>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">
                            <label>Movie</label>
                            <select class="form-control" name="movie_choosen" required>
                                <?php foreach($arrayOfMovies as $movie) { ?>
								    <option value="<?= $movie->getIdBd(); ?>"><?= $movie->getName(); ?></option>
                                <?php } ?>
							</select>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">
                            <label>Date</label>
                            <input type="date" name="date_choosen" class="form-control" required>
                        </div>
                        <div class="col" style="margin-left: 25px;">
                            <label>Time</label>
                            <input type="time" name="time_choosen" class="form-control" required>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- jQuery -->
<script>

$("#movieTheaters").change(function() {
  console.log("AJAX call");
  var id = ($("#movieTheaters").val()).toString();
  $.ajax({
    url: "<?php echo BASE;?>Auditorium/loadAuditoriumsInJson",
    type: "POST",
    dataType: "json",
    data: {"id":id}, 
    success: function(response) {
        console.log(response);
        var array = JSON.parse(response);

        var $auditoriums = $('#auditoriums');
        $auditoriums.empty();
        for (var i = 0; i < array.length; i++) {
            $auditoriums.append('<option value=' + array[i].id + '>' + array[i].name + '</option>');
        }
        $auditoriums.change();
    },
    error: function (jqXHR, exception) {
        if (jqXHR.status === 0) {
            console.log('Not connect.\n Verify Network.');
        } else if (exception === 'parsererror') {
            console.log('Requested JSON parse failed.');
        } else if (exception === 'timeout') {
            console.log('Time out error.');
        } else if (exception === 'abort') {
            console.log('Ajax request aborted.');
        } else {
            console.log('Uncaught Error.\n' + jqXHR.responseText);
        }
    }
  });
});

</script>