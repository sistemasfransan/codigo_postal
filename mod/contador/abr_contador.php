<?

class RegistrarNegocio
{
	var $conexion;
	
	function  RegistrarNegocio( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		if( !$_POST ) $_POST = $_GET;
		
		switch( $_POST[state] )
		{
			case "insert":
				$this -> Registrar();
			break;
			
			default:
				$this -> Formulario();
			break;
		}
	}
	
	function Registrar()
	{
		$_POST[usrCreacion] = $_SESSION[session_user][codUsuario];	
		
		$this -> conexion -> Start();
		
		$codConsecutivo = $this -> getCodigo( $_POST[codSucursal] );
		$numIni = $codConsecutivo;
		
		for( $i = 0; $i < $_POST[numConsecutivo] ; $i++ )
		{					
			$davConsecutivo = $_POST[numFormulario].$_POST[numAno].$_POST[numSIA].$_POST[codSucursal].$codConsecutivo;
			$consecutivos[] = $davConsecutivo;				
			$numFin = $codConsecutivo;			
			$codConsecutivo = sprintf( "%07d", $codConsecutivo + 1 );		
		}	
		
		$codAsignacion = $this -> getCodigoTabla();

		$insert = "INSERT INTO tab_codigos 
					(
						codAsignacion, codUsuario, codSucursal, 
						numAno, numConsecutivo, fecAsignacion, 
						codDO, numIp, usrCreacion, fecCreacion,
						numIni, numFin
					) 
					VALUES 
					(
						'$codAsignacion', '$_POST[codUsuario]', '$_POST[codSucursal]', 
						'$_POST[numAno]', '$_POST[numConsecutivo]', NOW(), 
						'$_POST[codDO]', '$_SERVER[REMOTE_ADDR]', '$_POST[usrCreacion]', NOW(),
						'$numIni', '$numFin'
					);";
		
		//Prueba( $insert );
		
		$this -> conexion -> Consultar( $insert );		
		$this -> conexion -> Commit();
		$this -> EnviarCorreo( $consecutivos );

        $form = new Form(array("name" => "form"));
		
		MessageOk( "Se asignaron exitosamente <b>$_POST[numConsecutivo]</b> consecutivos." );
        $form->Button("Aceptar", array("onclick" => "form.submit()"));
        $form->closeForm();
	}
	
	function EnviarCorreo( $consecutivos )
	{
		//Prueba( $_POST );
		
		$correos = array(); 
		
		$correos[] = "info@alsagacompany.com";
		$correos[] = "info@alsagahosting.com";
		
		$sucursal = "SELECT a.nomSucursal
				   FROM tab_sucursales a
				   WHERE a.codSucursal = '$_POST[codSucursal]' ";
		
		$sucursal = $this -> conexion -> Consultar( $sucursal, "a" );
		
		$usuario = "SELECT a.emaUsuario
				   FROM tab_usuarios a
				   WHERE a.codUsuario = '$_POST[codUsuario]' ";
		
		$usuario = $this -> conexion -> Consultar( $usuario, "a" );
		
		//
		
		$correos[] = $usuario[emaUsuario];
				
		$empresa = "SELECT a.nitEmpresa, a.nomEmpresa, a.dirEmpresa, 
					   a.telEmpresa, b.nomCiudad, a.celEmpresa,
					   a.corEmpresa
				 FROM tab_empresa a,
					  tab_ciudades b
				 WHERE a.ciuEmpresa = b.codCiudad  ";
	
		$empresa = $this -> conexion -> Consultar( $empresa, "a" );
		
		$para = implode( ", ", $correos );
		
		//Prueba( $para );
		
		$titulo = "Asingación de Consecutivos";
		
		$header = "padding:10px; background-color:#f4f4f4; border:1px solid #ddd; ";
		$body = "padding:10px; background-color:#fff; border:1px solid #ddd; font-size:12px; text-align:center; ";
		$footer = "padding:10px; background-color:#333; color: #fff; text-align:center;";
		
		$cellLabel = "style='padding:3px 10px; text-align:right; '";
		$cellInfo = "style='padding:3px 10px; text-align:left; '";
		$cellHead = "style='padding:3px 10px; text-align:center; background-color:#f4f4f4; border:1px solid #ddd;'";
		$cellTd = "style='padding:3px 10px; text-align:center; background-color:#fff; border:1px solid #ddd;'";
		
		$mensaje  = "";
		$mensaje .= "<div>";
		$mensaje .= "	<div style='$header' >";
		$mensaje .= "		<table width='100%' >";
		$mensaje .= "		<tr>";
		$mensaje .= "		<td align=center >
								<img src='".URLAPP."images/empresa.png' style='width:100px; max-width:100px; height:auto' >
								<div><b>$empresa[nomEmpresa]</b></div>
								<h1 style='color:#333' >$titulo</h1></td>";
		$mensaje .= "		</tr>";
		$mensaje .= "		</table>";	
		$mensaje .= "	</div>";
		$mensaje .= "	<div style='$body' >";		
		
		$mensaje .= "	<div>";
		$mensaje .= "		<table width='100%' align=center>";
		$mensaje .= "		<tr>";
		$mensaje .= "		<td $cellLabel width='20%'><b>Sucursal:</b></td>";
		$mensaje .= "		<td $cellInfo width='30%'>$sucursal[nomSucursal]</td>";
		$mensaje .= "		<td $cellLabel width='20%'><b>Año:</b></td>";
		$mensaje .= "		<td $cellInfo width='30%'>$_POST[numAno]</td>";
		$mensaje .= "		</tr>";
		
		$mensaje .= "		<tr>";
		$mensaje .= "		<td $cellLabel ><b>SIA:</b></td>";
		$mensaje .= "		<td $cellInfo >$_POST[numSIA]</td>";
		$mensaje .= "		<td $cellLabel ><b>Cantidad de Consecutivos:</b></td>";
		$mensaje .= "		<td $cellInfo >$_POST[numConsecutivo]</td>";
		$mensaje .= "		</tr>";
		
		$mensaje .= "		<tr>";
		$mensaje .= "		<td colspan=4 align=center >";
		$mensaje .= "		<br/>";
		$mensaje .= "		<table>";
		$mensaje .= "		<tr>";
		$mensaje .= "		<th $cellHead >#</th>";
		$mensaje .= "		<th $cellHead >Consecutivos Asignados</th>";
		$mensaje .= "		</tr>";
		
		$i = 1;
		if( $consecutivos )
		foreach( $consecutivos as $c )
		{
			$mensaje .= "		<tr>";
			$mensaje .= "		<td $cellTd >$i</td>";
			$mensaje .= "		<td $cellTd >$c</td>";
			$mensaje .= "		</tr>";
			$i++;
		}
		
		$mensaje .= "		</table>";
		$mensaje .= "		</td>";
		$mensaje .= "		</tr>";
		
		$mensaje .= "		</table>";		
		$mensaje .= "	</div>";
		
		$mensaje .= "		<br/>Enviado desde <a href='".WEBSITE."' >".WEBSITE."</a><br/>";
		$mensaje .= "	</div>";
		$mensaje .= "	<div style='$footer' >";
		$mensaje .= "		$empresa[nomCiudad] - ".date( "Y" )." ";
		$mensaje .= "	</div>";
		$mensaje .= "</div>";
		
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= "From: ".TITLE." <".NOREPLY.">" . "\r\n";
		
		//echo $mensaje;
		
		mail( $para, $titulo, $mensaje, $cabeceras );
	}
	
