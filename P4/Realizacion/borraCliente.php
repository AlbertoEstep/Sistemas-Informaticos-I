<?php 
	session_start();
	$dbuser='alumnodb';
	$hostname='localhost';
	$dbname='si1';
	$customerid=$_GET['customerid'];
	
	function printTables ($bd, $c){
		print "<p>TABLA ORDERS & ORDERDETAIL</p>";
		$sql1 = "SELECT * FROM orderdetail, orders WHERE orderdetail.orderid = orders.orderid AND orders.customerid= '$c' limit 3";
		foreach($bd->query($sql1) as $row){
			print $row['orderid'] .'-'. $row['prod_id'] .'-'. $row['customerid'] . '<br/>';
		}
		
		print "<p>TABLA CUSTOMERS</p>";
		$sql2 = "Select * from customers where customerid = '$c'";
		foreach($bd->query($sql2) as $row){
			print $row['customerid'] .'-'. $row['firstname'] . '<br/>';
		}
	}
	
	if($customerid == NULL){
		echo '<form action="borraCliente.php" method="get">
				customerid: <input type="text" name="customerid"><br>
				pdo: <input type="radio" name="pdo"><br>
				<input type="submit" value="Submit">
			</form>';
	}
	else{
		if (isset($_GET['pdo'])) {
			try{
				$db = new PDO("pgsql:dbname=$dbname; host=$hostname",$dbuser);
				$db->beginTransaction();
				$db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				print "<p>Antes de ejecutar</p>";
				printTables($db, $customerid);
				$sql= "DELETE FROM orderdetail USING orders WHERE orderdetail.orderid = orders.orderid AND orders.customerid= '$customerid';";
				$resultado = $db->exec($sql);
				if ($resultado===FALSE || ($resultado===0 && $db->errorCode()!="00000")){
					echo 'Error en DELETE ORDERDETAIL';
				}

				$sql= "DELETE FROM orders WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
				if ($resultado===FALSE || ($resultado===0 && $db->errorCode()!="00000")){
					echo 'Error en DELETE ORDERS';
				}	
				$sql= "DELETE FROM customers WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
				if ($resultado===FALSE || ($resultado===0 && $db->errorCode()!="00000")){
					echo 'Error en DELETE CUSTOMERID';
				}
				$db->commit();	
				print "<p>Despues de ejecutar</p>";
				printTables($db, $customerid);
			}
			catch(Exception $e){
				print	"Un error<p>";
				echo $e->getMessage();
				$db->rollback();
				print	"<p>";
			}	
		}
		else{
			try{
				$db = new PDO("pgsql:dbname=$dbname; host=$hostname",$dbuser);
				$db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$db->exec(BEGIN);
				print "<p>Antes de ejecutar</p>";
				printTables($db, $customerid);
				$sql= "DELETE FROM orderdetail USING orders WHERE orderdetail.orderid = orders.orderid AND orders.customerid= '$customerid';";
				$resultado = $db->exec($sql);
				if ($resultado===FALSE || ($resultado===0 && $db->errorCode()!="00000")){
					echo 'Error en DELETE ORDERDETAIL';
				}
				$sql= "DELETE FROM orders WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
				if ($resultado===FALSE || ($resultado===0 && $db->errorCode()!="00000")){
					echo 'Error en DELETE ORDERS';
				}	
				$sql= "DELETE FROM customers WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
				if ($resultado===FALSE || ($resultado===0 && $db->errorCode()!="00000")){
					echo 'Error en DELETE CUSTOMERID';
				}	
				$db->exec(COMMIT);	
				print "<p>Despues de ejecutar</p>";
				printTables($db, $customerid);	
			}
			catch(Exception $e){
				print	"Un error<p>";
				echo $e->getMessage();
				$db->exec(ROLLBACK);
				print	"<p>";
			}	
		}
	}	
?>
