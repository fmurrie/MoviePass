<main class="d-flex align-items-center justify-content-center">
     <div class="container mt-5">
          <div class="d-flex justify-content-center h-70 mt-5 mb-4">
               <div class="card mt-5 mb-5">
                    <div class="card-header">
                         <h3>Sign in</h3>
                         <div class="d-flex justify-content-end social_icon">
                              <span><i class="fas fa-sign-in-alt"></i></span>
				     </div>
                    </div>
                    <div class="card-body">
                         <form action="<?php echo BASE; ?>User/loginUser" method=post>
                              <div class="input-group form-group">
                                   <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                   </div>
                                   <input type="email" name="email" class="form-control" placeholder="Email" required>
                              </div>
                              <div class="input-group form-group">
                                   <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                   </div>
                                   <input type="password" name="password" class="form-control" placeholder="Password" required>
                              </div>
                              <div class="form-group">
                                   <input type="submit" value="Log in" class="btn float-right btn btn-primary">
                              </div>
                         </form>
                    </div>
                    <div class="d-flex justify-content-center align-items-center text-white">
                         <?php
                         require_once "FacebookConfig.php";
                         $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
                              echo "<br><a href=" . htmlspecialchars($loginUrl) . "><img src='" . BASE . "Views/img/core-img/buttonLoginFB.png' width=250px></a>";
                         ?>
                    </div>
                    <div class="card-footer">
                         <div class="d-flex justify-content-center align-items-center text-white">
                              Don't have an account?
                         </div>
                         <div class="d-flex justify-content-center align-items-center text-white mt-1">
                              <a class="btn btn-outline-light" href="<?php echo BASE; ?>Home/signup">Sign up</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</main>