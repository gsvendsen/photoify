<?php require __DIR__.'/views/header.php'; ?>


<?php if(!isset($_SESSION['user'])){ redirect("/"); } else { $user = $_SESSION['user'];}?>
<div class="new-post-container">
    <h1>Update Information</h1>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']['message'] ?>
      </div>
    <?php endif;?>

    <form action="app/users/update.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" value="<?= $user['email'] ?>" name="email" placeholder="francis@darjeeling.com">
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" value="<?= $user['name'] ?>" name="name">
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="profile-picture">Update Profile Picture</label>
            <input class="form-control" type="file" name="profile-picture">
        </div><!-- /form-group -->


        <div class="form-group">
            <label for="banner-picture">Update Profile Banner</label>
            <input class="form-control" type="file" name="banner-picture">
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" type="text" name="username" placeholder="Username">
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="newPassword">New Password</label>
            <input class="form-control" type="password" name="newPassword" placeholder="New Password">
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="password">Old Password</label>
            <input class="form-control" type="password" name="password" placeholder="Old Password">
        </div><!-- /form-group -->

        <button type="submit" class="btn btn-primary">Update Information</button>
    </form>

    <a href="/app/users/delete.php" class="btn btn-primary mt-1 alert-danger" role="button" aria-pressed="true">Delete Account</a>
</div>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>
