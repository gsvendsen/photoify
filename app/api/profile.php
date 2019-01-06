<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

header('Content-Type: application/json');

if(!isset($_GET['user'])){
    redirect("/");
} else {

    $userQuery = filter_var($_GET['user'],FILTER_SANITIZE_STRING);

    // Fetches user data
    $selectStatement = $pdo->prepare('SELECT * FROM users WHERE username = :username');

    $selectStatement->bindParam(':username', $userQuery, PDO::PARAM_STR);

    $selectStatement->execute();

    $user = $selectStatement->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        $user['error'] = "User not found.";
        $jsonData = json_encode($user);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {

        // Fetches all posts from user
        $selectStatement = $pdo->prepare('SELECT * FROM posts WHERE user_id = :user_id');
        $selectStatement->bindParam(':user_id', $user['id'], PDO::PARAM_STR);
        $selectStatement->execute();
        $userPosts = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

        $userPosts2 = [];

        foreach ($userPosts as $post) {
            // Fetching posts likes
            $selectStatement = $pdo->prepare('SELECT like FROM likes WHERE post_id = :post_id');

            $selectStatement->bindParam(':post_id', $post['id'], PDO::PARAM_STR);

            $selectStatement->execute();

            $likes = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

            $likeTotal = 0;
            // Adds up all likes and dislikes into likeTotal
            foreach ($likes as $like => $value) {
                $likeTotal += $value['like'];
            }

            if($post['user_id'] == $_SESSION['user']['id']){
                $post['auth'] = 'true';
            } else {
                $post['auth'] = 'false';
            }

            $post['likes'] = $likeTotal;

            $userPosts2[] = $post;
        }

        $user['posts'] = $userPosts2;

        $jsonData = json_encode($user);

        header('Content-Type: application/json');


        echo $jsonData;
    }
}
