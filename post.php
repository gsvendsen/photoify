<?php require __DIR__.'/views/header.php'; ?>


<?php if(!isset($_SESSION['user'])){ redirect("/"); } else { $user = $_SESSION['user'];}?>
<article>
    <h1>New Post</h1>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']['message'] ?>
      </div>
    <?php endif;?>

    <form action="app/posts/create.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Image</label>
            <input class="form-control" type="file" name="image">
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" type="text" name="description"></textarea>
        </div><!-- /form-group -->

        <button type="submit" class="btn btn-primary">Upload new Post</button>
    </form>
</article>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>