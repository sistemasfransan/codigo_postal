<?

class DeshabilitarPerfil
{
	var $conexion;
	
	function DeshabilitarPerfil( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		if( !$_GET[state] ) $_GET[state] = $_POST[state];
		
		switch( $_GET[state] )
		{
			case "insert":
				$this -> Desactivar();
			break;
			
			case "form":
				$this -> Form();
			break;
			
			default:
				$this -> Listar();
			break;
		}
	}
	
	function getProfiles()
	{
		$perfiles = "SELECT a.codPerfil, a.nomPerfil, a.desPerfil
				  	 FROM tab_perfiles a  
				  	 WHERE a.indEstado = '1' AND
					 	   a.codPerfil != '1' ";
		
		$perfiles = $this -> conexion -> Consultar( $perfiles, "i", TRUE );
		
		return $perfiles;
	}
	
	
	
	function Listar()
	{
		$profiles = $this -> getProfiles();
		
		echo "<h2 style='text-align:left' >Listado de Perfiles Disponibles para Desactivar</h2>";
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td class='cellHead'>Codigo</td>";
		echo "<td class='cellHead'>Nombre Perfil</td>";
		echo "<td class='cellHead'>Descripci&oacute;n</td>";
		echo "</tr>";
		
		if( $profiles )
		foreach( $profiles as $row )
		{
			$row[0] = "<a href='index.php?codOption=$_GET[codOption]&state=form&codPerfil=$row[0]'>$row[0]</a>";
			echo "<tr>";
			echo "<td class='cellInfo' align='center'>$row[0]</td>";
			echo "<td class='cellInfo' align='left'>$row[1]</td>";
			echo "<td class='cellInfo' align='left'>$row[2]</td>";
			echo "</tr>";
		}	
		else
		{
			echo "<tr>";
			echo "<td class='cellInfo' align='left' colspan='3' >No se encontraron perfiles disponibles para Desactivar</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
	function Desactivar()
	{
		$this -> conexion -> Start();
		
		$usuario = $_SESSION[session_user][codUsuari];
		
		$update = "UPDATE tab_perfiles
				   SET indEstado = '0',
					   usrEdicion = '$usuario',
					   fecEdicion = NOW()
				   WHERE codPerfil = '$_POST[codPerfil]' ";
		
		$this -> conexion -> Consultar( $update );	
		
		
		MessageOk( "El Perfil <b>$_POST[nomPerfil]</b> se Desactivo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Desactivar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function getInfoProfile( $codPerfil )
	{
		$perfil = "SELECT a.codPerfil, a.nomPerfil, a.desPerfil
				   FROM tab_perfiles a 
				   WHERE a.codPerfil = '$codPerfil' AND
					 	 a.codPerfil != '1' ";
		
		$perfil = $this -> conexion -> Consultar( $perfil, "a" );
		return $perfil;
	}
	
	function Form()
	{
		$profile = $this -> getInfoProfile( $_GET[codPerfil] );
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( "Desactivar Perfil ", "100%" );
		$form -> Row();
		$form -> Label( array( "label" => "Codigo Perfil:", "for" => "codPerfil" ) );
		$form -> Infor( array( "validate" => "n", "readonly" => "true", "value" => $_GET[codPerfil], "name" => "codPerfil" ) );
		$form -> Label( array( "label" => "Nombre Perfil:", "for" => "nomPerfil" ) );
		$form -> Infor( array( "maxlenght" => "100", "size" => "30", "name" => "nomPerfil" , "value" => $profile[nomPerfil] ) );
		$form -> closeRow();
		
		$form -> Row();
		$form -> Label( array( "label" => "Descripci&oacute;n Perfil:", "for" => "desPerfil", "valign" => "top" ) );
		$form -> Infor( array( "maxlenght" => "100", "size" => "100" , "colspan" => "3", "name" => "desPerfil" , "value" => $profile[desPerfil] ) );
		$form -> closeRow();
		$form -> closePanel();
		
		
		echo "<br>";
		echo "<div align='center' >";
		$form -> Button( "Desactivar", array( "onclick" => "DesactivarPerfil()" ) );
		echo "</div>";
		$form -> Hidden( array( "name" => "state", "value" => "insert" ) );
		$form -> closeForm();
	}
}

$action = new DeshabilitarPerfil( $this -> conexion );

?>