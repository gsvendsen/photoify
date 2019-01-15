<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

if(!isset($_GET['id'])){
    redirect('/');
} else {

    $deleteId = filter_var(trim($_GET['id']),FILTER_SANITIZE_STRING);
    $statement = $pdo->prepare('SELECT user_id FROM comments WHERE id = :id');
    $statement->bindParam(':id', $deleteId, PDO::PARAM_STR);
    $statement->execute();

    $postUserId = $statement->fetch(PDO::FETCH_ASSOC);

    if(!$postUserId){
        $_SESSION['messages'][] = "The comment you tried to delete does not exist.";
        redirect("/");
    } elseif(strval($_SESSION['user']['id']) !== $postUserId['user_id']){
        $_SESSION['messages'][] = "You do not have authorization to delete that comment.";
        redirect("/");
    } else {
        $statement = $pdo->prepare('DELETE FROM comments WHERE id = :id');
        $statement->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $statement->execute();

        $_SESSION['messages'][] = "Comment was deleted";
        redirect("/");
    }
}
