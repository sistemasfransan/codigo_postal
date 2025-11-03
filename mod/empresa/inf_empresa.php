<?
class InformacionEmpresa
{
	var $conexion;
	
	function InformacionEmpresa( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		//Prueba( $_POST );
		
		if( !$_GET[state] ) $_GET[state] = $_POST[state];
		
		switch( $_GET[state] )
		{
			case "save":
				$this -> Guardar();
			break;
			
			default:
				$this -> Formulario();
			break;
		}
	}
	
	function Formulario()
	{
		//error_reporting(E_ALL);
		//ini_set("display_errors", 1);
		
		$ciudades = $this -> getCiudades(); 
		$regimen = $this -> getRegimen(); 
		
		$_POST = $this -> getInfoEmpresa();
		
		$titulo = "Informaci&oacute;n Empresa";
		require( "empresa.view.php" );
	}
	
	function getInfoEmpresa()
	{
		$empresa = "SELECT a.nitEmpresa, a.nomEmpresa, a.dirEmpresa, 
						   a.telEmpresa, a.celEmpresa, a.corEmpresa, 
						   a.gerEmpresa, a.ciuEmpresa, a.codRegimen
				    FROM tab_empresa a ";
	
		$empresa = $this -> conexion -> Consultar( $empresa, "a" );
		
		return $empresa;
	}
	
