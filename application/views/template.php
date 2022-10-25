<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css">
    </head>
    <body>
    <header class="card" style="margin-bottom:60px;">
        <div class="navbar-brand" href="#">
            <!-- <img src="/docs/4.0/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt=""> -->
            <strong>BeFocused</strong>
            <span style="cursor: pointer;" onclick="window.location='/main/index';" class="text-muted float-right"><i style="margin-right:7px;" class="fa fa-info"></i>About</span>
        </div>
    </header>
    <div style="width:70%;margin: 0px auto;min-height:100%;height:100%;">
        <?php 
            include_once 'pages/page_main_goals.php';
        ?>
    </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
    </body>
</html>