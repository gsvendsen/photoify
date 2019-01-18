<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';
// In this file we delete posts

if(!isset($_GET['locale'])){
    redirect('/');
} else {

    $deleteId = filter_var(trim($_GET['locale']),FILTER_SANITIZE_STRING);
    $statement = $pdo->prepare('SELECT user_id FROM posts WHERE id = :id');
    $statement->bindParam(':id', $deleteId, PDO::PARAM_STR);
    $statement->execute();

    $postUserId = $statement->fetch(PDO::FETCH_ASSOC);

    if(!$postUserId){
        $_SESSION['messages'][] = "The post you tried to delete does not exist.";
        redirect("/");
    } elseif(intval($_SESSION['user']['id']) !== intval($postUserId['user_id'])){
        $_SESSION['messages'][] = "You do not have authorization to delete that post.";
        redirect("/");
    } else {
        $statement = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        $statement->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $statement->execute();

        $statement = $pdo->prepare('DELETE FROM likes WHERE post_id = :id');
        $statement->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $statement->execute();

        // Removes user post directory and all its content from data Directory
        $dirPath = __DIR__."/../data/{$_SESSION['user']['id']}/posts/$deleteId";

        rrmdir($dirPath);

        $_SESSION['messages'][] = "Post was deleted";
        redirect("/");
    }
}
