<?php require __DIR__.'/views/header.php'; ?>


<?php if(!isset($_SESSION['user'], $_GET['post'])){ redirect("/"); } else { $user = $_SESSION['user'];}?>


<?php


$postId = filter_var($_GET['post'], FILTER_SANITIZE_STRING);

// Fetching logged in users posts
$selectStatement = $pdo->prepare('SELECT * FROM posts WHERE id = :post_id');


$selectStatement->bindParam(':post_id', $postId, PDO::PARAM_STR);

$selectStatement->execute();

$editPost = $selectStatement->fetch(PDO::FETCH_ASSOC);

if(!$editPost){
    $_SESSION['messages'][] = "Post you tried to edit does not exist!";
    redirect("/");
}

if(intval($editPost['user_id']) !== intval($_SESSION['user']['id'])){
    $_SESSION['messages'][] = "You do not have permission to edit that post!";
    redirect("/");
}

?>
<article>

    <img class="post-image" src="<?=$editPost['img_path'] ?>"/>

    <form action="app/posts/update.php?post=<?= $editPost['id'] ?>" method="post" enctype="multipart/form-data">
        <div class="form-section">
            <label for="description">Description:</label>
            <textarea type="text" name="description"><?= $editPost['description'] ?></textarea>
        </div><!-- /form-group -->

        <a href="/">Go Back</a>
        <button type="submit">Update Post</button>
    </form>
</article>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>
