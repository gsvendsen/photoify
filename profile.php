<?php require __DIR__.'/views/header.php'; ?>


<?php if(!isset($_SESSION['user'])){ redirect("/"); } else { $user = $_SESSION['user'];}?>
<div class="profile-update-container">
    <h4>Update Information</h4>

    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']['message'] ?>
      </div>
    <?php endif;?>

    <form action="app/users/update.php" method="post" enctype="multipart/form-data">
        <div class=" form-section">
            <label for="email">Email</label>
            <input type="email" value="<?= $user['email'] ?>" name="email" placeholder="francis@darjeeling.com">
        </div><!-- / form-section -->

        <div class="form-section">
            <label for="name">Name</label>
            <input type="text" value="<?= $user['name'] ?>" name="name">
        </div><!-- / form-section -->


        <div class="form-section">
            <label for="username">Username</label>
            <input type="text" value="<?= $user['username'] ?>" name="username" placeholder="Username">
        </div><!-- / form-section -->

        <div class="form-section">
            <label for="newPassword">New Password</label>
            <input type="password" name="newPassword" placeholder="New Password">
        </div><!-- / form-section -->

        <div class="form-section">
            <label for="password">Old Password</label>
            <input type="password" name="password" placeholder="Old Password">
        </div><!-- / form-section -->

        <div class="form-section">
            <label for="profile-picture">Update Profile Picture</label>
            <input type="file" name="profile-picture">
        </div><!-- / form-section -->


        <div class="form-section">
            <label for="banner-picture">Update Profile Banner</label>
            <input type="file" name="banner-picture">
        </div><!-- / form-section -->

        <button type="submit">Update Information</button>
    </form>

    <div class="alert-danger delete-account-button" data-id="<?= $_SESSION['user']['id'] ?>"role="button" aria-pressed="true">Delete Account</div>
</div>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>
