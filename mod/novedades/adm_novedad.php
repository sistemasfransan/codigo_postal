<?
class Administracion
{	
	var $order = 2;
	var $tab = "tab_novedades";
	var $nombre = "Novedad";
	var $nombres = "Novedades";
	var $mensaje = "La Novedad";
	var $columnas = array( "Código", "Nombre" );
	var $campos = array( "codNovedad", "nomNovedad" );
	
	var $conexion;
	
	function Administracion( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		//Prueba( $_POST );
		
		if( !$_POST ) $_POST = $_GET;
		
		switch( $_POST[state] )
		{
			case "delete":
				$this -> Borrar();
			break;
			
			case "Actualizar":
				$this -> Actualizar();
			break;
			
			case "edit":
				$this -> Editar();
			break; 
			
			case "Registrar":
				$this -> Registrar();
			break;
			
			case "insert":
				$this -> Form();
			break;
			
			default:
				$this -> Listar();
			break;
		}
	}
	
	function Registrar()
	{
		$this -> conexion -> Start();
		$_POST[usrCreacion] = $_SESSION[session_user][codUsuario];		
		
		$post = array();
		
		foreach( $this -> campos as $campo )
		{
			$post[] = "'$_POST[$campo]'";
		}
		
		$insert = "INSERT INTO ".$this -> tab." 
					(
						".implode( ", ", $this -> campos ).", usrCreacion, fecCreacion
					) 
					VALUES 
					(
						".implode( ", ", $post ).", '$_POST[usrCreacion]', NOW()
					);";
					
		//Prueba( $insert );
		
		$this -> conexion -> Consultar( $insert );
		
		MessageOk( $this -> mensaje." <b>".$_POST[$this -> campos[1]]."</b> se registro Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Registrar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function Actualizar()
	{		
		$this -> conexion -> Start();
		$_POST[codUsuario] = $_SESSION[session_user][codUsuario];	
		
		$campos = array();
		
		for( $i = 1; $i < sizeof( $this -> campos ); $i++ )
		{
			$campo = $this -> campos[$i];
			$campos[] = "$campo = '$_POST[$campo]'";
		}
		
		$insert = "UPDATE ".$this -> tab." 
					SET ".implode( ", ", $campos ).",
						usrEdicion = '$_POST[codUsuario]',
						fecEdicion = NOW()
					WHERE ".$this -> campos[0]." = '".$_POST[$this -> campos[0]]."' ";
		
		//Prueba( $insert );
		
		$this -> conexion -> Consultar( $insert );
		
		MessageOk( $this -> mensaje." <b>".$_POST[$this -> campos[1]]."</b> se actualizo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Volver", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function Borrar()
	{		
		$this -> conexion -> Start();
		$_POST[codUsuario] = $_SESSION[session_user][codUsuario];			
		
		$delete = "DELETE FROM ".$this -> tab." 
				   WHERE ".$this -> campos[0]." = '$_POST[codDelete]' ";
		
		$this -> conexion -> Consultar( $delete );
		
		MessageOk( $this -> mensaje." Nro. <b>".$_POST[codDelete]."</b> se borro exitosamente." );
		
		$this -> conexion -> Commit();			
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Volver", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function getCodigo()
	{
		$codigo = "SELECT MAX( ".$this -> campos[0]." ) as codigo
				   FROM ".$this -> tab."  ";
		
		$codigo = $this -> conexion -> Consultar( $codigo, "a" );
		
		if( !$codigo[codigo] ) $codigo[codigo] = 1;
		else $codigo[codigo]++;
		
		return $codigo[codigo];
	}
	
	function getInfo( $codigo )
	{
		$select = "SELECT ".implode( ", ", $this -> campos )."
					FROM ".$this -> tab." a 
					WHERE ".$this -> campos[0]." = '$codigo' ";
		
		$select = $this -> conexion -> Consultar( $select, "a" );
		
		return $select;
	}
	
	function Editar()
	{
		//Prueba( $_POST );
		
		if( !isset( $_POST[$this -> campos[1]] ) ) 
		{
			$_POST = $this -> getInfo( $_GET[$this -> campos[0]] );
			
		}
		
		$this -> Vista( "Editar ".$this -> nombre, "Actualizar", "edit" );
	}
	
	function Form()
	{
		$_POST[$this -> campos[0]] = $this -> getCodigo();		
		$this -> Vista( "Registrar ".$this -> nombre, "Registrar", "insert" );
	}
	
	function Vista( $titulo , $boton , $state )
	{
		echo "<script>\n";
		echo "function validarOpcion()
				{
					try
					{
						var formulario = document.getElementById( 'form' );
						var state = document.getElementById( 'state' ); \n\n";
						
		for( $i = 0; $i < sizeof( $this -> campos ); $i++ )
		{
			$campo = $this -> campos[$i];
			$nombre = $this -> columnas[$i];
			
			echo "\t\t\t\t\t\tvar $campo = document.getElementById( '".$campo."' );\n\n";
			echo "\t\t\t\t\t\tif( !".$campo.".value )\n";
			echo "\t\t\t\t\t\t{\n";
			echo "\t\t\t\t\t\t\talert( 'El campo $nombre es requerido.' )\n";
			echo "\t\t\t\t\t\t\treturn ".$campo.".focus();\n";
			echo "\t\t\t\t\t\t}\n\n";
		}
						
		echo "\n\t\t\t\t\t\tif( confirm( '¿ Está seguro de ".$boton." ".$this -> mensaje." ?' ) )
						{
							state.value = '".$boton."';
							formulario.submit();
						}
					}
					catch( e )
					{
						alert( 'Error ' + e.message  );
					}
				}\n";
		echo "</script>";
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( $titulo, "100%" );
		$form -> Row();
		$form -> Label( array( "label" => $this -> columnas[1], "for" => $this -> campos[1] ) );
		$form -> TextField( array( "maxlenght" => "100", "size" => "30" , "name" => $this -> campos[1],  "value" => $_POST[$this -> campos[1]] ) );
		$form -> closeRow();		
		$form -> closePanel();	
		echo "<br/>";
		
		if( $boton )
			$form -> Button( $boton, array( "onclick" => "validarOpcion()" ) );

		$form -> Button( "Atras", array( "onclick" => "Atras()" ) );
		$form -> Hidden( array( "name" => "state", "value" => $state ) );
		$form -> Hidden( array( "name" => $this -> campos[0], "value" => $_POST[$this -> campos[0]] ) );
		$form -> closeForm();
	}
	
	function getListado()
	{
		$select = "SELECT ".implode( ",", $this -> campos )."
				   FROM ".$this -> tab."
				   ORDER BY ".$this -> order." ";
		
		$select = $this -> conexion -> Consultar( $select, "t", TRUE );
		
		return $select;
	}
	
	function Listar()
	{
		$select = $this -> getListado();
		
		$form = new Form( array( "name" => "form" ) );		
		
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td>";
		echo "<h2 style='text-align:left' >Lista de ".$this -> nombres."</h2>";
		echo "</td>";		
		echo "<td align=right>";
		echo "<a href='index.php?codOption=$_GET[codOption]&state=insert' class='linkNew' >";
		echo "<b><img src='images/add_icon.png' border=0 style='vertical-align:middle' >Agregar ".$this -> nombre."</b></a>";
		echo "</td>";		
		echo "</tr>";
		echo "</table>";		
		echo "<br>";		
		echo "<table width='100%' >";		
		echo "<tr>";
		echo "<td class='cellHead' colspan=".( sizeof( $this -> columnas ) + 1 )." >Se encontraron ".sizeof( $select )." ".$this -> nombres."</td>";		
		echo "</tr>";		
		echo "<tr>";
		if( $this -> columnas )
		foreach( $this -> columnas as $c )
			echo "<td class='cellHead'>".$c."</td>";
		echo "<td class='cellHead'>Opciones</td>";
		echo "</tr>";
		
		$i = 0;
		
		if( $select )
		foreach( $select as $row )
		{
			$i++;
			
			echo "<tr>";
			if( $this -> campos )
			foreach( $this -> campos as $c )			
			echo "<td class='cellInfo' align=left>".$row[$c]."</td>";			
			echo "<td class='cellInfo' align='center' nowrap>";
			echo "<a href='index.php?codOption=$_GET[codOption]&state=edit&".$this -> campos[0]."=$row[0]' title='Editar'>";
			echo "<img src='images/edit.png' border=0></a>&nbsp;";				
			echo "<a href='#' onclick='EliminarItem( \"$row[0]\", \"$row[1]\", \"".$this -> mensaje."\" )' title='Eliminar'>";
			echo "<img border=0 src='images/del.png'></a>";
			echo "</td>";			
			echo "</tr>";
		}		
		else
		{
			echo "<tr>";
			echo "<td class='cellInfo' align='left' colspan='8' >No se encontraron ".$this -> nombres." disponibles</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		$form -> Hidden( array( "name" => "codDelete", "value" => "" ) );
		$form -> closeForm();
	}
}

$admin = new Administracion( $this -> conexion );
?>