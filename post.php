<?php require __DIR__.'/views/header.php'; ?>


<?php if(!isset($_SESSION['user'])){ redirect("/"); } else { $user = $_SESSION['user'];}?>
<div class="new-post-container">
    <h1>New Post</h1>

    <img class="post-image" id='output'>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']['message'] ?>
      </div>
    <?php endif;?>

    <form action="app/posts/create.php" method="post" enctype="multipart/form-data">
        <div class="form-section">
            <label for="image">Image</label>
            <input onchange='openFile(event)' type="file" name="image">
        </div><!-- /form-group -->

        <div class="form-section">
            <label for="description">Description</label>
            <textarea placeholder="Image description..." type="text" name="description"></textarea>
        </div><!-- /form-group -->

        <button type="submit">Upload new Post</button>
    </form>
</div>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>
