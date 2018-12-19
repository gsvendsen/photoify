<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';


if(isset($_FILES['image'], $_POST['description'])){

    $fileTypes = ['image/png', 'image/jpg'];

    $description = filter_var(trim($_POST['description']),FILTER_SANITIZE_STRING);

    // If image file type is allowed
    if(in_array($_FILES['image']['type'], $fileTypes)){

        if($description !== ""){

            $date = getDate();
            $formatDate = "{$date['year']}-{$date['mon']}-{$date['mday']}";

            $statement = $pdo->prepare('INSERT INTO posts (user_id, description, date, image) VALUES (:user_id, :description, :date, :image)');

            $statement->bindParam(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
            $statement->bindParam(':description', $description, PDO::PARAM_STR);
            $statement->bindParam(':date', $formatDate, PDO::PARAM_STR);
            $statement->bindParam(':image', $_FILES['image']['name'], PDO::PARAM_STR);

            $statement->execute();

            $_SESSION['messages'][] = "New post was uploaded!";

            mkdir("../data/dog", 0777);

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
