<?php require __DIR__.'/views/header.php'; ?>


<?php if(!isset($_SESSION['user'], $_GET['post'])){ redirect("/"); } else { $user = $_SESSION['user'];}?>


<?php


$postId = filter_var($_GET['post'], FILTER_SANITIZE_STRING);

// Fetching logged in users posts
$selectStatement = $pdo->prepare('SELECT * FROM posts WHERE id = :post_id');


$selectStatement->bindParam(':post_id', $postId, PDO::PARAM_STR);

$selectStatement->execute();

$editPost = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

if(!$editPost){
    $_SESSION['messages'][] = "Post you tried to edit does not exist!";
    redirect("/");
}

if($editPost[0]['user_id'] !== $_SESSION['user']['id']){
    $_SESSION['messages'][] = "You do not have permission to edit that post!";
    redirect("/");
}

echo $editPost[0]['description'];
?>
<article>
    <h1>Edit Post</h1>

    <form action="app/posts/update.php?post=<?= $editPost[0]['id'] ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" type="text" name="description"><?= $editPost[0]['description'] ?></textarea>
        </div><!-- /form-group -->

        <button type="submit" class="btn btn-primary">Upload new Post</button>
    </form>
</article>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>
