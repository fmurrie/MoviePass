<section class="welcome-area">
    <div class="welcome-slides owl-carousel">
        <!-- Slide -->
        <div class="single-welcome-slide bg-img bg-overlay jarallax bg-home" style="height: 820px;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12">
                        <div class="welcome-text-two">
                            <h2 class="text-center" data-animation="fadeInUp" data-delay="300ms">Moviepass</h2>
                            <h4 class="text-center" data-animation="fadeInUp" data-delay="100ms">Movie tickets</h4>
                            <!-- Director/date -->
                            <div class="event-meta mt-5 text-left ml-2" data-animation="fadeInUp" data-delay="500ms">
                                <a class="event-date"><i class="zmdi zmdi-thumb-up"></i>Going to the cinema has never been easier with MoviePass</a><br/>
                                <a class="event-author"><i class="zmdi zmdi-thumb-up"></i>Don't have an account? Sign up for FREE!</a>
                            </div>
                            <?php if(!isset($user)) { ?>
                            <div class="hero-btn-group text-left ml-2" data-animation="fadeInUp" data-delay="700ms">
                                <a href="<?php echo BASE; ?>Home/login" class="btn confer-btn">Sign in <i class="zmdi zmdi-long-arrow-right"></i></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide -->
        <div class="single-welcome-slide bg-img bg-overlay jarallax bg-sw" style="height: 820px;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <!-- Title -->
                    <div class="col-12">
                        <div class="welcome-text-two text-center">
                            <h2 data-animation="fadeInUp" data-delay="300ms">Coming soon</h2>
                            <h4 data-animation="fadeInUp" data-delay="100ms">Star Wars: Episode 9</h4>
                            <!-- Director/release date -->
                            <div class="event-meta" data-animation="fadeInUp" data-delay="500ms">
                                <a class="event-date"><i class="zmdi zmdi-account"></i>J. J. Abrams</a>
                                <a class="event-author"><i class="zmdi zmdi-alarm-check"></i>19 December 2019</a>
                            </div>
                            <div class="hero-btn-group" data-animation="fadeInUp" data-delay="700ms">
                                <a href="https://www.imdb.com/title/tt2527338/" class="btn confer-btn m-2">More info<i class="zmdi zmdi-long-arrow-right"></i></a>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide -->
        <div class="single-welcome-slide bg-img bg-overlay jarallax bg-ju" style="height: 820px;">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <!-- Title -->
                    <div class="col-12">
                        <div class="welcome-text-two text-center">
                            <h2 data-animation="fadeInUp" data-delay="300ms">Coming soon</h2>
                            <h4 data-animation="fadeInUp" data-delay="100ms">Jumanji: The Next Level</h4>
                            <!-- Director/release date -->
                            <div class="event-meta" data-animation="fadeInUp" data-delay="500ms">
                                <a class="event-date"><i class="zmdi zmdi-account"></i>Jake Kasdan</a>
                                <a class="event-author"><i class="zmdi zmdi-alarm-check"></i>2 January 2020</a>
                            </div>
                            <div class="hero-btn-group" data-animation="fadeInUp" data-delay="700ms">
                                <a href="https://www.imdb.com/title/tt7975244/?ref_=rlm" class="btn confer-btn m-2">More info<i class="zmdi zmdi-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>