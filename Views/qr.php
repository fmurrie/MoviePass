<main class="d-flex align-items-center justify-content-center">
     <div class="container">
          <div class="row">
               <div class="container text-center" style="margin-top: 150px; width: 450px;">
                    <div class="single-blog-area wow">
                         <div class="single-blog-thumb">
                              <img src="data:image/jpeg;base64,<?php echo base64_encode($purchase->getQr()); ?>"/>
                         </div>
                         <div class="single-blog-text text-center">
                              <a class="blog-title" disabled style="color: #df42b1;">QR code</a><br/>
                              <h6>The QR code will be required to print the tickets in the movie theater.</h6>
                              <a href="<?php echo BASE . "Home/purchase_list" . "?pageno=" . $pageno . "&filter=" . $filter;?>" class="btn btn-primary mt-2">Back</a>
                         </div>
                    </div>   
               </div>
          </div>
     </div>
</main>