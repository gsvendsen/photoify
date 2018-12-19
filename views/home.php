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

<h1><?php echo $config['title']; ?></h1>

<h3><?= "Welcome home, ".$_SESSION['user']['name']."."; ?></h3>
<p>This is the home page.</p>

<h4>Your Posts:</h4>
<?php foreach($userPosts as $post): ?>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Card title</h5>
        <p class="card-text"><?= $post['description'] ?></p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
      </div>
  </div>
<?php endforeach; ?>
