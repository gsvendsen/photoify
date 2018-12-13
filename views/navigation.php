<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><?php echo $config['title']; ?></a>

  <ul class="navbar-nav">
      <!-- If logged out -->
      <?php if (!isset($_SESSION['user'])): ?>

      <!-- If logged in -->
      <?php else: ?>
          <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Home
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="/">Home</a>
              <a class="dropdown-item" href="/profile.php">My Profile</a>
              <a class="dropdown-item" href="/app/users/logout.php">Log Out</a>
            </div>
          </div>
      <?php endif; ?>
  </ul><!-- /navbar-nav -->
</nav><!-- /navbar -->
