<?php

declare(strict_types=1);

if (isset($_GET['q'])) {
    if ($_GET['q'] == 'register') {
        // Shows register page if q paremeter is register
        require __DIR__.'/register.php';
    } else {
        // Shows log in page otherwise
        require __DIR__.'/login.php';
    }
} else {
    // Shows log in page otherwise
    require __DIR__.'/login.php';
}
