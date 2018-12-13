<h1 mx="auto"><?php echo $config['title']; ?></h1>

<?php if(isset($_GET['q'])): ?>
    <?php if($_GET['q'] == 'register'): ?>
        <?php require __DIR__.'/../register.php'; ?>
        <p>Already Have An Account? <a href="?q=login">Login</a>

    <?php elseif($_GET['q'] == 'login'): ?>
        <?php require __DIR__.'/../login.php'; ?>
        <p>Don't have an account? <a href="?q=register">Register</a>
    <?php endif; ?>
<?php else: ?>
    <?php require __DIR__.'/../login.php'; ?>
    <p>Don't have an account? <a href="?q=register">Register</a>
<?php endif; ?>
