<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// In this file we fetch a users profile data and return it as a JSON

if (isset($_GET['user'])) {
    $userQuery = filter_var($_GET['user'], FILTER_SANITIZE_STRING);

    // Fetches user data
    $selectStatement = $pdo->prepare('SELECT id, name, username, image_path, banner_image_path, biography FROM users WHERE username = :username');

    $selectStatement->bindParam(':username', $userQuery, PDO::PARAM_STR);

    $selectStatement->execute();

    $user = $selectStatement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $user['error'] = "User not found.";
        $jsonData = json_encode($user);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {

        // Fetches all posts from user
        $selectStatement = $pdo->prepare('SELECT * FROM posts WHERE user_id = :user_id ORDER BY id DESC');
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

            if ($post['user_id'] == $_SESSION['user']['id']) {
                $post['auth'] = 'true';
            } else {
                $post['auth'] = 'false';
            }

            // Checking if user has liked post
            $selectStatement = $pdo->prepare('SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id');
            $selectStatement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
            $selectStatement->bindParam(':post_id', $post['id'], PDO::PARAM_STR);
            $selectStatement->execute();

            $likeExists = $selectStatement->fetch(PDO::FETCH_ASSOC);

            if ($likeExists) {
                if ($likeExists['like'] == 1) {
                    $post['disliked'] = false;
                    $post['liked'] = true;
                } else {
                    $post['disliked'] = true;
                    $post['liked'] = false;
                }
            } else {
                $post['liked'] = false;
                $post['disliked'] = false;
            }

            $post['likes'] = $likeTotal;

            $post['user'] = $user;

            $userPosts2[] = $post;
        }

        // Checks if user already follows id
        $selectStatement = $pdo->prepare('SELECT * FROM follows WHERE follower_id = :user_id AND following_id = :follow_id');
        $selectStatement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
        $selectStatement->bindParam(':follow_id', $user['id'], PDO::PARAM_STR);
        $selectStatement->execute();

        $followExists = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

        if (!$followExists) {
            $user['follows'] = "false";
        } else {
            $user['follows'] = "true";
        }

        $user['self'] = $user['username'] == $_SESSION['user']['username'];

        $user['posts'] = $userPosts2;

        $jsonData = json_encode($user);

        header('Content-Type: application/json');


        echo $jsonData;
    }
}
