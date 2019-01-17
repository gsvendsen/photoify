<div class="form-wrapper">

    <img class="start-logo" src="assets/images/photoify.png">

    <form action="app/users/login.php" method="post">
        <div class="form-section">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="example@mail.com" value="<?php if(isset($_SESSION['error'])){ echo $_SESSION['error']['email'];} ?>" required>
            <small>Please provide your email address.</small>
        </div><!-- /form-group -->

        <div class="form-section">
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <small>Please provide your password.</small>
        </div><!-- /form-group -->

        <div class="form-center">
            <?php if(isset($_SESSION['error'])): ?>
              <p class="warning-message">
                <?= $_SESSION['error']['message'] ?>
             </p>
            <?php endif;?>
            <button type="submit">Sign In</button>
            <p>Don't have an account? <a href="?q=register">Register</a>
        </div>
    </form>
</div>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>
