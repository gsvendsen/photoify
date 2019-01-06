<?php
declare(strict_types=1);

// Fetching logged in users posts
$selectStatement = $pdo->prepare('SELECT * FROM posts WHERE user_id = :id');

$selectStatement->bindParam(':id', $_SESSION['user']['id'], PDO::PARAM_STR);

$selectStatement->execute();

$userPosts = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

?>

<?php if(isset($_SESSION['messages'])) : ?>
    <?php foreach($_SESSION['messages'] as $message) : ?>
        <div class="alert alert-success" role="alert">
          <?=  $message ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<?php if(isset($_GET['u'])): ?>
    <?php require __DIR__."/profile.php"; ?>
<?php else: ?>
    <?php require __DIR__.'/posts.php'; ?>
<?php endif; ?>
