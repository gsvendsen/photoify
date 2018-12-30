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

<h4>Your Posts:</h4>
<?php foreach($userPosts as $post): ?>
    <div class="card">
      <div class="card-body">
        <img class="col-10" src="<?= $post['img_path'] ?>"/>
        <p class="card-text"><?= $post['description'] ?></p>
        <a href="/app/posts/delete.php?locale=<?= $post['id'] ?>" class="btn btn-primary">Delete Post</a>
        <a href="/app/likes/update.php?post=<?= $post['id'] ?>&like=1" class="btn btn-primary">Like</a>
        <a href="/app/likes/update.php?post=<?= $post['id'] ?>&like=-1" class="btn btn-primary">Dislike</a>
        <?php

        // Fetching posts likes
        $selectStatement = $pdo->prepare('SELECT like FROM likes WHERE post_id = :post_id');

        $selectStatement->bindParam(':post_id', $post['id'], PDO::PARAM_STR);

        $selectStatement->execute();

        $likes = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

        $likeTotal = 0;

        // Adds up all likes and dislikes into likeTotal
        foreach ($likes as $like => $value) {
            $likeTotal += $value['like'];
        }

        ?>
        <p class="card-text"> Amount of likes: <?= $likeTotal ?></p>
      </div>
  </div>
<?php endforeach; ?>
