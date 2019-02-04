<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';
// In this file we update a follow between two users
if (isset($_GET['follow'])) {
    $followId = filter_var(trim($_GET['follow']), FILTER_SANITIZE_STRING);
    $userId = $_SESSION['user']['id'];

    // Checks if given user to follow exists
    $selectStatement = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $selectStatement->bindParam(':id', $followId, PDO::PARAM_STR);
    $selectStatement->execute();

    $userExists = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

    if (!$userExists) {
        $_SESSION['messages'][] = "User you tried to follow does not exist";
        redirect("/");
    } else {

        // Checks if user already follows id
        $selectStatement = $pdo->prepare('SELECT * FROM follows WHERE follower_id = :user_id AND following_id = :follow_id');
        $selectStatement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
        $selectStatement->bindParam(':follow_id', $followId, PDO::PARAM_STR);
        $selectStatement->execute();

        $followExists = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$followExists) {
            $statement = $pdo->prepare('INSERT INTO follows (follower_id, following_id) VALUES (:user_id, :follow_id)');

            $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
            $statement->bindParam(':follow_id', $followId, PDO::PARAM_STR);

            $statement->execute();

            $_SESSION['messages'][] = "You are now following that user!";
        } elseif (isset($_GET['unfollow']) || $_GET['unfollow'] == "true") {
            $statement = $pdo->prepare('DELETE FROM follows WHERE follower_id=:user_id AND following_id = :follow_id');

            $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
            $statement->bindParam(':follow_id', $followId, PDO::PARAM_STR);

            $statement->execute();

            $_SESSION['messages'][] = "You are no longer following that user!";
        }

        if (isset($_GET['location'])) {
            $userLocation = filter_var($_GET['location'], FILTER_SANITIZE_STRING);
            redirect("/?u={$userLocation}");
        }
    }
}



redirect("/");
