
<div class="form-wrapper">
    <?php if(isset($_SESSION['error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error']['message'] ?>
      </div>
    <?php endif;?>

    <h2>Log In</h2>

    <form action="app/users/login.php" method="post">
        <div class="form-section">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="example@mail.com" value="<?php if(isset($_SESSION['error'])){ echo $_SESSION['error']['email'];} ?>" required>
            <small>Please provide your email address.</small>
        </div><!-- /form-group -->

        <div class="form-section">
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <small>Please provide your password (passphrase).</small>
        </div><!-- /form-group -->

        <div class="form-center">
            <button type="submit">Sign In</button>
            <p>Don't have an account? <a href="?q=register">Register</a>
        </div>
    </form>
</div>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>
