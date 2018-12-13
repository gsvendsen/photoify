
<article>
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']['message'] ?>
      </div>
    <?php endif;?>

    <form action="app/users/create.php" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" placeholder="example@mail.com" required>
            <small class="form-text text-muted">Please provide the your email address.</small>
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" required>
            <small class="form-text text-muted">Please provide the your name.</small>
        </div><!-- /form-group -->

        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" type="password" name="password" required>
            <small class="form-text text-muted">Please provide the your password.</small>
        </div><!-- /form-group -->

        <button type="submit" class="btn btn-primary">Create Account</button>
    </form>

</article>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>

<?php require __DIR__.'/views/footer.php'; ?>
