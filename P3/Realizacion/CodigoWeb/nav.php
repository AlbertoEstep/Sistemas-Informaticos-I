<?php
	function navegation(){
		if(isset($_SESSION["login"]) && $_SESSION["login"] == 1){
        echo "<li><a href=index.php><img src=imagenes/home.png alt=user></a></li>";
        echo "<li><a href=salir.php>Salir</a></li>";
        echo "<li><a href=shoppingcart.php>Shopping cart</a></li>";
        echo "<li><a href=history.php>History</a></li>";
      }else{
        $_SESSION["login"] = 0;
        echo "<li><a href=index.php><img src=imagenes/home.png alt=user></a></li>";
        echo "<li><a href=login.php>Login</a></li>";
        echo "<li><a href=register.php>Register</a></li>";
        echo "<li><a href=shoppingcart.php>Shopping cart</a></li>";
      }
	}
?>
