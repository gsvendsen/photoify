<?php require __DIR__.'/views/header.php'; ?>

<article>

    <?php
    // If user is logged in
    if(isset($_SESSION['user'])):  ?>

    <?php require __DIR__.'/views/home.php'; ?>

    <?php
    //If user is not logged in
    else: ?>
        <?php require __DIR__.'/views/intro.php'; ?>
    <?php endif; ?>
</article>

<?php unset($_SESSION['messages']); ?>

<?php require __DIR__.'/views/footer.php'; ?>
