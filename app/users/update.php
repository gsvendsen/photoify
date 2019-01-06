<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// In this file user data is updated

if(!isset($_SESSION['user'])){
    redirect('/profile.php');
} else {

    $id = $_SESSION['user']['id'];

    $query = "SELECT * FROM users WHERE email = :email";

    $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email');

    $statement->bindParam(':email', $_SESSION['user']['email'], PDO::PARAM_STR);

    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // If password update was posted
    if(isset($_POST['newPassword'], $_POST['password']) && $_POST['password'] !== ""){
        if($_POST['newPassword'] === $_POST['password'] && $_POST['password'] === ""){

            $_SESSION['error']['message'] = "You cannot use the same password again!";
            redirect("/profile.php");

        } elseif(password_verify($_POST['password'], $user['password'])) {

            $newPassword = password_hash(filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);

            $updateStatement = $pdo->prepare("UPDATE users SET password = :newPassword WHERE id = :id");

            $updateStatement->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);
            $updateStatement->bindParam(':id', $id, PDO::PARAM_STR);

            $updateStatement->execute();

            $_SESSION['messages'][] = "Your password has been updated!";

        }

        else {
            $_SESSION['error']['message'] = "Wrong password!";
            redirect("/profile.php");
        }
    }

    // If name update was posted
    if(isset($_POST['name']) && $_POST['name'] !== $_SESSION['user']['name']){
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);

        $updateStatement = $pdo->prepare("UPDATE users SET name = :name WHERE id = :id");

        $updateStatement->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $updateStatement->bindParam(':id', $id, PDO::PARAM_STR);

        $updateStatement->execute();

        $_SESSION['messages'][] = "Your name has been updated!";

    }

    // If email update was posted
    if(isset($_POST['email']) && $_POST['email'] !== $_SESSION['user']['email']){
        $name = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);

        $updateStatement = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");

        $updateStatement->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $updateStatement->bindParam(':id', $id, PDO::PARAM_STR);

        $updateStatement->execute();

        $_SESSION['messages'][] = "Your email has been updated!";

    }

    // Updates profile picture if file was uploaded
    if(isset($_FILES['profile-picture'])){
        $fileTypes = ['image/png', 'image/jpg', 'image/jpeg'];

        if(in_array($_FILES['profile-picture']['type'], $fileTypes)){

        $userPath = __DIR__."/../data/{$_SESSION['user']['id']}/profile";

        $info = explode('.', strtolower( $_FILES['profile-picture']['name']) );
        move_uploaded_file( $_FILES['profile-picture']['tmp_name'], "{$userPath}/phoimg_dp.{$info[1]}");

        $dbPath = "/app/data/{$_SESSION['user']['id']}/profile/phoimg_dp.{$info[1]}";

        $statement = $pdo->prepare('UPDATE users SET image_path = :image_path WHERE id = :id');

        $statement->bindParam(':image_path', $dbPath, PDO::PARAM_STR);
        $statement->bindParam(':id', $_SESSION['user']['id'], PDO::PARAM_STR);

        $statement->execute();

        $_SESSION['messages'][] = "Your DP has been updated!";


        }
    }
}

// Updates user session after information update
$statement = $pdo->prepare('SELECT * FROM users WHERE id = :id');

$statement->bindParam(':id', $_SESSION['user']['id'], PDO::PARAM_STR);

$statement->execute();

$user = $statement->fetch(PDO::FETCH_ASSOC);

$_SESSION['user'] = [
  'id' => $user['id'],
  'name' => $user['name'],
  'email' => $user['email'],
  'img_path' => $user['image_path']
];

redirect("/");
