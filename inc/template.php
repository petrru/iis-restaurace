<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?php Mapper::get_root_url(); ?>">
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/script.js"></script>
    <?php $page->print_extra_assets(); ?>
    <title><?php echo $page->get_title(); ?> - Dos compañeros</title>
</head>

<body>
<div class="name">
    <h1> · · · ∞ <a href="<?php echo $page->get_menu()->get_homepage_url() ?>">
            Dos compañeros</a> ∞ · · · </h1>
</div>


<div class="main_menu">
    <?php $page->get_menu()->print_menu(); ?>
</div>

<?php if (!empty($_SESSION['success_message'])): ?>
<div class="container success_message">
    <i class="material-icons">check</i>
    <?php
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    ?>
    <i class="material-icons close">close</i>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['error_message'])): ?>
<div class="container error_message">
    <i class="material-icons">warning</i>
    <?php
        echo $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    ?>
    <i class="material-icons close">close</i>
</div>
<?php endif; ?>

<?php $page->print_content(); ?>

<div class="before_footer"></div>
<div class="footer">
    <footer>
        <div class="row">
            <div class="column_33">
                <p class="heading">Otváracia doba</p>
                <p class="footer_text">Po-Pia od 10:00 do 21:00<br>So-Ne od 10:00 do 23:00</p>
            </div>
            <div class="column_33">
                <p class="heading">Obedy</p>
                <p class="footer_text">Po-So od 11:00 do 14:00</p>
            </div>
            <div class="column_33">
                <p class="heading">Rezervácie</p>
                <p class="footer_text">
                    +420 908 589 221<br>
                    <a href="reservation">» vytvoriť rezerváciu</a>
                </p>
            </div>
        </div>
    </footer>
</div>

</body>
