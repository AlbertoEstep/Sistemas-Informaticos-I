<?php 
	session_start();
	$dbuser='alumnodb';
	$hostname='localhost';
	$dbname='si1';
	$customerid=$_GET['customerid'];
	
	function printTables ($bd, $c){
		print "<p>TABLA ORDERDETAIL</p>";
		$sql1 = "SELECT * FROM orderdetail, orders WHERE orderdetail.orderid = orders.orderid AND orders.customerid= '$c' limit 3";
		foreach($bd->query($sql1) as $row){
			print $row['orderid'] .'-'. $row['prod_id'] .'-'. $row['customerid'] . '<br/>';
		}
		
		print "<p>TABLA CUSTOMERS</p>";
		$sql2 = "Select * from customers where customerid = '$c'";
		foreach($bd->query($sql2) as $row){
			print $row['customerid'] .'-'. $row['firstname'] . '<br/>';
		}
		
		print "<p>TABLA ORDERS</p>";
		$sql2 = "Select * from orders where customerid = '$c'";
		foreach($bd->query($sql2) as $row){
			print $row['orderid'] .'-'. $row['customerid'] . '<br/>';
		}
	}
	
	if($customerid == null){
		echo '<form action="borraClienteMal.php" method="get">
				customerid: <input type="text" name="customerid"><br>
				pdo: <input type="radio" name="pdo"><br>
				commit: <input type="radio" name="commit"><br>
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
				
				print "<p>Despues de ejecutar</p>";
				printTables($db, $customerid);

				if (isset($_GET['commit'])) {
					$db->commit();	
					$db->beginTransaction();
				}

				$sql= "DELETE FROM customers WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
				
				print "<p>Aquí deberia haber dado fallo</p>";
				printTables($db, $customerid);

				$sql= "DELETE FROM orders WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
	
				$db->commit();		
			}
			catch(PDOException $e){
				echo $e->getMessage();
				$db->rollBack();
				print "<p>Despues de rollback</p>";
				printTables($db, $customerid);
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

				print "<p>Despues de ejecutar</p>";
				printTables($db, $customerid);
		
				if (isset($_GET['commit'])) {
					$db->exec(COMMIT);
					$db->exec(BEGIN);
				}
				$sql= "DELETE FROM customers WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
				
				print "<p>Aquí deberia haber dado fallo</p>";
				printTables($db, $customerid);
				
				$sql= "DELETE FROM orders WHERE customerid= '$customerid'";
				$resultado = $db->exec($sql);
	
				$db->exec(COMMIT);		
			}
			catch(PDOException $e){
				echo $e->getMessage();
				$db->exec(ROLLBACK);
				print "<p>Despues de rollback</p>";
				printTables($db, $customerid);
			}	

		}

	}
?>
