<main class="d-flex align-items-center justify-content-center">
     <div class="container mt-5">
          <div class="d-flex justify-content-center h-70 mt-5 mb-4">
               <div class="card mt-5 mb-5">
                    <div class="card-header">
                         <h3>Sign up</h3>
                         <div class="d-flex justify-content-end social_icon">
                              <span><i class="fas fa-id-card"></i></span>
				     </div>
                    </div>
                    <div class="card-body">
                         <form action="<?php echo BASE; ?>User/createUser" method=post>
                              <div class="input-group form-group">
                                   <input type="text" name="firstName" class="form-control" placeholder="First name" required>
                                   <input type="text" name="lastName" class="form-control" placeholder="Last name" required>
                              </div>
                              <div class="input-group form-group">
                                   <input type="email" name="email" class="form-control" placeholder="Email" required>        
                              </div>
                              <div class="input-group form-group">
                                   <input type="password" name="password" class="form-control" placeholder="Password" minlength="5" maxlength="20" required>
                              </div>
                              <div class="input-group form-group">
                                   <input type="password" name="confirmPassword" class="form-control" placeholder="Confirm password" minlength="5" maxlength="20" required>
                              </div>
                              <div class="form-group">
                              <div class="form-group float-left mt-3">
                                   <a href="<?php echo BASE; ?>Home/login" class="btn btn-outline-light">Back</a>
                              </div>
                                   <input type="submit" value="Create account" class="btn float-right btn-outline-light mt-3">
                              </div>
                         </form>
                    </div>
               </div>
          </div>
     </div>
</main>