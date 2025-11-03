<?

class HabilitarUsuario
{
	var $conexion;
	
	function HabilitarUsuario( $conexion )
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
				$this -> Activar();
			break;
			
			case "form":
				$this -> Form();
			break;
			
			default:
				$this -> Listar();
			break;
		}
	}
	
	function getUsuarios()
	{
		$usuarios = "SELECT a.codUsuario, a.nomUsuario, 
							a.emaUsuario, b.nomPerfil
				  	 FROM tab_usuarios a,
						  tab_perfiles b
				  	 WHERE a.indEstado = '0' AND
						   a.codPerfil = b.codPerfil ";
		
		$usuarios = $this -> conexion -> Consultar( $usuarios, "i", TRUE );
		
		return $usuarios;
	}
	
	function Listar()
	{
		$usuarios = $this -> getUsuarios();
		
		echo "<h2 style='text-align:left' >Listado de Usuarios para Activar</h2>";
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td class='cellHead'>Login</td>";
		echo "<td class='cellHead'>Nombre Usuario</td>";
		echo "<td class='cellHead'>Email</td>";
		echo "<td class='cellHead'>Perfil</td>";
		echo "</tr>";
		
		if( $usuarios )
		foreach( $usuarios as $row )
		{
			$row[0] = "<a href='index.php?codOption=$_GET[codOption]&state=form&codUsuario=$row[0]'>$row[0]</a>";
			echo "<tr>";
			echo "<td class='cellInfo' align='left'>$row[0]</td>";
			echo "<td class='cellInfo' align='left'>$row[1]</td>";
			echo "<td class='cellInfo' align='left'>$row[2]</td>";
			echo "<td class='cellInfo' align='left'>$row[3]</td>";
			echo "</tr>";
		}
		else
		{
			echo "<tr>";
			echo "<td class='cellInfo' align='left' colspan='3' >No se encontraron Usuarios disponibles para Activar</td>";
			echo "</tr>";
		}		
		echo "</table>";
	}
	
	function Activar()
	{
		$this -> conexion -> Start();
		
		$usuario = $_SESSION[session_user][codUsuario];
		
		$update = "UPDATE tab_usuarios
				   SET indEstado = '1',
					   usrEdicion = '$usuario',
					   fecEdicion = NOW()
				   WHERE codUsuario = '$_POST[codUsuario]' ";
		
		$this -> conexion -> Consultar( $update );	
		
		
		MessageOk( "El Usuario <b>$_POST[nomUsuario]</b> se Activo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Activar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function Form()
	{
		$usuario = $this -> getUsuarioInfo( $_GET[codUsuario] );
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( "Activar Usuario", "100%" );
		
		$form -> Row();
		$form -> Label( array( "label" => "Nombre Usuario:", "for" => "nomUsuario" ) );
		$form -> Infor( array( "maxlenght" => "100", "size" => "30", "name" => "nomUsuario", "value" => $usuario[nomUsuario] ) );
		
		$form -> Label( array( "label" => "Login Usuario:", "for" => "codUsuario" ) );
		$form -> Infor( array( "maxlenght" => "20", "size" => "20", "name" => "codUsuario", "value" => $usuario[codUsuario], "readonly" => "true" ) );
		$form -> closeRow();

		$form -> Row();
		$form -> Label( array( "label" => "Perfil:", "for" => "codPerfil" ) );
		$form -> Infor( array( "maxlenght" => "100", "size" => "30", "name" => "nomPerfil", "value" => $usuario[nomPerfil] ) );
		$form -> Label( array( "label" => "Correo Usuario:", "for" => "emaUsuario" ) );
		$form -> Infor( array( "maxlenght" => "100", "size" => "30", "name" => "emaUsuario", "value" => $usuario[emaUsuario] ) );
		$form -> closeRow();			
		
		$form -> closePanel();
		
		echo "<br>";
		echo "<div align='center' >";
		$form -> Button( "Activar", array( "onclick" => "ActivarUsuario()" ) );
		echo "</div>";
		$form -> Hidden( array( "name" => "state", "value" => "insert" ) );
		$form -> closeForm();
	}
	
	function getUsuarioInfo( $codUsuario )
	{
		$usuario = "SELECT a.codUsuario, a.nomUsuario, a.emaUsuario, b.nomPerfil
				  	 FROM tab_usuarios a,
						  tab_perfiles b
				  	 WHERE a.codUsuario = '$codUsuario' AND
						   a.codPerfil = b.codPerfil ";
		
		$usuario = $this -> conexion -> Consultar( $usuario, "a" );
		return $usuario;
	}
}

$action = new HabilitarUsuario( $this -> conexion );

?>