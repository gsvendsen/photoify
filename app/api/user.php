<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

$userId['id'] = $_SESSION['user']['id'];

$jsonData = json_encode($userId);

header('Content-Type: application/json');

echo $jsonData;
