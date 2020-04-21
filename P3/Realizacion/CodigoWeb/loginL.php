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
          // Base de datos
          try{
			  $dbh = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb","alumnodb"); 
		  }catch(PDOException $e){
			  echo $e->getMessage();
		  }
		  $stmt = $dbh->prepare("SELECT password FROM customers WHERE username = :username");
      $stmt->bindParam(':username',$name, PDO::PARAM_STR);
      $stmt->execute();
          
      $result = $stmt->fetchAll();
		  if(strcmp($password,$result[0][0]) == 0){
				$_SESSION['login']=1;
				if(!isset($_COOKIE['usuario'])) {
					  setcookie('usuario', $name, time() + (86400 * 30), "/");
				}else {
					  if($_COOKIE['usuario'] != $name){
						setcookie('usuario', $name, time() + (86400 * 30), "/");
					  }
				}
				$dbh = null;
				header('Location: index.php');
		  }else{
				$_SESSION['error']='password';
				$dbh = null;
				header('Location: error.php');
		  }
		$dbh = null;
        ?>
      </div>

      <footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

    </div>

  </body>

</html>
