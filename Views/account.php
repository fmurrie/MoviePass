<main class="d-flex align-items-center justify-content-center">
     <div class="container">
          <div class="row">
               <div class="container text-center" style="margin-top: 150px; width: 450px;">
                    <div class="single-blog-area wow">
                         <div class="single-blog-thumb">
                              <?php if ($user->getPhoto() == null) { ?>
                                   <img src="<?php echo BASE; ?>Views/img/core-img/user.png" alt="">
                              <?php } else { ?>
                                   <img src="data:image/jpeg;base64,<?php echo base64_encode($user->getPhoto()); ?>"/>
                              <?php } ?>
                         </div>
                         <div class="single-blog-text text-center">
                              <a class="blog-title" disabled style="color: #df42b1;"><?=$user->getFirstName() . " " . $user->getLastName() . " "; ?></a><br/>
                              <i class="fas fa-envelope-square"></i><?=" Email: " . $user->getEmail();?><br/>
                              <button data-toggle="modal" data-target="#modificar-avatar" class="btn btn-primary mt-2">Update photo</button>
                              <button data-toggle="modal" data-target="#modificar-datos" class="btn btn-primary mt-2">Update name</button>
                         </div>
                    </div>   
               </div>
          </div>
     </div>
</main>

<div class="modal fade mt-5" id="modificar-datos" tabindex="-1" userRolee="dialog" aria-labelledby="sign-up" aria-hidden="true">
     <div class="modal-dialog" userRolee="document">
          <form class="modal-content" action="<?php echo BASE; ?>User/updateUser" method="POST">
               <div class="modal-header">
                    <h5 class="modal-title">Update name</h5>
                    <button type="button" class="close" data-dismiss="modal">
                         <span>&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $user->getId();?>" class="form-control">
                    <div class="form-group">
                         <label>First name</label>
                         <input type="text" value="<?php echo $user->getFirstName();?>" class="form-control" name="firstName" required>
                    </div>
                    <div class="form-group">
                         <label>Last name</label>
                         <input type="text" value="<?php echo $user->getLastName();?>" class="form-control" name="lastName" required>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Update</button>
               </div>
          </form>
     </div>
</div>

<div class="modal fade mt-5" id="modificar-avatar" tabindex="-1" userRolee="dialog" aria-labelledby="sign-up" aria-hidden="true">
     <div class="modal-dialog" userRolee="document">
          <form class="modal-content" action="<?php echo BASE; ?>User/loadAvatar" method="POST" enctype="multipart/form-data">
               
               <script src="<?php echo BASE; ?>Views/js/avatar.js"></script>
               <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
               
               <!-- some CSS styling changes and overrides -->
               <style>
               .file-upload {
               background-color: #ffffff;
               width: 500px;
               margin: 0 auto;
               padding: 20px;
               }

               @media screen and (max-width: 600px) {
               .file-upload  {
                    margin-top: 100px;
                    height: 30%;
                    width: 80%;
                    max-width: 95%;
                    margin: 1em;
                    display: block;
               }
               }

               .file-upload-btn {
               width: 100%;
               margin: 0;
               color: #fff;
               background: #1FB264;
               border: none;
               padding: 10px;
               border-radius: 4px;
               border-bottom: 4px solid #15824B;
               transition: all .2s ease;
               outline: none;
               text-transform: uppercase;
               font-weight: 700;
               }

               .file-upload-btn:hover {
               background: #1AA059;
               color: #ffffff;
               transition: all .2s ease;
               cursor: pointer;
               }

               .file-upload-btn:active {
               border: 0;
               transition: all .2s ease;
               }

               .file-upload-content {
               display: none;
               text-align: center;
               }

               .file-upload-input {
               position: absolute;
               margin: 0;
               padding: 0;
               width: 100%;
               height: 100%;
               outline: none;
               opacity: 0;
               cursor: pointer;
               }

               .image-upload-wrap {
               margin-top: 20px;
               border: 4px dashed #1FB264;
               position: relative;
               }

               .image-dropping,
               .image-upload-wrap:hover {
               background-color: #1FB264;
               border: 4px dashed #ffffff;
               }

               .image-title-wrap {
               padding: 0 15px 15px 15px;
               color: #222;
               }

               .drag-text {
               text-align: center;
               }

               .drag-text h3 {
               font-weight: 100;
               text-transform: uppercase;
               color: #15824B;
               padding: 60px 0;
               }

               .file-upload-image {
               max-height: 200px;
               max-width: 200px;
               margin: auto;
               padding: 20px;
               }

               .remove-image {
               width: 200px;
               margin: 0;
               color: #fff;
               background: #cd4535;
               border: none;
               padding: 10px;
               border-radius: 4px;
               border-bottom: 4px solid #b02818;
               transition: all .2s ease;
               outline: none;
               text-transform: uppercase;
               font-weight: 700;
               }

               .remove-image:hover {
               background: #c13b2a;
               color: #ffffff;
               transition: all .2s ease;
               cursor: pointer;
               }

               .remove-image:active {
               border: 0;
               transition: all .2s ease;
               }
               </style>
               
               <div class="file-upload justify-content: center">
                    <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Upload photo</button>
                    <div class="image-upload-wrap">
                         <input class="file-upload-input" name="image" type='file' onchange="readURL(this);" accept="image/*" />
                         <div class="drag-text">
                              <h3>Drag and drop an image here or select Upload photo</h3>
                         </div>
                    </div>
                    <div class="file-upload-content">
                         <img class="file-upload-image" src="#" alt="your image" />
                              <div class="image-title-wrap">
                                   <button type="button" onclick="removeUpload()" class="remove-image">Delete <span class="image-title">Upload photo</span></button>
                              </div>
                         </div>
                    <button type="submit" class="btn btn-dark mt-3 pull-right">Update</button>
                    <button type="button" class="btn btn-link mt-3 pull-right" data-dismiss="modal">Cancel</button>
               </div>
          </form>
     </div>
</div>
