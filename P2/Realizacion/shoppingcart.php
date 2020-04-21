<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<title>Movies ShoppingCart</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
	</head>

	<body>

		<div class="container">

			<header>
				<h1 class="PageTitle">Movies</h1>
			</header>

			<h1 class="content-title">Shopping Cart</h1>
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
					if(!isset($_SESSION['cart'])) $_SESSION['cart'] = array();

					if(isset($_GET['id'])){
		              $id = $_GET['id'];

		              if(!in_array($id, $_SESSION['cart'])) array_push($_SESSION['cart'], $id);
		            }
				?>
				<table>
					<tr>
						<th>Product</th>
						<th>Price</th>
					</tr>

					<?php
						$total = 0;
						$arlength = count($_SESSION['cart']);
						$xml=simplexml_load_file("catalogo.xml") or die("Error: Cannot create object");
		              	$flag_encontrado = 0;
						for($i = 0; $i < $arlength; $i++){
							foreach($xml->children() as $pelis) {
			                if((string)$pelis['id'] == $_SESSION['cart'][$i]){
				                   $nodo = $pelis;
				                   $flag_encontrado = 1;
			                	}
			              	}
			              	if($flag_encontrado){
			              		echo "<tr>
									<td>$nodo->titulo</td>
									<td class=price>$nodo->precio&euro;</td>
								</tr>";
								$total += (float)$nodo->precio;
			              	}
						}
						echo "<tr class=total>
							<td>Total:</td>
							<td class=price>$total&euro;</td>
						</tr>";
						$_SESSION['buyprice'] = $total;
					?>
				</table>



				<?php
					if(isset($_SESSION['login']) && $_SESSION['login'] == 1
						&& isset($_COOKIE['usuario'])){

						$directory = './usuarios/'.$_COOKIE['usuario'].'/datos.dat';
						if(file_exists($directory)==true){
							if(($file = fopen($directory,'r'))!=false){
								$i = 0;
								while($i < 6){
									$bufer=fgets($file);// obtener saldo
									$i += 1;
								}
								echo "<p class=saldo>Su saldo es: $bufer</p> </br>";
								if($total > $bufer){
									echo "<form action=buy.php method=post class='center'>
											<input type=submit name=cancel value=Cancel>
											<input type=submit name=clear value=Clear>
										</form>";
									echo "<br><p class=insuficiente>Saldo insuficiente</p>";
								}else{
									echo "<form action=buy.php method=post class='center'>
											<input type=submit name=cancel value=Cancel>
											<input type=submit name=clear value=Clear>
											<input type=submit name=buy value=Buy!>
										</form>";
								}
							}
						}else{
							closedir($directory);
				            $_SESSION['error']='file';
				            header('Location: error.php');
						}
					}
				?>
			</div>

			<footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>
		</div>
	</body>

</html>
