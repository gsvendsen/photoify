<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><?php echo $config['title']; ?></a>

  <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == "/index.php" || $_SERVER['REQUEST_URI'] == "/"){echo "active";} ?>" href="/index.php">Home</a>
      </li><!-- /nav-item -->
      <!-- If logged out -->
      <?php if (!isset($_SESSION['user'])): ?>
        <li class="nav-item">
          <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == "/login.php"){echo "active";} ?>" href="/login.php">Log In</a>
        </li><!-- /nav-item -->

        <li class="nav-item">
          <a class="nav-link <?php if($_SERVER['REQUEST_URI'] == "/register.php"){echo "active";} ?>" href="/register.php">Create Account</a>
        </li><!-- /nav-item -->
      <!-- If logged in -->
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="/profile.php">My Profile</a>
        </li><!-- /nav-item -->

        <li class="nav-item">
          <a class="nav-link" href="/app/users/logout.php">Log Out</a>
        </li><!-- /nav-item -->
      <?php endif; ?>
  </ul><!-- /navbar-nav -->
</nav><!-- /navbar -->
