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
            <?php
            	include 'nav.php';
							navegation();
            ?>
				</nav>
			</div>

			<div class="content">
				<form action="loginL.php" class='center' method="POST">
					<fieldset class="login">
						<legend>Personal Information:</legend>
          	User:<br>
            <input type="text" name="name" placeholder="<?php echo $_COOKIE['usuario']?>" required>
            <br>
          	Password:<br>
            <input type="password" name="password" required>
            <br>
          	<input type="submit" value="Aceptar">
					</fieldset>
				</form>
			</div>

			<footer>Copyright &copy; <?php echo date("d/m/Y H:i:s"); ?></footer>

		</div>

	</body>

</html>
