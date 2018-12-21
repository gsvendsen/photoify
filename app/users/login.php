<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// In this file we login users.
if(isset($_POST['email'], $_POST['password'])){

  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);

  $query = "SELECT * FROM users WHERE email = :email";

  $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email');

  $statement->bindParam(':email', $email, PDO::PARAM_STR);

  $statement->execute();

  if ($statement) {

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // If email dont match
    if(!$user){
      $_SESSION['error'] = [
        'message' => "User with that email does not exist.",
        'email' => "",
      ];
      redirect("/");

    // If user exists and password matches
    } elseif(password_verify($_POST['password'], $user['password'])){

      $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
      ];

      $_SESSION['messages'][] = "Logged in successfully!";

    // If passwords dont match
    } else {
      $_SESSION['error'] = [
        'message' => "Credentials do not match.",
        'email' => $email,
      ];
      redirect("/login.php");
    }
  }
}

redirect("/");
