<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Error Screen</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
  </head>

  <body>

    <div class="container">

      <header>
        <h1 class="PageTitle">Movies</h1>
      </header>

      <h1 class="content-title">Successful Purchase</h1>
      <div class="navegacion">
        <nav>
          <ul>
            <?php
              include 'nav.php';
              navegation();
            ?>
          </ul>
        </nav>
      </div>

      <div class="content">
        <?php
          print("Thank you! Successful puchase.\n");
          echo "<a href=\"index.php\"><br>Index</a>";
        ?>
      </div>

      <footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

    </div>

  </body>

</html>