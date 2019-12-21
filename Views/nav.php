<header class="header-area">
    <div class="classy-nav-container breakpoint-off">
        <div class="container">

            <!-- Menu "Classy" -->
            <nav class="classy-navbar justify-content-between" id="conferNav">

                <!-- Logo -->
                <a class="nav-brand" href="<?php echo BASE; ?>"><img src="<?php echo BASE; ?>Views/img/core-img/logo.png" alt=""></a>

                <!-- "Toggler" -->
                <div class="classy-navbar-toggler">
                    <span class="navbarToggler"><span></span><span></span><span></span></span>
                </div>

                <!-- Menu -->
                <div class="classy-menu">

                    <!-- Button "Close" -->
                    <div class="classycloseIcon">
                        <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                    </div>
                    
                    <!-- Navigation bar -->
                    <div class="classynav">
                        <ul id="nav">
                            <li class="active"><a href="<?php echo BASE; ?>">Home</a></li>
                            <li><a href="<?php echo BASE; ?>Home/now_playing">Now playing</a></li>
                            <?php if(isset($user) && $user->getUserRoleDescription()=="user") { ?>
                                <li>
                                <a href="#">Account</a>
                                    <ul class="dropdown">
                                        <?php
                                        if($user->getIdFacebook()!=null)
                                            echo "<li><a href=https://www.facebook.com>- Ir a Facebook</a></li>";
                                        ?>
                                        <li><a href="<?php echo BASE; ?>Home/account">- My profile</a></li>
                                        <li><a href="<?php echo BASE; ?>Home/purchase_list">- My purchases</a></li>
                                        <li><a href="<?php echo BASE; ?>User/logout">- Logout</a></li>
                                    </ul>
                                </li>

                                <?php if ($user->getPhoto() == null) { ?>
                                    <img class="single-schedule-tumb" src="<?php echo BASE; ?>Views/img/core-img/user.png" alt="">
                                <?php } else { ?>
                                    <img class="single-schedule-tumb" src="data:image/jpeg;base64,<?php echo base64_encode($user->getPhoto()); ?>" alt="">
                                <?php } ?>

                            <?php } else if($user == null) { ?>
                                <li><a href="<?php echo BASE; ?>Home/login">Sign in</a></li>
                                <li><img class="single-schedule-tumb" src="<?php echo BASE; ?>Views/img/core-img/anon.png" alt=""></li>
                            <?php } ?>
                            <?php if(isset($user) && $user->getUserRoleDescription()=="admin") { ?>
                            <li>
                                <a href="#">Admin</a>
                                    <ul class="dropdown">
                                        <li><a href="<?php echo BASE; ?>Home/showtime_list">- Showtimes</a></li>
                                        <li><a href="<?php echo BASE; ?>Home/new_showtime">- New showtime</a></li>
                                        <li id='separator'> </li>
                                        <li><a href="<?php echo BASE; ?>Home/admin_movietheaters">- Movie theaters</a></li>
                                        <li id='separator'> </li>
                                        <li><a href="<?php echo BASE; ?>Home/total_sales_amount">- Total sales</a></li>
                                        <li id='separator'> </li>
                                        <li><a href="<?php echo BASE; ?>Movie/loadMoviesIntoBD">- Up movies</a></li>
                                        <li id='separator'> </li>
                                        <li><a href="<?php echo BASE; ?>User/logout">- Logout</a></li>
                                    </ul>
                            <li><img class="single-schedule-tumb" src="<?php echo BASE; ?>Views/img/core-img/admin.png" alt=""></li>
                            </li>
                            <?php } ?>
                        </ul>
                  </div>
                </div>
            </nav>
        </div>
    </div>
</header>