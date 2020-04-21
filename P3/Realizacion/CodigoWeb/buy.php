<?php
	// Start the session
	session_start();
    if (isset($_POST['cancel'])) {
        header("location: index.php");
    }
    elseif (isset($_POST['clear'])) {
    	$_SESSION['cart'] = array();
        header("location: shoppingcart.php");
    }
    #caso de añadir saldo
    elseif (isset($_POST['addMoney'])) {
        if(isset($_SESSION['balance']) && isset($_COOKIE['usuario'])){
            $total = $_POST['balanceToAdd'] + $_SESSION['balance'];

            $directory = 'usuarios/'.$_COOKIE['usuario'].'/datos.dat';

            if(file_exists($directory)==true){
                // load the data and delete the line from the array
                $lines = file($directory);
                if(!sizeof($lines) > 0){
                    $_SESSION['error']='file';
                    header('Location: error.php');
                }
                $last = sizeof($lines) - 1;
                unset($lines[$last]);

                // write the new data to the file

                $fp = fopen($directory, 'w');
                fwrite($fp, implode('', $lines).$total."\n");
                fclose($fp);

            }else{
                $_SESSION['error']='file';
                header('Location: error.php');
            }
            header('Location: history.php');
        }
    }

    #caso de BUY
    elseif (isset($_POST['buy'])) {
    	#existe el carro
    	if(!isset($_SESSION['cart'])) {
    		$_SESSION["error"] = "cart";
            header("Location: error.php");
    	}

        if(!isset($_SESSION['buyprice']) ){
            $_SESSION["error"] = "variables_price";
            header("Location: error.php");
        }
        #es un usuario loggeado

        if(!isset($_SESSION['login']) || ($_SESSION['login'] != 1 )|| !isset($_COOKIE['usuario']) ) {

            $_SESSION["error"] = "user";
            header("Location: error.php");
        }

        #comprobacion saldo
        $directory = 'usuarios/'.$_COOKIE['usuario'].'/datos.dat';

        if(file_exists($directory)==true){
            if(($file = fopen($directory,'r'))!=false){
                $i = 0;
                while($i < 6){
                    $bufer=fgets($file);// obtener saldo
                    $i += 1;
                }
                $saldo = $bufer;
            }
        }else{
            closedir($directory);
            $_SESSION['error']='file';
            header('Location: error.php');
        }

        $precio = $_SESSION['buyprice'];
        if((float)$saldo < (float)$precio){
            $_SESSION['error'] = "login";
            header("Location: error.php");
            error_log("OSTIA PUTA 2");
        }

        $total = $saldo - $_SESSION['buyprice'];

        if(file_exists($directory)==true){
                // load the data and delete the line from the array
            $lines = file($directory);
            if(!sizeof($lines) > 0){
                $_SESSION['error']='file';
                header('Location: error.php');
            }
            $last = sizeof($lines) - 1;
            unset($lines[$last]);

            // write the new data to the file

            $fp = fopen($directory, 'w');
            fwrite($fp, implode('', $lines).$total."\n");
            fclose($fp);
        }


        #path al archivo
        $path = "usuarios/".$_COOKIE['usuario']."/historial.xml";
    	#obtener el XML o crearlo si no existe
    	if(!file_exists($path)){
    		$xml = new DOMDocument();
	    	$xml_compras = $xml->createElement("compras");
	    	$xml->appendChild($xml_compras);
    	}else{
        chmod("usuarios",0777);
    		$xml = new DOMDocument();
    		$xml->load($path);
    		$xml_compras = $xml->getElementsByTagName('compras')[0];
    	}

    	#Iterar y escribir la informacion
    	$arlength = count($_SESSION['cart']);
    	$xmlcatalogo=simplexml_load_file("catalogo.xml") or die("Error: Cannot create object");
      	$flag_encontrado = 0;
    	for($i = 0; $i < $arlength; $i++){
    		foreach($xmlcatalogo->children() as $pelis) {
          if((string)$pelis['id'] == $_SESSION['cart'][$i]){
                   $nodo = $pelis;
                   $flag_encontrado = 1;
            	}
          }
          if($flag_encontrado){
          		#si se encuentra añadir la info y meter el nodo
	      		$xml_producto = $xml->createElement("producto");
	      		$xml_titulo = $xml->createElement("titulo","$nodo->titulo");
	      		$date = date("d/m/Y H:i:s");
	      		$xml_fecha = $xml->createElement("fecha","$date");
	      		$xml_precio = $xml->createElement("precio","$nodo->precio");

	      		$xml_producto->appendChild($xml_titulo);
			    	$xml_producto->appendChild($xml_fecha);
			    	$xml_producto->appendChild($xml_precio);

			    	$xml_compras->appendChild($xml_producto);
	      	}
    	}

    	$xml->save($path);
    	chmod($path,0777);
    	$_SESSION['cart'] = array();
      unset($_SESSION['cart']);
    	header("location: success.php");
    }
?>
