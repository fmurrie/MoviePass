<main class="d-flex align-items-center justify-content-center">
	<div class="container mt-5">
		<div class="d-flex justify-content-center mt-5">
			<div class="card mt-5 peli-contenedor" style="height: 240px; width: 100%;">
				<div class="card-header">
					<div class="section-heading-2 text-center wow fadeInUp" style="margin: 0;">
                        <p>Now playing</p>
                        <h4>Choose your movie</h4>
                    </div>
                    <div class="d-flex justify-content-end social_icon">
                        <span><i class="fas fa-film"></i></span>
				    </div>
				</div>
				<div class="card-body">
					<div class="container">
						<div class="row align-items-center justify-content-center text-center">
							<div class="col-md-10">
								<div class="form-search-wrap p-2" data-aos="fade-up" data-aos-delay="200">
									<form method="post" action="<?php echo BASE; ?>Home/now_playing">
										<div class="row align-items-center">
											<div class="col-lg-12 col-xl-4 no-sm-border texto-movies">
												<input type="text" style="background-color: white; text-align: center;" class="form-control" placeholder="What are you looking for?" disabled>
											</div>
											<div class="col-lg-12 col-xl-3 no-sm-border texto-movies">
												<div class="select-wrap">
													<span class="icon mt-1"><span class="icon-keyboard_arrow_down"></span></span>
													<select class="form-control" name="date">
														<option value="today" <?php if($choosenDate == "today") { ?> selected="selected" <?php } ?>>Today</option>
														<?php foreach($arrayOfDates as $date) { ?>
															<option value="<?php echo $date; ?>" <?php if($choosenDate == $date) { ?> selected="selected" <?php } ?>> <?php echo date("M jS, Y",strtotime($date)); ?> </option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-lg-12 col-xl-3">
												<div class="select-wrap">
													<span class="icon mt-1"><span class="icon-keyboard_arrow_down"></span></span>
													<select class="form-control" name="genre">
														<option value="total">All genres</option>
														<?php foreach($arrayOfGenres as $genre) { ?>
																<option value="<?php echo $genre->getIdBd(); ?>" <?php if($genre->getIdBd() == $choosenGenre) { ?> selected="selected" <?php } ?>> <?php echo $genre->getName();?> </option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-lg-12 col-xl-2 ml-auto text-right">
												<input type="submit" class="btn btn-primary" value="Search">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</div>
</main>

<main class="d-flex align-items-center justify-content-center mb-5">
	<div class="container-Pelicula mb-5">
		<?php if($arrayOfChoosenMovies == null) { echo "<a class='btn btn-primary text-white mt-5' style='margin: 0 auto;'>No showtimes available</a>"; }?>
		<?php foreach($arrayOfChoosenMovies as $value) { ?>
		<div class="movie-card">
			<a href="<?php echo BASE; ?>Home/movie_description/?id=<?php echo $value->getIdBd() . "&date=" . $choosenDate;?>">
				<div class="movie-header" style="background-size: cover; background-image: url(<?php echo $value->getPoster(); ?>);">
				</div>
				<div class="movie-content">
					<div class="movie-content-header">
							<h3 class="movie-title"><?= $value->getName(); ?></h3>
						<div class="imax-logo"></div>
					</div>
				</div>
			</a>
		</div>
		<?php } ?>
	</div>
</main>