	function getCodigo( $codSucursal )
	{
		$codigo = "SELECT MAX( a.numFin ) as codigo
				   FROM tab_codigos a 
				   WHERE a.codSucursal = '$codSucursal' ";
		
		$codigo = $this -> conexion -> Consultar( $codigo, "a" );
		
		if( !$codigo[codigo] ) $codigo[codigo] = 1;
		else $codigo[codigo]++;
		
		return sprintf( "%07d", $codigo[codigo] );
	}
	
	function getCodigoTabla()
	{
		$codigo = "SELECT MAX( a.codAsignacion ) as codigo
				   FROM tab_codigos a  ";
		
		$codigo = $this -> conexion -> Consultar( $codigo, "a" );
		
		if( !$codigo[codigo] ) $codigo[codigo] = 1;
		else $codigo[codigo]++;
		
		return $codigo[codigo];
	}
	
	function getSucursales()
	{
		$select = "SELECT a.codSucursal, a.nomSucursal
				   FROM tab_sucursales a
				   ORDER BY 2";

        $select = $this -> conexion -> Consultar( $select, "i", TRUE );
        $select = array_merge( array( array( "", "---" ) ), $select );

        return $select;
	}
	
	function getUsuarios()
	{
		$select = "SELECT a.codUsuario, a.nomUsuario
				   FROM tab_usuarios a
				   WHERE a.codPerfil != 1 
				   ORDER BY 2";

        $select = $this -> conexion -> Consultar( $select, "i", TRUE );
        $select = array_merge( array( array( "", "---" ) ), $select );

        return $select;
	}
	
	function Formulario()
	{
		$_POST[codUsuario] = $_SESSION[session_user][codUsuario];	
		$_POST[codSitio] = $_SESSION[session_user][codSitio];	
		
		$insert = "UPDATE tab_sitios 
				   SET indEstado = '0',
					   usrEdicion = '$_POST[codUsuario]',
					   fecEdicion = NOW() 
				   WHERE codSitio = '$_POST[codSitio]' ";
		
		$this -> conexion -> Consultar( $insert );
		
		$form = new Form(array("name" => "form"));
        $form -> Panel( "Contador", "100%" );
		echo "<tr>";
		echo "<td alert='center' >";
		echo "<embed  src='init.swf?codSitio=$_POST[codSitio]&codUsuario=$_POST[codUsuario]' width='450' height='400' FlashVars='codSitio=$_POST[codSitio]&codUsuario=$_POST[codUsuario]'  ></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td align=center >";
		echo "<br/>";
		$form->Button( "Activar", array("onclick" => "EstadoConteo()", "id" => "btnEstado", "value" => "Activar" ));
		echo "<br/>";
		echo "<br/>";
		echo "<div id='menContador' ><b style='color:#900' >Contador Inactivo</b></div>";
		echo "<td>";
		echo "</tr>";
        $form -> closePanel();
		echo "<div align=left><a href='images/codigo.jpg' target='_blank'>[Descargar Código]</a></div>";
        $form -> closeForm();
	}
}


$option = new RegistrarNegocio( $this -> conexion );
?>