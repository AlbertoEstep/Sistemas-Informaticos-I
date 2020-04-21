<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Film</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
  </head>

  <body>

    <div class="container">

      <header>
        <h1 class="PageTitle">Movies</h1>
      </header>


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
        <div class="detailItem">
          <?php
            if(isset($_GET['id'])){
              $id = $_GET['id'];
            }else{
              $_SESSION["error"] = "title_url";
              header("location: error.php");
            }
             $xml=simplexml_load_file("catalogo.xml") or die("Error: Cannot create object");
              $flag_encontrado = 0;
              foreach($xml->children() as $pelis) {
                if((string)$pelis['id'] == $id){
                   $nodo = $pelis;
                   $flag_encontrado = 1;
                }
              }
              if($flag_encontrado){
                echo "<h1 class=content-title>$nodo->titulo</h1>";
                echo "<img class = detailImg src=$nodo->detalle_poster alt=$nodo->titulo>";
                echo "<div class=descriptionmovie>";
                echo "<fieldset>";
                echo "<legend><b>Movie's description:</b></legend>";
                echo "<p>$nodo->descripcion<br><br></p>";
                echo "<ul class=detalles>";
                echo "<li><b>Director:</b> $nodo->director</li><br>";
                echo "<li><b>Category:</b> $nodo->categoria</li><br>";
                echo "<li><b>Year:</b> $nodo->anno</li><br>";
                echo "<li><b>Prize:</b> $nodo->precio&euro;</li><br>";
                echo "</u>";
                echo "</fieldset>";
                echo "<a class=buyImg href=shoppingcart.php?id=$id><img class=buyImg src=imagenes/buyImg.png alt=Add to Shopping Cart></a>";
                echo "</div>";

              }else {
                $_SESSION["error"] = "title_catalog";
                header("location: error.php");
              }
          ?>
          </div>
      </div>

      <footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

    </div>

  </body>
</html>
