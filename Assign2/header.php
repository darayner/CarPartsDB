<?php
require_once('db_connect.php');
session_start();
?>

<html>
  <head>
    <title>Car Parts</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset = "UTF-8">
  </head>

  <body>
    <header>
      <nav class="headerNav">
        <img id="parts" src="carparts.png" alt="carparts">
        <div class="navbar">
          <h1 id = "carParts">Car Parts</h1>
          <?php
            if(isset($_SESSION['username'])){
              echo '<form action="logout.php" method="post" name="logout" id="logout">
                    <button type="submit" name="submit-logout" id="submit">Logout</button>
                    </form>';
                    echo "<p class='status'>Logged in as ". $_SESSION['username']."!</p>";
            }
            else{
              echo '<form action="login.php" method="post" name="login" id="login">
                <input type="text" name="username" placeholder="username">
                <input type="password" name="password" placeholder="password">
                <button type="submit" name="submit-login" id="submit">Login</button>
                </form>';
                echo "<p class='status'>Logged out: visitor</p>";
            }
          ?>
        </div>
      </nav>
    </header>
