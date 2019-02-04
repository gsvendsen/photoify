<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

if (isset($_GET['post'], $_GET['content'])) {
    $postId = filter_var(trim($_GET['post']), FILTER_SANITIZE_STRING);
    $content = filter_var(trim($_GET['content']), FILTER_SANITIZE_STRING);

    if ($content !== "") {
        $statement = $pdo->prepare('INSERT INTO comments (content, post_id, user_id) VALUES (:content, :post_id, :user_id)');

        $statement->bindParam(':content', $content, PDO::PARAM_STR);
        $statement->bindParam(':post_id', $postId, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);

        $statement->execute();

        $return['success'] = true;
    } else {
        $return['success'] = false;
    }
} else {
    $return['success'] = false;
}

$jsonData = json_encode($return);

header('Content-Type: application/json');


echo $jsonData;
