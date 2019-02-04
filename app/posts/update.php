<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';
// In this file we update a posts description

if (!isset($_SESSION['user'], $_POST['description'], $_GET['post'])) {
    redirect('/');
} else {
    $newDescription = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $postId = filter_var($_GET['post'], FILTER_SANITIZE_STRING);

    $updateStatement = $pdo->prepare("UPDATE posts SET description = :description WHERE id = :post_id");

    $updateStatement->bindParam(':description', $newDescription, PDO::PARAM_STR);
    $updateStatement->bindParam(':post_id', $postId, PDO::PARAM_STR);

    $updateStatement->execute();

    $_SESSION['messages'][] = "Your post has been updated!";
}

redirect("/");
