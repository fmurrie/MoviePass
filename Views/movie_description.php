<section class="welcome-area">
    <div class="welcome-slides owl-carousel">
        <!-- Datos -->
        <div class="single-welcome-slide bg-overlay" style="background-size: cover; background-image: url(<?php echo $photosToShow[1]; ?>);">
            <div class="container h-100 img-responsive">
                <div class="row h-100 align-items-center justify-content-center">
                    <!-- Title -->
                    <div class="welcome-text-two text-center mt-5">
                        <!-- Name -->
                        <h2 data-animation="fadeInUp" data-delay="100ms"><?php echo $movie->getName();?></h2>
                        <!-- Synopsis -->
                        <div class="event-meta mt-5 text-background" data-animation="fadeInUp" data-delay="500ms">
                            <a class="event-date sinopsis"><i class="zmdi zmdi-info-outline"></i><?php echo $movie->getSynopsis();?></a>
                        </div>
                        <!-- Genre and score -->
                        <div class="event-meta mt-5 text-background" data-animation="fadeInUp" data-delay="500ms">
                            <a class="event-date sinopsis"><i class="zmdi zmdi-tv-list"></i><?php echo " " . $movie->getNameGenres();?></a>
                            <a class="event-date sinopsis"><i class="zmdi zmdi-star"></i><?php echo " " . $movie->getScore() . " / 10";?></a>
                        </div>
                        <!-- Buttons -->
                        <div class="hero-btn-group text-background" data-animation="fadeInUp" data-delay="700ms">
                            <a href="<?php echo BASE; ?>Home/purchase_showtime/?id=<?php echo $idMovie . "&date=" . $date;?>" class="btn confer-btn m-2">Purchase tickets<i class="zmdi zmdi-long-arrow-right"></i></a>
                        </div>
                        <div class="hero-btn-group text-background" data-animation="fadeInUp" data-delay="700ms">
                            <a href="<?php echo BASE; ?>Home/now_playing" class="btn confer-btn m-2"><i class="zmdi zmdi-long-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Trailer -->
        <div class="single-welcome-slide bg-overlay" style="background-size: cover; background-image: url(<?php echo $photosToShow[2]; ?>);">
            <div class="container h-100 img-responsive">
                <div class="row h-100 align-items-center mb-5">
                    <div class="embed-responsive embed-responsive-16by9" style="height: 500px; margin-top: 100px;">
                        <iframe class="embed-responsive-item"src="<?php echo $linkTrailer;?>" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
</section>