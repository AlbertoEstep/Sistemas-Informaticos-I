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

      <h1 class="content-title">Error Screen</h1>
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
          if( isset($_GET['debug'])){
             print("Session closed.\n");
            echo "<a href=\"index.php\"><br>Index</a>";
            session_unset();
            session_destroy();
          }

          $error=$_SESSION['error'];
          if(strcmp($error, 'login')==0){
            print("The user does not exist, log in again.\n");
            echo "<a href=\"login.php\"><br>Login</a>";
          }
          else if(strcmp($error,'password')==0){
            print("Incorrect password, retry again.\n");
            echo "<a href=\"login.php\"><br>Login</a>";
          }
          else if(strcmp($error,'variables_price')==0){
            print("Failed in price variables.\n");
            echo "<a href=\"index.php\"><br>Index</a>";
          }
          else if(strcmp($error,'saldo')==0){
            print("Insufficient amount of balance.\nRecharge in your history page.\n");
            echo "<a href=\"shoppingcart.php\"><br>Back</a>";
            echo "<a href=\"history.php\"><br>Recharge</a>";
          }
          else if(strcmp($error,'title_url')==0){
            print("Title not reconogsized(url).\n");
            echo "<a href=\"index.php\"><br>Index</a>";
          }
          else if(strcmp($error,'title_catalog')==0){
            print("Title not reconogsized(catalog).\n");
            echo "<a href=\"index.php\"><br>Index</a>";
          }
          else if(strcmp($error,'cart')==0){
            print("Cart error.\n");
            echo "<a href=\"index.php\"><br>Index</a>";
          }
          else if(strcmp($error,'user')==0){
            print("You need to be registered in order to buy our products.\n");
            echo "<a href=\"index.php\"><br>Index</a>";
          }
          else if(strcmp($error,'file')==0){
            print("Error accesing user information.\n");
            echo "<a href=\"index.php\"><br>Index</a>";
          }
        ?>
      </div>

      <footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

    </div>

  </body>

</html>
