<?
class ConfigurarParametros
{
	var $conexion;
	
	function ConfigurarParametros( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		if( !$_GET[state] ) $_GET[state] = $_POST[state];
		
		switch( $_GET[state] )
		{
			case "1":
				$this -> Guardar();
			break;
			
			default:
				$this -> Formulario();
			break;
		}
	}
	
	function Formulario()
	{		
		$form = new Form( array( "name" => "form" ) );
	
		$form -> Panel( "Configuraci&oacute;n Facturaci&oacute;n", "100%" );
		
		$form -> Row();
		$form -> Label( array( "label" => "* IVA( % ): ", "for" => "porIva" ) );
		$form -> TextField( array( "maxlength" => "3", "size" => "3", "name" => "porIva", "value" => $this -> getParame( "porIva" ) ) );		
		$form -> Label( array( "label" => "* ICA( x 1000 ): ", "for" => "porIca" ) );
		$form -> TextField( array( "maxlength" => "3", "size" => "3", "name" => "porIca" , "value" => $this -> getParame( "porIca" ) ) );
		$form -> closeRow();

		$form -> Row();
		$form -> Label( array( "label" => "* Aviso de Regimen: ", "for" => "aviRegimen" ) );
		$form -> TextField( array( "name" => "aviRegimen", "maxlength" => "250", "colspan" => "3", "size" => "80", "value" => $this -> getParame( "aviRegimen" ) ) );	
		$form -> closeRow();	
		
		$form -> Row();
		$form -> Label( array( "label" => "* Resoluci&oacute;n DIAN: ", "for" => "resDian" ) );
		$form -> TextField( array( "name" => "resDian", "maxlength" => "20", "validate" => "n", "size" => "20", "value" => $this -> getParame( "resDian" )) );		
		$form -> Label( array( "label" => "* Fecha Resoluci&oacute;n: ", "for" => "fecResolucion" ) );
		$form -> TextField( array( "maxlength" => "10", "size" => "10", "validate" => "d", "name" => "fecResolucion" , "value" => $this -> getParame( "fecResolucion" ) ) );
		$form -> closeRow();	
		
		$form -> Row();
		$form -> Label( array( "label" => "* Rango Inicio: ", "for" => "ranInicial" ) );
		$form -> TextField( array( "name" => "ranInicial", "maxlength" => "20", "validate" => "n", "size" => "10", "value" => $this -> getParame( "ranInicial" ) ) );		
		$form -> Label( array( "label" => "* Rango Fin: ", "for" => "ranFinal" ) );
		$form -> TextField( array( "name" => "ranFinal", "maxlength" => "20", "validate" => "n", "size" => "10", "value" => $this -> getParame( "ranFinal" ) ) );	
		$form -> closeRow();
		
		$form -> Row();
		$form -> Label( array( "label" => "* Observaci&oacute;n Factura: ", "for" => "obsFactura" ) );
		$form -> TextArea( array( "name" => "obsFactura", "rows" => "12", "colspan" => "3", "cols" => "80", "value" => $this -> getParame( "obsFactura" ) ) );		
		$form -> closeRow();
		
		$form -> ClosePanel();
		
		$form -> Button( "Guardar", array( "onclick" => "form.submit()" ) );		
		$form -> Hidden( array( "name" => "state", "value" => 1 ) );
		$form -> closeForm();
	}
	
	function getParame( $nomParame )
	{
		$select = "SELECT valParametro
				    FROM tab_parametros 
					WHERE nomParametro = '$nomParame' ";
	
		$select = $this -> conexion -> Consultar( $select, "i" );
		
		return $select[0];
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
		$codUsuario = $_SESSION[session_user][codUsuario];
		$_POST[state] = "";
				
		$llaves = array_keys( $_POST  );
		
		$delete = "DELETE FROM ".BASE.".tab_parametros";		
		$this -> conexion -> Consultar( $delete );
		
		if( $llaves )
		foreach( $llaves as $llave )
		{
			if( $_POST[$llave] )
			{
				$insert = "INSERT INTO ".BASE.".tab_parametros 
							(
								nomParametro, valParametro, usrUpdate, 
								fecUpdate
							) 
							VALUES 
							(
								'$llave', '".addslashes( $_POST[$llave] )."', '$codUsuario', 
								NOW() 
							);";
				
				$this -> conexion -> Consultar( $insert );
				
				//Prueba( $insert );
			}
		}
		
		MessageOk( "Los Parametros de la empresa se actualizaron exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Volver", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	
	
	function Actualizar()
	{
		//Prueba( $_POST );
		
		$this -> conexion -> Start();
		$_POST[codUsuario] = $_SESSION[session_user][codUsuario];
		
		$insert = "UPDATE tab_facturas
				   SET fecFactura = '$_POST[fecFactura]',
					   codCliente = '$_POST[codCliente]',
					   docCliente = '$_POST[docCliente]',
					   total = '$_POST[subtotal]',
					   impuesto = '$_POST[impuesto]',
					   neto = '$_POST[neto]',
					   usrEdicion = '$_POST[codUsuario]',
					   fecEdicion = NOW()
				   WHERE numFactura = '$_POST[numFactura]' ";
		
		$this -> conexion -> Consultar( $insert );
		
		$items = $_POST[item];
		
		$delete = "DELETE FROM tab_detfactura
				   WHERE numFactura = '$_POST[numFactura]' ";
		
		$this -> conexion -> Consultar( $delete );
		
		$i = 1;
		if( $items )
		foreach( $items as $row )
		{
			$detalle = Clear( $row[detalle] );
			
			$insert = "INSERT INTO tab_detfactura 
						(
							numFactura , fecFactura , numDetalle ,
							desDetalle , valUnitario , numCantidad ,
							valDetalle , usrCreacion , fecCreacion,
							codConcepto
						)
						VALUES 
						(
							'$_POST[numFactura]',  '$_POST[fecFactura]',  '$i',  
							'$detalle',  '$row[valor]',  '$row[cantidad]',  
							'$row[total]', '$_POST[codUsuario]', NOW(),
							'$row[codigo]'
						)";
			
			//Prueba( $insert );
			
			$this -> conexion -> Consultar( $insert );
			
			$i++;
		}
				
		MessageOk( "La Factura Nro. <b>$_POST[numFactura]</b> se Actualizo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Actualizar Otro", array( "onclick" => "form.submit()" ) );
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

$action = new ConfigurarParametros( $this -> conexion );

?>