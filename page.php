<?php
session_start();
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.php';
});

DB::connect();
Utils::init_user();

$mapper = new Mapper();
$page = $mapper->create_page($_GET['q']);

if (!$page->check_privileges(Utils::get_logged_user()->position_id)) {
    if (Utils::get_logged_user()->employee_id) {
        // Uživatel nemá dostatečná oprávnění
        $page = new NotFoundPage();
    }
    else {
        // Uživatel není přihlášen
        Utils::set_error_message('Přihlašte se prosím ještě jednou');
        header('Location: ' . Mapper::url('login') . '?back='
            . base64_encode($_SERVER['REQUEST_URI']));
        exit;
    }
}

try {
    $page->init();
} catch (NoEntryException $e) {
    $page = new NotFoundPage();
}

if ($page->should_print_html()) {
    include "inc/template.php";
}