<?php if(isset($_GET['q'])): ?>
    <?php if($_GET['q'] == 'register'): ?>
        <?php require __DIR__.'/../register.php'; ?>
    <?php elseif($_GET['q'] == 'login'): ?>
        <?php require __DIR__.'/../login.php'; ?>
    <?php else: ?>
        <?php require __DIR__.'/../login.php'; ?>
    <?php endif; ?>
<?php else: ?>
    <?php require __DIR__.'/../login.php'; ?>
<?php endif; ?>
