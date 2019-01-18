<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// In this file we fetch a posts comments and return them as a JSON

if(!isset($_GET['post'])){
    redirect("/");
} else {

    $postId = filter_var($_GET['post'],FILTER_SANITIZE_STRING);

    // Fetches user data
    $selectStatement = $pdo->prepare('SELECT * FROM comments WHERE post_id = :post_id ORDER BY id DESC');

    $selectStatement->bindParam(':post_id', $postId, PDO::PARAM_STR);

    $selectStatement->execute();

    $commentsData = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

    if(!$commentsData){
        $user['error'] = "No comments for this post";
        $jsonData = json_encode($user);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {

        $comments = [];

        foreach ($commentsData as $comment) {

            // Fetching posts user
            $selectStatement = $pdo->prepare('SELECT id, username, image_path FROM users WHERE id = :user_id');

            $selectStatement->bindParam(':user_id', $comment['user_id'], PDO::PARAM_STR);

            $selectStatement->execute();

            $commentUser = $selectStatement->fetch(PDO::FETCH_ASSOC);

            $comment['user'] = $commentUser;

            if($comment['user']['id'] == $_SESSION['user']['id']){
                $comment['self'] = true;
            } else {
                $comment['self'] = false;
            }

            $comments[] = $comment;
        }

        $jsonData = json_encode($comments);

        header('Content-Type: application/json');

        echo $jsonData;
    }
}
