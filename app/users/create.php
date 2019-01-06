<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// In this file we login users.
if(isset($_POST['email'], $_POST['password'], $_POST['name'])){

  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
  $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
  $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
  $passwordHash = password_hash(filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);
  $imagePath = "/assets/images/phoimg_default.png";

  // Checks if user with email already exists
  $selectStatement = $pdo->prepare('SELECT * FROM users WHERE email = :email OR username = :username');

  $selectStatement->bindParam(':email', $email, PDO::PARAM_STR);
  $selectStatement->bindParam(':username', $username, PDO::PARAM_STR);

  $selectStatement->execute();

  $user = $selectStatement->fetch(PDO::FETCH_ASSOC);

  if(!$user){
    // Insert user data into user table in database
    $statement = $pdo->prepare('INSERT INTO users (name, email, password, image_path, username) VALUES (:name, :email, :password, :image_path, :username)');

    $statement->bindParam(':name', $name, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':password', $passwordHash, PDO::PARAM_STR);
    $statement->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);

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
      'img_path' => $user['image_path'],
      'username' => $user['username'],

    ];

    $dir = __DIR__."/../data/{$user['id']}";
    mkdir($dir, 0777, true);
    mkdir($dir."/posts", 0777, true);
    mkdir($dir."/profile", 0777, true);

    $_SESSION['messages'][] = "Created new account successfully!";

  } else {
    $_SESSION['error']['message'] = "User with that email or username already exists!";

    redirect("/?q=register");
  }
}

redirect("/");
