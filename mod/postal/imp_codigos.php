<?

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
ini_set('memory_limit', '512M');

class SubirGrupo
{
	var $conexion;
	
	function SubirGrupo( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		switch( $_REQUEST[state] )
		{
			case "upload":
				$this -> SubirLista();
			break;
			
			default:
				$this -> Formulario();
			break;
		}
	}
	
	function limpiar($String)
	{
	    $String = str_replace(array('á','à','â','ã','ª','ä'),"a",$String);
	    $String = str_replace(array('Á','À','Â','Ã','Ä'),"A",$String);
	    $String = str_replace(array('Í','Ì','Î','Ï'),"I",$String);
	    $String = str_replace(array('í','ì','î','ï'),"i",$String);
	    $String = str_replace(array('é','è','ê','ë'),"e",$String);
	    $String = str_replace(array('É','È','Ê','Ë'),"E",$String);
	    $String = str_replace(array('ó','ò','ô','õ','ö','º'),"o",$String);
	    $String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),"O",$String);
	    $String = str_replace(array('ú','ù','û','ü'),"u",$String);
	    $String = str_replace(array('Ú','Ù','Û','Ü'),"U",$String);
	    $String = str_replace(array('[','^','´','`','¨','~',']'),"",$String);
	    $String = str_replace("ç","c",$String);
	    $String = str_replace("Ç","C",$String);
	    $String = str_replace("ñ","n",$String);
	    $String = str_replace("Ñ","N",$String);
	    $String = str_replace("Ý","Y",$String);
	    $String = str_replace("ý","y",$String);
	     
	    $String = str_replace("&aacute;","a",$String);
	    $String = str_replace("&Aacute;","A",$String);
	    $String = str_replace("&eacute;","e",$String);
	    $String = str_replace("&Eacute;","E",$String);
	    $String = str_replace("&iacute;","i",$String);
	    $String = str_replace("&Iacute;","I",$String);
	    $String = str_replace("&oacute;","o",$String);
	    $String = str_replace("&Oacute;","O",$String);
	    $String = str_replace("&uacute;","u",$String);
	    $String = str_replace("&Uacute;","U",$String);
	    
		$String = str_replace("CRA.", "KR ",$String); 
		$String = str_replace("CRA", "KR ",$String);
		$String = str_replace("CARRERA", "KR ",$String);
		$String = str_replace("CALLE", "CL ",$String); 
		$String = str_replace("CLLE", "CL ",$String); 
		$String = str_replace("CLL", "CL ",$String); 
		
		$String = str_replace("CARRETERA", "CRT ",$String); 
		$String = str_replace("MANZANA", "MZ ",$String); 
		
		$String = str_replace("DIAGONAL", "DG ",$String); 
		$String = str_replace("TRANSVERSAL", "TV ",$String); 
		$String = str_replace("TRAN", "TV ",$String); 
		
		$String = str_replace("C/", "CL ",$String); 
		$String = str_replace("AVENIDA", "AV ",$String); 
		$String = str_replace("AVDA.", "AV ",$String); 
		$String = str_replace("AVDA", "AV ",$String); 
		$String = str_replace("AVE", "AV ",$String);
		
		$String = str_replace("AV CL", "AC ",$String);
		$String = str_replace("AV  CL", "AC ",$String);
		$String = str_replace("AV KR", "AK ",$String);
		
		$String = str_replace("Nº","#",$String);
		$String = str_replace("  "," ",$String);
		
	    return $String;
	}
	
	function SubirLista()
	{
		//Prueba( $_POST );
		//Prueba( $_FILES );	
		
		$usuario = $_SESSION[session_user][codUsuario];		
		$ruta = $_SERVER['DOCUMENT_ROOT']."/postal/codigos/$usuario.csv";
		
		
		copy( $_FILES[filePlane][tmp_name], $ruta );
		
		$archivo = File( $ruta );		
		
		$matrix = array();
		
		if( $archivo )
		foreach( $archivo as $linea )
		{
			if( $linea )
			{
				$matrix[] = explode( ";", $linea );
			}
		}
		
		 
		
		$html = "";
		
		$html .= "<table width=100%' border=1 '>";
		
		$html .= "<tr>";
		//$html .= "<th>Var</th>";
		$html .= "<th>Dirección</th>";
		$html .= "<th>Ciudad</th>";
		$html .= "<th>Google Maps</th>"; 
		$html .= "<th>POS</th>";
		$html .= "<th>Código Postal</th>";
		$html .= "</tr>";
		
		for( $i = 0; $i < sizeof($matrix); $i++ )//sizeof($matrix) 
		{
			$dir = mb_strtoupper(trim($matrix[$i][1]));
			$ciu = $matrix[$i][2];
			
			$direccion = ($this -> limpiar("$dir"));
			
			if( $ciu ) $dir.= ", $ciu";
			
			$dirUrl = urlencode( $dir );
			
			$urlLocation = "http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find?text=$dirUrl&f=json&sourceCountry=COL";
			 
			$response = $this -> getData( $urlLocation );
			$location = $response[locations][0][feature][geometry];
			 
			
			$nomDireccion = utf8_decode($response[locations][0][name])."<br>";
			
			$urlPostal = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$location[y],$location[x]&key=AIzaSyDorVGx1WHCZLGUya6yg0z_ijAQuYMB_ZQ";
			
			$urlPostal = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$location[y],$location[x]&key=AIzaSyDorVGx1WHCZLGUya6yg0z_ijAQuYMB_ZQ";
			
			
			$response = $this -> getData( $urlPostal );
			
			$respuesta = $response[results][0];
			
			//$nomDireccion .= utf8_decode($response[results][0][formatted_address]);	
			
			$posicion = $response[results][0][geometry][location]; 
			
			if($respuesta[address_components])
			foreach($respuesta[address_components] as $row )
			{	
				if($row[types][0] == "postal_code" )
				{
					$codPostal = $row[short_name];
				}
			}
			
			$html .= "<tr>";
			$html .= "<td align=left >$direccion</td>";
			$html .= "<td align=left >$ciu</td>";
			$html .= "<td align=left >$nomDireccion</td>";
			$html .= "<td align=left ><a target='blank' href='https://www.google.com/maps/?q=$location[y],$location[x]'>Mapa</a></td>";
			$html .= "<td align=left >$codPostal</td>";
			$html .= "</tr>";
		}
		
		
		$html .= "</table>";
		
		$_SESSION[html] = $html;
		
		echo $html;
		
		echo "<a href='xls.php' >Excel</a>";
		
		
		echo "<br/>";
		
		$this -> conexion -> Start();
		$usuario = $_SESSION[session_user][codUsuario];
		
		
		MessageOk( "Se subieron los correos" );
		
		
		$this -> conexion -> Rollback();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Volver", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
		
	}

	function getData( $url )
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url ); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($ch), true);
		
		return $response;
	}
	
	function getCodigo() 
	{
		$usuario = $_SESSION[session_user][codUsuario];
		
		$select = "SELECT MAX( codCliente )
				   FROM tab_clientes a 
				   WHERE a.usrCreacion = '$usuario' ";
		
		$select = $this -> conexion -> Consultar( $select, "i" );
		
		if( !$select ) 
			return 1;
		
		return $select[0]+1;
	}	
	
	function getCodCliente( $mailCliente ) 
	{
		$usuario = $_SESSION[session_user][codUsuario];
		
		$validar = "SELECT codCliente
				  	 FROM tab_clientes a 
					 WHERE mailCliente LIKE '$mailCliente' AND
						   usrCreacion = '$usuario' ";
		
		$validar = $this -> conexion -> Consultar( $validar, "i" );
		
		return $validar[0];
	}
	
	function getCodGrupo( $nomGrupo ) 
	{
		$usuario = $_SESSION[session_user][codUsuario];
		
		$validar = "SELECT codGrupo
				  	 FROM  tab_grupos a 
					 WHERE TRIM( nomGrupo ) LIKE TRIM( '$nomGrupo' ) AND
						   usrCreacion = '$usuario' ";
		
		$validar = $this -> conexion -> Consultar( $validar, "i" );
		
		return $validar[0];
	}
	
	function ValidarCliente( $mailCliente ) 
	{
		$usuario = $_SESSION[session_user][codUsuario];
		
		$validar = "SELECT 1
				  	 FROM tab_clientes a 
					 WHERE mailCliente LIKE '$mailCliente' AND
						   usrCreacion = '$usuario' ";
		
		$validar = $this -> conexion -> Consultar( $validar, "i" );
		
		//Prueba( $validar );
		
		if( $validar )
			return true;
		
		return false;
	}
	
	function ValidarClienteGrupo( $codCliente, $codGrupo ) 
	{
		$usuario = $_SESSION[session_user][codUsuario];
		
		$validar = "SELECT 1
				  	FROM tab_clientesgrupo a 
					WHERE codCliente = '$codCliente' AND
						  codGrupo = '$codGrupo' ";
		
		$validar = $this -> conexion -> Consultar( $validar, "i" );
		
		if( $validar )
			return true;
		
		return false;
	}
	
	function getClientes()
	{
		$usuario = $_SESSION[session_user][codUsuario];
		
		$select = "SELECT a.codCliente, a.mailCliente, a.nomCliente, 
						  a.fecCreacion, a.fecEdicion
				   FROM tab_clientes a  
				   WHERE indEstado = 1 AND
						 usrCreacion = '$usuario' 
				   ORDER BY 2 ";
		
		$select = $this -> conexion -> Consultar( $select, "i", TRUE );
		
		return $select;
	}
	
	function Formulario()
	{
		$form = new Form( array( "name" => "form" ) );	
		
		$form -> Panel( "Subir Direcciones", "100%" );
		
		$form -> Row();
		echo "<td colspan=2 ><div align=center><img src='images/csv.png' ><br/><br/></div></td>";
		$form -> closeRow();	
				
		$form -> Row();
		$form -> Label( array( "label" => "Archivo Plano:", "for" => "filePlane" ) );
		$form -> File( array( "name" => "filePlane" ) );
		$form -> closeRow();	
		
		$form -> Row();
		echo "<td colspan=2 ><div align=center><br/><br/><a href='doc/formato.csv' >[ Formato Archivo Plano ]</a></div></td>";
		$form -> closeRow();
		
		$form -> closePanel();		
		
		echo "<br>";
		echo "<div align='center' >";
		$form -> Button( "Subir Archivo", array( "onclick" => "submit()" ) );
		echo "</div>";
		$form -> Hidden( array( "name" => "state", "value" => "upload" ) );
		$form -> closeForm();
	}
}

$action = new SubirGrupo( $this -> conexion );

?>