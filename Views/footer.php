    <footer id="footer">
        <img id="footer-logo" src="<?php echo BASE; ?>Views/img/core-img/old_logo.png">
        <p id="footer-link" class="btn btn-link disabled mt-1">Copyright &copy; <?php echo date('Y'); ?> - All Rights Reserved</p>
        <p id="footer-link" class="btn btn-link disabled mt-1">Users online: <?php use DAO\UserOnlineManager as UserOnlineManager; echo UserOnlineManager::retrieveAllUsersOnline(); ?></p>
    </footer>

    <?php
    //Alert messages
    if(isset($messageType) && isset($message)) {
        if($messageType == 1) {
            echo "<script>
            Swal.fire(
                'Confirmation message',
                '" . $message . "',
                'success'
            );</script>";
        }
        else if($messageType == 0) {
            echo "<script>
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: '" . $message . "'
            });</script>";
        }
        else if($messageType == 3) {
            echo "<script> alertify.set('notifier','position', 'top-left'); alertify.success('" . $message .  "'); </script>";
        }
    }
    ?>
   
    <!-- **** Archivos JS ***** -->
    <!-- jQuery 2.2.4 -->
    <script src="<?php echo BASE; ?>Views/js/jquery.min.js"></script>
    <!-- Popper -->
    <script src="<?php echo BASE; ?>Views/js/popper.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo BASE; ?>Views/js/bootstrap.min.js"></script>
    <!-- Plugins -->
    <script src="<?php echo BASE; ?>Views/js/confer.bundle.js"></script>
    <!-- Active -->
    <script src="<?php echo BASE; ?>Views/js/default-assets/active.js"></script>
    </body>
</html>