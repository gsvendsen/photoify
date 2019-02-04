<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';
// In this file we register new users.
if (isset($_POST['email'], $_POST['password'], $_POST['confirm-password'], $_POST['name'], $_POST['username'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $passwordHash = password_hash(filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);
    $imagePath = "/assets/images/phoimg_default.png";
    $coverImagePath = "/assets/images/cover_default.jpg";
    $biography = "";


    $charLimit = 100;

    if ($_POST['password'] !== $_POST['confirm-password']) {
        $_SESSION['error']['message'] = "Passwords did not match!";
        redirect("/?q=register");
    }

    // User input validation
    if (strlen($username) > $charLimit || strlen($name) > $charLimit) {
        $_SESSION['error']['message'] = "Username or Name contains too many characters!";
        redirect("/?q=register");
    }

    if (strlen($_POST['password']) < 8) {
        $_SESSION['error']['message'] = "Password is too short (min 8 characters)";
        redirect("/?q=register");
    }

    if (preg_match('/\s/', $username)) {
        $_SESSION['error']['message'] = "Username can't contain spaces!";
        redirect("/?q=register");
    }

    // Checks if user with same email or username already exists
    $selectStatement = $pdo->prepare('SELECT * FROM users WHERE email = :email OR username = :username');

    $selectStatement->bindParam(':email', $email, PDO::PARAM_STR);
    $selectStatement->bindParam(':username', $username, PDO::PARAM_STR);

    $selectStatement->execute();

    $user = $selectStatement->fetch(PDO::FETCH_ASSOC);

    // If user with same email or username does not exist
    if (!$user) {

    // Insert user data into user table in database
        $statement = $pdo->prepare('INSERT INTO users (name, email, password, image_path, banner_image_path, username, biography) VALUES (:name, :email, :password, :image_path, :banner_image_path, :username, :biography)');

        $statement->bindParam(':name', $name, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':password', $passwordHash, PDO::PARAM_STR);
        $statement->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
        $statement->bindParam(':banner_image_path', $coverImagePath, PDO::PARAM_STR);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':biography', $biography, PDO::PARAM_STR);

        $statement->execute();

        $idThing = $pdo->lastInsertId();

        // Selects newly made user and logs in
        $selectStatement = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $selectStatement->bindParam(':email', $email, PDO::PARAM_STR);
        $selectStatement->execute();

        $user = $selectStatement->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user'] = [
      'id' => $user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'biography' => $user['biography'],
      'img_path' => $user['image_path'],
      'banner_path' => $user['banner_image_path'],
      'username' => $user['username'],

    ];

        // Creates a user data folder with corresponding id
        $dir = __DIR__."/../data/{$user['id']}";
        mkdir($dir, 0777, true);
        // Users posts folder for post images
        mkdir($dir."/posts", 0777, true);
        // Users profile images as profile picture and banner picture
        mkdir($dir."/profile", 0777, true);

        $_SESSION['messages'][] = "Created new account successfully!";
    } else {
        $_SESSION['error']['message'] = "User with that email or username already exists!";

        redirect("/?q=register");
    }
}

redirect("/");
