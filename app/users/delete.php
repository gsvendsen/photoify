<?php

declare(strict_types=1);
require __DIR__.'/../autoload.php';
// In this file user is deleted
if (!isset($_SESSION['user'])) {
    redirect('/');
} else {

    // Removes all of users posts
    $statement = $pdo->prepare("SELECT * FROM posts WHERE user_id = :id");

    $statement->bindParam(":id", $_SESSION['user']['id'], PDO::PARAM_STR);

    $statement->execute();

    $userPosts = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($userPosts !== false) {
        foreach ($userPosts as $post) {

            // Removes likes on users posts table
            $statement = $pdo->prepare("DELETE FROM likes WHERE post_id = :id");

            $statement->bindParam(":id", $post['id'], PDO::PARAM_STR);

            $statement->execute();

            // Removes comments on users posts table
            $statement = $pdo->prepare("DELETE FROM comments WHERE post_id = :id");

            $statement->bindParam(":id", $post['id'], PDO::PARAM_STR);

            $statement->execute();
        }
    }

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
