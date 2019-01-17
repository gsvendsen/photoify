<div class="form-wrapper">

    <img class="start-logo" src="assets/images/photoify.png">

    <form action="app/users/create.php" method="post">
        <div class="form-section">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
            <small>Please provide your email address.</small>
        </div><!-- /form-group -->

        <div class="form-section">
            <label for="username">Username</label>
            <input placeholder="Username..." type="text" name="username" required>
            <small>Please provide your username.</small>
        </div><!-- /form-group -->

        <div class="form-section">
            <label for="name">Name</label>
            <input placeholder="Name..." type="text" name="name" required>
            <small>Please provide your name.</small>
        </div><!-- /form-group -->

        <div class="form-section">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
            <small>Please provide your password. (min 8 chars)</small>
        </div><!-- /form-group -->

        <div class="form-center">
            <?php if(isset($_SESSION['error'])): ?>
              <p class="warning-message">
                <?= $_SESSION['error']['message'] ?>
             </p>
            <?php endif;?>
            <button type="submit">Create Account</button>
            <p>Already Have An Account? <a href="?q=login">Login</a>
        </div>
    </form>
</div>
<?php if(isset($_SESSION['error'])){ unset($_SESSION['error']) ;}; ?>