	function Guardar()
	{
		$this -> conexion -> Start();
		$_POST[codUsuario] = $_SESSION[session_user][codUsuario];
		
		$delete = "DELETE FROM tab_empresa ";
		
		$this -> conexion -> Consultar( $delete );
		
		$insert = "INSERT INTO tab_empresa 
					(
						nitEmpresa , nomEmpresa , gerEmpresa ,
						dirEmpresa , telEmpresa , ciuEmpresa ,
						celEmpresa , corEmpresa , codRegimen ,
						usrEdicion , fecEdicion
					)
					VALUES 
					(
						'$_POST[nitEmpresa]',  '$_POST[nomEmpresa]',  '$_POST[gerEmpresa]',  
						'$_POST[dirEmpresa]',  '$_POST[telEmpresa]',  '$_POST[ciuEmpresa]',  
						'$_POST[celEmpresa]',  '$_POST[corEmpresa]',  '$_POST[codRegimen]', 
						'$_POST[codUsuario]' , NOW()
					)";
		
		$this -> conexion -> Consultar( $insert );		
		
		MessageOk( "La Informaci&oacute;n de la empresa se actualizo exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Volver", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function getRegimen()
	{
		$select = "SELECT a.codRegimen, a.nomRegimen
					FROM tab_regimen a 
					ORDER BY 2 ";
		
		$select = $this -> conexion -> Consultar( $select, "i", TRUE );
		$select = array_merge( array( array( "", "---" ) ), $select ) ;
		
		return $select;
	}
	
	
	function getCiudades()
	{
		$select = "SELECT a.codCiudad, a.nomCiudad
					FROM tab_ciudades a 
					ORDER BY 2 ";
		
		$select = $this -> conexion -> Consultar( $select, "i", TRUE );
		$select = array_merge( array( array( "", "---" ) ), $select ) ;
		
		return $select;
	}
	
	function getDetalles( $numFactura )
	{
		$select = "SELECT a.desDetalle AS detalle, a.valUnitario AS valor, 
						  a.numCantidad AS cantidad, a.codConcepto AS codigo,
						  a.valDetalle AS total
				  	 FROM tab_detfactura a
				  	 WHERE a.numFactura = '$numFactura' 
					 ORDER BY numDetalle ";
		
		$select = $this -> conexion -> Consultar( $select, "a", TRUE );
		
		return $select;
	}
	
	function getFacturas( $numFactura )
	{
		$select = "SELECT a.numFactura, a.fecFactura, b.nomCliente, 
						  a.docCliente, a.total, a.impuesto, 
						  a.neto, a.usrCreacion, a.fecCreacion, 
						  a.usrEdicion, a.fecEdicion, a.codCliente
				  	 FROM tab_facturas a,
						  tab_clientes b
				  	 WHERE a.codCliente = b.codCliente  ";
						   
		if( $numFactura )
			$select .= " AND a.numFactura = '$numFactura' ";
		
		if( $_POST[numFactura] )
			$select .= " AND a.numFactura = '$_POST[numFactura]' ";
		
		if( $_POST[docCliente] )
			$select .= " AND a.docCliente = '$_POST[docCliente]' ";
		
		if( $_POST[nomCliente] )
			$select .= " AND b.nomCliente LIKE '%$_POST[nomCliente]%' ";
		
		$select .= " ORDER BY 1 ";
		
		if( !$numFactura )
			$select = $this -> conexion -> Consultar( $select, "t", TRUE );
		else
			$select = $this -> conexion -> Consultar( $select, "a" );
		
		return $select;
	}
	
	function Listar()
	{
		$facturas = $this -> getFacturas();
		
		$form = new Form( array( "name" => "form" ) );
		
		$form -> Panel( "Filtros de Facturas", "100%" );
		
		$form -> Row();
		$form -> Label( array( "label" => "NIT: ", "for" => "nitCliente" ) );
		$form -> TextField( array( "maxlength" => "30", "size" => "30" , "name" => "nitCliente", "value" => $_POST[nitCliente] ) );
		$form -> Label( array( "label" => "Nombre Cliente: ", "for" => "nomCliente" ) );
		$form -> TextField( array( "maxlength" => "30", "size" => "30" , "name" => "nomCliente", "value" => $_POST[nomCliente] ) );
		$form -> closeRow();
		
		$form -> Row();
		$form -> Label( array( "label" => "Factura: ", "for" => "numFactura" ) );
		$form -> TextField( array( "maxlength" => "15", "size" => "15" , "name" => "numFactura", "value" => $_POST[numFactura] ) );
		
		$form -> closeRow();
		
		$form -> ClosePanel();
		
		$form -> Button( "Aceptar", array( "onclick" => "form.submit()" ) );
		
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td>";
		echo "	<h2 style='text-align:left' >Facturas Registradas</h2>";
		echo "</td>";		
		echo "</tr>";
		echo "</table>";
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td class='cellHead' colspan=8 >Se encontraron ".sizeof( $facturas )." Facturas</td>";		
		echo "</tr>";		
		echo "<tr>";
		
		echo "<td class='cellHead'>Factura</td>";
		echo "<td class='cellHead'>Fecha Exp.</td>";
		echo "<td class='cellHead' colspan=2 >Cliente</td>";
		echo "<td class='cellHead'>Valor</td>";	
		echo "<td class='cellHead'>Registro</td>";		
		echo "<td class='cellHead'>Edicion</td>";	
		echo "<td class='cellHead'>Gestion</td>";		
		echo "</tr>";
		
		$total = 0;
		
		if( $facturas )
		{
			foreach( $facturas as $row )
			{
				$estado = "Inactivo";
				if( $row[8] ) $estado = "Activo";
				
				$total += $row[total];
				
				echo "<tr>";				
				echo "<td class='cellInfo' align='left' nowrap ><b>$row[0]</b></td>";				
				echo "<td class='cellInfo' align='center' nowrap ><em>$row[1]</m></td>";				
				echo "<td class='cellInfo' align='right' nowrap ><b>$row[docCliente]</b></td>";
				echo "<td class='cellInfo' align='left' nowrap >$row[2]</td>";				
				echo "<td class='cellInfo' align='right' nowrap><b>$ ".number_format( $row[total], 0 , '.', '.' )."</b></td>";
				echo "<td class='cellInfo' align='center' nowrap><b>$row[usrCreacion]</b> $row[fecCreacion]</td>";
				echo "<td class='cellInfo' align='center' nowrap><b>$row[usrEdicion]</b> $row[fecEdicion]</td>";
				echo "<td class='cellInfo' align='right' nowrap>
					<a href='index.php?codOption=$_GET[codOption]&state=edit&numFactura=$row[0]' title='Editar'>
						<img src='images/edit.png' border=0>
					</a>&nbsp;
					<a href='pdf/factura.php?numFactura=$row[0]' target=blank title='Factura en PDF' >
						<img src='images/pdf.png' border=0>
					</a>&nbsp;";
				echo "<a href='index.php?codOption=$_GET[codOption]&state=mail&numFactura=$row[0]' title='Enviar Factura'>
						<img border=0 src='images/mail.png'>
					</a>&nbsp;";
					
				echo "<a href='#' onclick='Eliminar( \"$row[0]\", \"$row[3]\" )' title='Eliminar'>
						<img border=0 src='images/del.png'>
					</a>
					</td>";
				echo "</tr>";
			}
			
			echo "<tr>";
			echo "<td class='cellHead' style='text-align:right' colspan=4 >TOTAL: </td>";
			echo "<td class='cellInfo' align='right' nowrap ><b>$ ".number_format( $total, 0 , '.', '.' )."</b></td>";
			echo "<td class='cellHead' colspan=3>".sizeof( $facturas )." Facturas registradas</td>";
			echo "</tr>";
		}			
		else
		{
			echo "<tr>";
			echo "<td class='cellInfo' align='left' colspan='8' >No se encontraron Facturas</td>";
			echo "</tr>";
		}
		
		echo "</table>";
		$form -> Hidden( array( "name" => "codDelete", "value" => $_POST[codDelete] ) );
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		$form -> closeForm();
	}
}

$action = new InformacionEmpresa( $this -> conexion );

?>