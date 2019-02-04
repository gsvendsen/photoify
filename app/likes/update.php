<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';
// In this file we update likes and dislikes and return the total like count for a post
if (isset($_GET['post'], $_GET['like'])) {
    $postId = filter_var(trim($_GET['post']), FILTER_SANITIZE_STRING);
    $likeValue = filter_var(trim($_GET['like']), FILTER_SANITIZE_STRING);

    // Allowed like values, 1: Like , -1: Dislike

    $allowedValues = [1,-1];

    if (in_array($likeValue, $allowedValues)) {

        // Checks if given post id exists
        $selectStatement = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $selectStatement->bindParam(':id', $postId, PDO::PARAM_STR);
        $selectStatement->execute();

        $postExists = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$postExists) {
        } else {
            if (isset($_GET['remove'])) {
                $selectStatement = $pdo->prepare('DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id');
                $selectStatement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
                $selectStatement->bindParam(':post_id', $postId, PDO::PARAM_STR);
                $selectStatement->execute();
            } else {

                // Checks if user has already liked post
                $selectStatement = $pdo->prepare('SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id');
                $selectStatement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
                $selectStatement->bindParam(':post_id', $postId, PDO::PARAM_STR);
                $selectStatement->execute();

                $likeExists = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

                if (!$likeExists) {
                    $statement = $pdo->prepare('INSERT INTO likes (user_id, post_id, like) VALUES (:user_id, :post_id, :like)');

                    $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
                    $statement->bindParam(':post_id', $postId, PDO::PARAM_STR);
                    $statement->bindParam(':like', $likeValue, PDO::PARAM_STR);

                    $statement->execute();
                } else {

                    // Adds like to likes table
                    $statement = $pdo->prepare('UPDATE likes SET like=:like WHERE user_id = :user_id AND post_id = :post_id');
                    $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
                    $statement->bindParam(':post_id', $postId, PDO::PARAM_STR);
                    $statement->bindParam(':like', $likeValue, PDO::PARAM_STR);

                    $statement->execute();
                }

                if (isset($_GET['location'])) {
                    $userLocation = filter_var($_GET['location'], FILTER_SANITIZE_STRING);
                }
            }
        }

        // Adds like to likes table
        $statement = $pdo->prepare('SELECT like FROM likes WHERE post_id = :post_id');
        $statement->bindParam(':post_id', $postId, PDO::PARAM_STR);

        $statement->execute();

        $likes = $statement->fetchAll(PDO::FETCH_ASSOC);

        $likeTotal = 0;

        // Adds up all likes and dislikes into likeTotal
        foreach ($likes as $like => $value) {
            $likeTotal += $value['like'];
        }

        if (!isset($likeTotal)) {
            $likeTotal = 0;
        }

        $return['likes'] = $likeTotal;
    }
}

// Returns new like count in json format
header('Content-Type: application/json');

$jsonData = json_encode($return);

echo $jsonData;
