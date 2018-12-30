<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

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
    } elseif($_SESSION['user']['id'] !== $postUserId['user_id']){
        $_SESSION['messages'][] = "You do not have authorization to delete that post.";
        redirect("/");
    } else {
        $statement = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        $statement->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $statement->execute();

        $_SESSION['messages'][] = "Post was deleted";
        redirect("/");
    }
}
