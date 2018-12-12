<?php require __DIR__.'/views/header.php'; ?>

<article>
  <?php if (isset($_SESSION['messages'])): ?>
    <?php foreach ($_SESSION['messages'] as $message): ?>
      <div class="alert alert-success" role="alert">
        <?= $message ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
    <h1><?php echo $config['title']; ?></h1>
    <h3><?php if(isset($_SESSION['user'])){ echo "Welcome, ".$_SESSION['user']['name']."!";} ?></h3>
    <p>This is the home page.</p>
</article>

<?php unset($_SESSION['messages']); ?>

<?php require __DIR__.'/views/footer.php'; ?>
