<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// In this file we fetch a users timeline (own posts and posts of who they follow) and return it as a JSON

if(isset($_GET['user'])){

    $userQuery = filter_var($_GET['user'],FILTER_SANITIZE_STRING);

    // Fetches user data
    $selectStatement = $pdo->prepare('SELECT * FROM follows WHERE follower_id = :user_id');

    $selectStatement->bindParam(':user_id', $userQuery, PDO::PARAM_STR);

    $selectStatement->execute();

    $follows = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

    $followingUsers = array_map(function ($follow) {
        return $follow['following_id'];
    }, $follows);

    // Array containing all of the user_id's that the user is following
    $implodedArray = implode(',', $followingUsers);

    // Fetching posts from following users and users own posts
    $selectStatement = $pdo->prepare("SELECT * FROM posts WHERE user_id IN ($implodedArray) OR user_id=:user_id ORDER BY id DESC");
    $selectStatement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_STR);
    $selectStatement->execute();
    $timelinePosts = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($timelinePosts as $post) {
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

        // Fetching posts user
        $selectStatement = $pdo->prepare('SELECT id, name, username, image_path FROM users WHERE id = :user_id');

        $selectStatement->bindParam(':user_id', $post['user_id'], PDO::PARAM_STR);

        $selectStatement->execute();

        $postUser = $selectStatement->fetch(PDO::FETCH_ASSOC);

        if($post['user_id'] == $_SESSION['user']['id']){
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

        // Setting posts like and dislikes bools depending if/what previous like exists
        if($likeExists){
            if($likeExists['like'] == 1){
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


        $post['user'] = $postUser;

        $post['likes'] = $likeTotal;

        $timelinePosts2[] = $post;
    }

    $jsonData = json_encode($timelinePosts2);

    header('Content-Type: application/json');


    echo $jsonData;
}
