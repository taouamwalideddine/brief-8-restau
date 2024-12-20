<?php
session_start();
?>
<nav id="menu" class="navbar navbar-default navbar-fixed-top">
  <div class="container"> 
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> 
        <span class="sr-only">Toggle navigation</span> 
        <span class="icon-bar"></span> 
        <span class="icon-bar"></span> 
        <span class="icon-bar"></span> 
      </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php" class="page-scroll">Main</a></li>
        <li><a href="menu.php" class="page-scroll">Menu</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php" class="page-scroll">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
