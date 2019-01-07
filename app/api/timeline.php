<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

header('Content-Type: application/json');

if(!isset($_GET['user'])){
    redirect("/");
} else {

    $userQuery = filter_var($_GET['user'],FILTER_SANITIZE_STRING);

    // Fetches user data
    $selectStatement = $pdo->prepare('SELECT * FROM follows WHERE follower_id = :user_id');

    $selectStatement->bindParam(':user_id', $userQuery, PDO::PARAM_STR);

    $selectStatement->execute();

    $follows = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

    if(!$follows){
        $follows['error'] = "User does not follow anyone.";
        $jsonData = json_encode($follows);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {

        // Maps following connections into array of user ids that $userQuery follows
        $followingUsers = array_map(function ($follow) {
            return $follow['following_id'];
        }, $follows);


        $implodedArray = implode(',', $followingUsers);


        $selectStatement = $pdo->prepare("SELECT * FROM posts WHERE user_id IN ($implodedArray) ORDER BY id DESC");
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
            $selectStatement = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');

            $selectStatement->bindParam(':user_id', $post['user_id'], PDO::PARAM_STR);

            $selectStatement->execute();

            $postUser = $selectStatement->fetch(PDO::FETCH_ASSOC);

            if($post['user_id'] == $_SESSION['user']['id']){
                $post['auth'] = 'true';
            } else {
                $post['auth'] = 'false';
            }

            $post['user'] = $postUser;

            $post['likes'] = $likeTotal;

            $timelinePosts2[] = $post;
        }

        $jsonData = json_encode($timelinePosts2);

        header('Content-Type: application/json');


        echo $jsonData;
    }
}
