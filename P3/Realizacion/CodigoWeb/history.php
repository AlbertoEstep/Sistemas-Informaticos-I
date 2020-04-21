<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<title>Movies history</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
	</head>

	<body>

		<div class="container">

			<header>
				<h1 class="PageTitle">Movies</h1>
			</header>

			<h1 class="content-title">Shopping History</h1>
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
				<table>
					<tr>
						<th>Product</th>
						<th>Price</th>
						<th>Bought Date</th>
					</tr>

					<?php
					#es un usuario loggeado
				        if(!isset($_SESSION['login'])){
				          $_SESSION["error"] = "user";
				          header("location: error.php");
				        }

				        $path = "usuarios/".$_COOKIE['usuario']."/historial.xml";
				        $directory = 'usuarios/'.$_COOKIE['usuario'].'/datos.dat';

				        if(file_exists($directory)==true){
									if(($file = fopen($directory,'r'))!=false){
										$i = 0;
										while($i < 6){
											$bufer=fgets($file);// obtener saldo
											$i += 1;
										}
										echo "<p class=saldo>Su saldo es: $bufer&euro;</p> </br>";
										$_SESSION['balance'] = $bufer;
										echo "<form action=buy.php method=post>
												Add more balance
												<select name=balanceToAdd>
													<option value=5 selected>5</option>
													<option value=10>10</option>
													<option value=20>20</option>
													<option value=50>50</option>
													<option value=100>100</option>
												</select>
												<input type=submit name=addMoney value='Add Money'>
											</form>";
									}
								}else{
									closedir($directory);
			            $_SESSION['error']='file';
			            header('Location: error.php');
								}

						$xml=simplexml_load_file($path) or die(" </br> No items bought yet");

						foreach($xml->children() as $producto) {
	                    	echo "<tr>
		                    	<td>$producto->titulo</td>
		                    	<td>$producto->precio&euro;</td>
		                    	<td>$producto->fecha</td>
		                    	</tr>";
	                  	}

					?>
				</table>
			</div>

		<footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>
		</div>
	</body>

</html>
