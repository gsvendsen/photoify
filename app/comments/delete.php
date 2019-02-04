<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

if (!isset($_GET['id'])) {
    redirect('/');
} else {
    $deleteId = filter_var(trim($_GET['id']), FILTER_SANITIZE_STRING);
    $statement = $pdo->prepare('SELECT user_id FROM comments WHERE id = :id');
    $statement->bindParam(':id', $deleteId, PDO::PARAM_STR);
    $statement->execute();

    $postUserId = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$postUserId) {
        $user['error'] = "The comment you tried to delete does not exist.";
        $jsonData = json_encode($user);
        header('Content-Type: application/json');
        echo $jsonData;
    } else {
        if (strval($_SESSION['user']['id']) !== $postUserId['user_id']) {
            $user['error'] = "You do not have authorization to delete that comment.";
            $jsonData = json_encode($user);
            header('Content-Type: application/json');
            echo $jsonData;
        } else {

            // Deletes comment
            $statement = $pdo->prepare('DELETE FROM comments WHERE id = :id');
            $statement->bindParam(':id', $deleteId, PDO::PARAM_INT);
            $statement->execute();

            $response['success'] = true;

            $jsonData = json_encode($response);

            header('Content-Type: application/json');

            echo $jsonData;
        }
    }
}
