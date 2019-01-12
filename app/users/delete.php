<?php

declare(strict_types=1);
require __DIR__.'/../autoload.php';

function rrmdir($dir) {
  foreach(glob($dir . '/*') as $file) {
    if(is_dir($file)) rrmdir($file); else unlink($file);
  } rmdir($dir);
}

if(!isset($_SESSION['user'])){
    redirect('/');
} else {

    // Removes all of users posts
    $statement = $pdo->prepare("DELETE FROM posts WHERE user_id = :id");

    $statement->bindParam(":id", $_SESSION['user']['id'], PDO::PARAM_STR);

    $statement->execute();

    // Removes user from user table
    $statement = $pdo->prepare("DELETE FROM users WHERE id = :id");

    $statement->bindParam(":id", $_SESSION['user']['id'], PDO::PARAM_STR);

    $statement->execute();

    // Removes user from likes table
    $statement = $pdo->prepare("DELETE FROM likes WHERE user_id = :id");

    $statement->bindParam(":id", $_SESSION['user']['id'], PDO::PARAM_STR);

    $statement->execute();

    // Removes user from follows table
    $statement = $pdo->prepare("DELETE FROM follows WHERE follower_id = :id OR following_id = :id");

    $statement->bindParam(":id", $_SESSION['user']['id'], PDO::PARAM_STR);

    $statement->execute();

    // Removes user data directory and all its content from data Directory
    $dirPath = __DIR__."/../data/{$_SESSION['user']['id']}";

    rrmdir($dirPath);

    //Unsets user session and goes to index
    unset($_SESSION['user']);
    redirect("/");
}
