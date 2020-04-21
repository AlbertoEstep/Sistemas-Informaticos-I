<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Movies</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
      $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#pelis li").each(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });

    $(document).ready(function(){
      setInterval(
        function(){
          var $random = Math.floor(Math.random()*(1000-1+1)+1);
          $("#conectadas").text("Personas conectadas: "+$random);
        },3000);
    });

    </script>
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

        <section>
          <div id="catalogo">
            <h1 class="content-title">Movies Catalog</h1>

            Search by title: <input id="search" type="text" placeholder="Search...">
            <form action="index.php" method="GET">
              Search by category:
              <select name="category">
                <option value="" selected></option>
                <option value="Science Fiction">Science fiction</option>
                <option value="Action">Action</option>
                <option value="Horror">Horror</option>
                <option value="Martial Arts">Martial arts</option>
                <option value="Adventure">Adventure</option>
                <option value="Comedy">Comedy</option>
                <option value="Romance">Romance</option>
                <option value="Documentary">Documentary</option>
                <option value="Animation">Animation</option>
                <option value="Drama">Drama</option>
                <option value="Thriller">Thriller</option>
              </select>
              <input type="submit" value="Search">
            </form>

            <ul class="galeria">
              <div id=pelis>
              <?php
                $xml=simplexml_load_file("catalogo.xml") or die("Error: Cannot create object");
                $categoria="";
                if(isset($_GET['category'])){
                  $categoria=$_GET['category'];
                }
                else {
                  $categoria="";
                }
                if ($categoria==""){
                  foreach($xml->children() as $pelis) {
                      echo "<li><a href=detailitem.php?id=$pelis[id]><img src = $pelis->poster alt=$pelis->titulo><p>$pelis->titulo</p></a></li>";
                  }
                }
                else {
                  foreach($xml->children() as $pelis) {
                    if($categoria == $pelis->categoria){
                      echo "<li><a href=detailitem.php?id=$pelis[id]><img src = $pelis->poster alt=$pelis->titulo><p>$pelis->titulo</p></a></li>";
                    }
                  }
                }
              ?>
              </div>
            </ul>

            <p id="conectadas"></p>

          </div>
        </section>
      </div>

      <footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

    </div>

  </body>
</html>
