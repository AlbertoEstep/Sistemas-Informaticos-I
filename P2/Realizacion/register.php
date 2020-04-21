<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<title>Movies register</title>
		<link rel="stylesheet" type="text/css" href="estilos.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>

		<script>
		var numeros="0123456789";
		var letras="abcdefghyjklmnñopqrstuvwxyz";
		var letras_mayusculas="ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";

		function tiene_numeros(texto){
		   for(i=0; i<texto.length; i++){
		      if (numeros.indexOf(texto.charAt(i),0)!=-1){
		         return 1;
		      }
		   }
		   return 0;
		}

		function tiene_letras(texto){
		   texto = texto.toLowerCase();
		   for(i=0; i<texto.length; i++){
		      if (letras.indexOf(texto.charAt(i),0)!=-1){
		         return 1;
		      }
		   }
		   return 0;
		}

		function tiene_minusculas(texto){
		   for(i=0; i<texto.length; i++){
		      if (letras.indexOf(texto.charAt(i),0)!=-1){
		         return 1;
		      }
		   }
		   return 0;
		}

		function tiene_mayusculas(texto){
		   for(i=0; i<texto.length; i++){
		      if (letras_mayusculas.indexOf(texto.charAt(i),0)!=-1){
		         return 1;
		      }
		   }
		   return 0;
		}

		function seguridad_clave(clave){
			var seguridad = 0;
			if (clave.length!=0){
				if (tiene_numeros(clave) && tiene_letras(clave)){
					seguridad += 30;
				}
				if (tiene_minusculas(clave) && tiene_mayusculas(clave)){
					seguridad += 30;
				}
				if (clave.length >= 4 && clave.length <= 5){
					seguridad += 10;
				}else{
					if (clave.length >= 6 && clave.length <= 8){
						seguridad += 30;
					}else{
						if (clave.length > 8){
							seguridad += 40;
						}
					}
				}
			}
			return seguridad;
		}

		function muestra_seguridad_clave(password,formulario){
			seguridad=seguridad_clave(password);
			formulario.seguridad.value=seguridad + "%";
		}

		function validacion() {
			var cla1 = document.form.password.value;
			var cla2 = document.form.passwordC.value;
			if (cla1 != cla2) {
				alert ("Las claves introducidas no son iguales");
				return false;
			}
			else {
				return true;
      }
		}

		</script>
	</head>

	<body>

		<div class="container">

			<header>
				<h1 class="PageTitle">Movies</h1>
			</header>

			<h1 class="content-title">Register</h1>
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
					if (isset($_POST['submit'])) {
						$name=$_POST['name'];
						$password=$_POST['password'];
						$passwordC=$_POST['passwordC'];
						$email=$_POST['email'];
						$phone=$_POST['phone'];
						$creditCard=$_POST['creditCard'];
						$directory="./usuarios";
						$saldo = rand(0,100);
						if (is_dir($directory)==TRUE){
							if (is_dir($directory.'/'.$name)==TRUE ){
								printf("User already exists, log in");
						      		echo "<a href=\"login.php\"><br>Login</a>";
							}
							else{
								$directory=$directory.'/'.$name;
								mkdir($directory, 0777,true);
								opendir($directory);
						    	$directory=$directory.'/datos.dat';
								$file=fopen($directory,"w");
								fprintf($file, "%s\n", $name);
								fprintf($file, "%s\n", md5($password));
								fprintf($file, "%s\n", $email);
						   	fprintf($file, "%s\n", $phone);
								fprintf($file, "%s\n", $creditCard);
								fprintf($file, "%s\n", $saldo);
								$_SESSION['login'] = 1;
								if(!isset($_COOKIE['usuario'])) {
								    setcookie('usuario', $name, time() + (86400 * 30), "/");
								} else {
									if($_COOKIE['usuario'] != $name){
										setcookie('usuario', $name, time() + (86400 * 30), "/");
									}
								}
								fclose($file);
						    chmod($directory, 0777);
						    header('Location: index.php');
							}
						}
						else{
							$directory=$directory.'/'.$name;
							if(!mkdir($directory, 0777, true)) {// LA CARPETA PADRE TIENE QUE TENER PERMISOS DE ESCRITURA PARA OTROS.
							    die('Fallo al crear las carpetas, dale permisos a la carpeta original...');
							}
							opendir($directory);
							$directory=$directory.'/datos.dat';
							$file=fopen($directory,"w");
							fprintf($file, "%s\n", $name);
							fprintf($file, "%s\n", md5($password));
							fprintf($file, "%s\n", $email);
							fprintf($file, "%s\n", $phone);
							fprintf($file, "%s\n", $creditCard);
							fprintf($file, "%s\n", $saldo);
							$_SESSION['login'] = 1;
							if(!isset($_COOKIE['usuario'])) {
									setcookie('usuario', $name, time() + (86400 * 30), "/");
							} else {
								if($_COOKIE['usuario'] != $name){
									setcookie('usuario', $name, time() + (86400 * 30), "/");
								}
							}
							fclose($file);
							chmod($directory, 0777);
							header('Location: index.php');
						}
					}
					else{
				?>
				<fieldset class="register">
					<legend>Personal Information:</legend>
					<form name='form' action="" method="POST" class='center' onSubmit="return validacion()">

						User nickname:<br>
						<input type="text" name="name" pattern=".{6,}" title="Six or more characters" required>
						<br>
						Password:<br>
						<input type="password" name="password" pattern=".{6,}" title="Six or more characters" onkeyup="muestra_seguridad_clave(this.value, this.form)" required>
						<br>
						<i>seguridad:</i> <input name="seguridad" type="text" onfocus="blur()">
						<br>
						Password confirmation:<br>
						<input type="password" name="passwordC" required>
						<br>
						email:<br>
						<input type="email" name="email" required>
						<br>
						Phone number:<br>
						<input type="text" name="phone" pattern="[0-9]{9}$" title="9 numbers" required>
						<br>
						Credit card:<br>
						<input type="text" name="creditCard" pattern="[0-9]{16}$" title="16 numbers" required>
						<br>
						<input type="submit" name="submit" value="Aceptar">
					</form>
				</fieldset>
				<?php
				}
				?>
			</div>

			<footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

		</div>

	</body>

</html>
