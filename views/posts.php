<h4>Your Posts:</h4>
<?php foreach($userPosts as $post): ?>

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

        // Fetching posts likes
        $selectStatement = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');

        $selectStatement->bindParam(':user_id', $post['user_id'], PDO::PARAM_STR);

        $selectStatement->execute();

        $postUser = $selectStatement->fetch(PDO::FETCH_ASSOC);

        $likeTotal = 0;
        // Adds up all likes and dislikes into likeTotal
        foreach ($likes as $like => $value) {
            $likeTotal += $value['like'];
        }
    ?>
    <div class="post-container">
      <div class="post">
        <img class="post-image" src="<?= $post['img_path'] ?>"/>
        <p><?= $post['description'] ?></p>
        <img class="post-profile" src="<?= $postUser['image_path'] ?>"/>
        <p><?= $postUser['name'] ?></p>

        <a href="/../edit.php?post=<?= $post['id'] ?>" class="btn btn-primary">Edit Post</a>
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

        <p> Amount of likes: <?= $likeTotal ?></p>
    </div><!-- /post -->
  </div><!-- /post-container -->
<?php endforeach; ?>
