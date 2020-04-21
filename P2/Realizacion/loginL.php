<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <title>Movies login</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
  </head>

  <body>

    <div class="container">

      <header>
        <h1 class="PageTitle">Movies</h1>
      </header>

      <h1 class="content-title">Login</h1>
      <div class="navegacion">
        <nav>
          <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="shoppingcart.php">Shopping cart</a></li>
          </ul>
        </nav>
      </div>

      <div class="content">
        <?php
          session_start();
          $name=$_POST['name'];
          $password=$_POST['password'];
          $directory = './usuarios/'.$name.'/datos.dat';
          if(file_exists($directory)==true){
            if(($file = fopen($directory,'r'))!=false){
              $bufer=fgets($file);//name
              $bufer=fgets($file);//contraseña

              if((strcmp($bufer, md5($password)."\n"))==0){
                $_SESSION['login']=1;
                if(!isset($_COOKIE['usuario'])) {
								    setcookie('usuario', $name, time() + (86400 * 30), "/");
								} else {
                  if($_COOKIE['usuario'] != $name){
                    setcookie('usuario', $name, time() + (86400 * 30), "/");
                  }
								}
              	closedir($directory);
                header('Location: index.php');
              }
              else{
              	$_SESSION['error']='password';
              	closedir($directory);
              	header('Location: error.php');
              }
            }
          }else if(file_exists($directory)!=true){
            closedir($directory);
            $_SESSION['error']='login';
            header('Location: error.php');
          }
        ?>
      </div>

      <footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

    </div>

  </body>

</html>
