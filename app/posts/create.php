<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';
// In this file we create new posts

if (isset($_FILES['image'], $_POST['description'])) {
    $characterLimit = 64;

    $fileTypes = ['image/png', 'image/jpg', 'image/jpeg'];

    $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING);

    // If image file type is allowed
    if (in_array($_FILES['image']['type'], $fileTypes)) {
        if ($description !== "") {
            if (strlen($description) > $characterLimit) {
                $_SESSION['error']['message'] = "Description can only be {$characterLimit} characters long!";
                redirect("/post.php");
            } else {
                $date = getDate();
                $formatDate = "{$date['year']}-{$date['mon']}-{$date['mday']}";

                $statement = $pdo->prepare('INSERT INTO posts (user_id, description, date) VALUES (:user_id, :description, :date)');

                $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':date', $formatDate, PDO::PARAM_STR);

                $statement->execute();

                $postId = $pdo->lastInsertId();

                $postDir = __DIR__."/../data/{$_SESSION['user']['id']}/posts/{$postId}";

                mkdir($postDir, 0777, true);

                $uniqueId = uniqid();

                $info = explode('.', strtolower($_FILES['image']['name']));
                move_uploaded_file($_FILES['image']['tmp_name'], "{$postDir}/phoimg_{$uniqueId}.{$info[1]}");

                $dbDir = "/app/data/{$_SESSION['user']['id']}/posts/{$postId}/phoimg_{$uniqueId}.{$info[1]}";

                $statement2 = $pdo->prepare('UPDATE posts SET img_path = :img_path WHERE id = :id');

                $statement2->bindParam(':img_path', $dbDir, PDO::PARAM_STR);
                $statement2->bindParam(':id', $postId, PDO::PARAM_STR);

                $statement2->execute();

                $_SESSION['messages'][] = "New post was uploaded!";
            }
        } else {
            $_SESSION['error']['message'] = "No description was given!";
            redirect("/post.php");
        }
    } else {
        $_SESSION['error']['message'] = "File type {$_FILES['image']['type']} not allowed!";
        redirect("/post.php");
    }
}

redirect("/");
