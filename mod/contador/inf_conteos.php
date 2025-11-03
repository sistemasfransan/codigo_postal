<?

class ListarNegocios
{
	var $conexion;
	
	function  ListarNegocios( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		if( !$_POST ) $_POST = $_GET;
		
		switch( $_POST[state] )
		{
			default:
				$this -> Listar();
			break;
		}
	}
	
	function getInfoAsignacion( $codAsignacion )
	{
		$select = "SELECT a.codAsignacion, a.codUsuario, a.codSucursal, 
						  a.numAno, a.numConsecutivo, a.fecAsignacion, 
						  a.codDO, a.numIp, b.nomUsuario, 
						  DATE( a.fecAsignacion ) AS fecha,
						  TIME( a.fecAsignacion ) AS hora, a.usrCreacion, a.fecCreacion,
						  c.nomSucursal, a.numIni, a.numFin
				   FROM tab_codigos a,
						tab_usuarios b,
						tab_sucursales c
				   WHERE a.codUsuario = b.codUsuario AND
						 a.codSucursal = c.codSucursal AND
						 a.codAsignacion = '$codAsignacion' ";
		
		$select = $this -> conexion -> Consultar( $select, "a" );
		
		return $select;
	}
	
	
	function getCodigo( $codSucursal )
	{
		$codigo = "SELECT MAX( a.numConsecutivo ) as codigo
				   FROM tab_codigos a 
				   WHERE a.codSucursal = '$codSucursal' ";
		
		$codigo = $this -> conexion -> Consultar( $codigo, "a" );
		
		if( !$codigo[codigo] ) $codigo[codigo] = 1;
		else $codigo[codigo]++;
		
		return sprintf( "%07d", $codigo[codigo] );
	}
	
	function getSitios()
	{
		$select = "SELECT a.codSitio, a.nomSitio
				   FROM tab_sitios a
				   ORDER BY 2";

        $select = $this -> conexion -> Consultar( $select, "i", TRUE );
        $select = array_merge( array( array( "", "---" ) ), $select );

        return $select;
	}
	
	function getAcciones()
	{
		$select = "SELECT a.codAccion, a.nomAccion
				   FROM tab_accion a
				   ORDER BY 2";

        $select = $this -> conexion -> Consultar( $select, "i", TRUE );
        $select = array_merge( array( array( "", "---" ) ), $select );

        return $select;
	}
	
	function getListado()
	{
		$codPerfil = $_SESSION[session_user][codPerfil];
		$codSitio = $_SESSION[session_user][codSitio];
		$codUsuario = $_SESSION[session_user][codUsuario];
		
		$select = "SELECT b.nomSitio, a.codRegistro, c.nomAccion, a.fecRegistro, a.usrRegistro
				   FROM tab_registros a,
						tab_sitios b,
						tab_accion c
				   WHERE a.codSitio = b.codSitio AND
						 a.codAccion = c.codAccion ";
						 
		if( $codPerfil != 1)
			$select .= " AND a.codSitio = '$codSitio' ";
						 
		if( $_POST[fcodSitio] )
			$select .= " AND a.codSitio = '$_POST[fcodSitio]' ";
						 
		if( $_POST[fcodAccion] )
			$select .= " AND a.codAccion = '$_POST[fcodAccion]' ";
						 
		if( $_POST[ffecRegistro] )
			$select .= " AND a.fecRegistro = '$_POST[ffecRegistro]' ";
			
		$select .= " ORDER BY a.fecRegistro ";
		
		$select = $this -> conexion -> Consultar( $select, "t", TRUE );
		
		return $select;
	}
	
	function Listar()
	{
		$select = $this -> getListado();
		$codPerfil = $_SESSION[session_user][codPerfil];
		
		$form = new Form( array( "name" => "form" ) );		
		
		$form -> Panel( "Conteos Registrados", "100%" );
		
		if( $codPerfil == 1 )
		{
			$form -> Row();
			
			$form -> Label( array( "label" => "Sitio: ", "for" => "fcodSitio" ) );
			$form -> ComboBox( array( "name" => "fcodSitio" ), $this -> getSitios(), $_POST[fcodSitio] );
			
			$form -> closeRow();
		}
		
		$form -> Row();
		$form -> Label( array( "label" => "Fecha: ", "for" => "ffecRegistro" ) );
		$form -> TextField( array( "maxlength" => "10", "size" => "10" ,"validate" => "d" , "name" => "ffecRegistro", "value" => $_POST["ffecRegistro"] ) );
		$form -> Label( array( "label" => "Acción: ", "for" => "fcodAccion" ) );
		$form -> ComboBox( array( "name" => "fcodAccion" ), $this -> getAcciones(), $_POST[fcodAccion] );
		$form -> closeRow();	

		$form -> Row();
		echo "<td colspan='4' >";
		$form -> Button( "Aceptar", array( "onclick" => "form.submit()" ) );
		echo "</td>";
		$form -> closeRow();	
		
		$form -> ClosePanel();	

		echo "<br/>";
		echo "<div align=left ><a href='xls.php' >[ Excel ]</a></div>";
		$html = "<table>";
			
		$html .=  "<br/>";
		$html .=  "<table width='100%' >";		
		$html .=  "<tr>";
		$html .=  "<td class='cellHead' colspan=12 >Se encontraron ".sizeof( $select )." conteos registrados</td>";		
		$html .=  "</tr>";

		
		$html .=  "<tr>";		
		$html .=  "<td class='cellHead'>Sitio</td>";
		$html .=  "<td class='cellHead'>Nro. Registro</td>";
		$html .=  "<td class='cellHead'>Acción</td>";
		$html .=  "<td class='cellHead'>Fecha</td>";
		$html .=  "<td class='cellHead'>Usuario</td>";
		$html .=  "</tr>";
		
		$i = 0;
		
		$_POST[codPerfil] = $_SESSION[session_user][codPerfil];
		
		if( $select )
		foreach( $select as $row )
		{
			$i++;
			$html .=  "<tr>";	 
			$html .=  "<td class='cellInfo' align=center><b>".$row[nomSitio]."</b></td>";			
			$html .=  "<td class='cellInfo' align=left>".$row[codRegistro]."</td>";			
			$html .=  "<td class='cellInfo' align=left>".$row[nomAccion]."</td>";			
			$html .=  "<td class='cellInfo' align=center>".$row[fecRegistro]."</td>";			
			$html .=  "<td class='cellInfo' align=left>".$row[usrRegistro]."</td>";				
			$html .=  "</tr>";
		}		
		else
		{
			$html .=  "<tr>";
			$html .=  "<td class='cellInfo' align='left' colspan='8' >No se encontraron ".$this -> nombres." disponibles</td>";
			$html .=  "</tr>";
		}
		$html .=  "</table>";
		
		echo $html;
		
		$html = str_replace( "<img src='images/edit.png' >", "", $html );
		$html = str_replace( "<a", "<label", $html );
		$html = str_replace( "</a>", "</label>", $html );
		
		$_SESSION["html"] = $html;
		
		
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		$form -> Hidden( array( "name" => "codDelete", "value" => "" ) );
		$form -> closeForm();
	}
}


$option = new ListarNegocios( $this -> conexion );
?>