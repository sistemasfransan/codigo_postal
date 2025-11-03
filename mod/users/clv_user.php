<?

class ChangePassword
{
	var $conexion;
	
	function ChangePassword( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		if( !$_GET[state] ) $_GET[state] = $_POST[state];
		
		switch( $_GET[state] )
		{
			case "regist":
				$this -> Actualizar();
			break;
			
			case "form":
				$this -> Datos();
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
				  	 WHERE a.indEstado = '1' AND
						    a.codPerfil = b.codPerfil ";
							
		if( $_SESSION[session_user][codPerfil] != 1 )
			$usuarios .= " AND a.codUsuario = '".$_SESSION[session_user][codUsuario]."' ";
		
		$usuarios = $this -> conexion -> Consultar( $usuarios, "i", TRUE );
		
		return $usuarios;
	}
	
	function Listar()
	{
		$usuarios = $this -> getUsuarios();
		
		echo "<h2 style='text-align:left' >Listado de Usuarios Disponibles para Cambiar Clave</h2>";
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
		echo "</table>";
	}
	
	function Datos()
	{
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( "Cambiar Clave", "100%" );
		
		$form -> Row();
		$form -> Label( array( "label" => "Clave Usuario:", "for" => "clvUsuario" ) );
		$form -> Password( array( "maxlenght" => "100", "size" => "30", "name" => "clvUsuario", "value" => $_POST[clvUsuario] ) );
		
		$form -> Label( array( "label" => "Confirmar Clave:", "for" => "clvUsuario2" ) );
		$form -> Password( array( "maxlenght" => "100", "size" => "30", "name" => "clvUsuario2", "value" => $_POST[clvUsuario2] ) );
		$form -> closeRow();	
		
		$form -> closePanel();
		
		$form -> Button( "Actualizar", array( "onclick" => "ValidarClave()" ) );
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		$form -> Hidden( array( "name" => "codUsuario", "value" => $_GET[codUsuario] ) );
		
		$form -> closeForm();
	}
	
	function Actualizar()
	{
		$this -> conexion -> Start();
		$usuario = $_SESSION[session_user][codUsuario];
		$_POST[clvUsuario] = md5( $_POST[clvUsuario] );
		
		$update = "UPDATE tab_usuarios
				   SET clvUsuario = '$_POST[clvUsuario]',
					   usrEdicion = '$usuario',
					   fecEdicion = NOW()
				   WHERE codUsuario = '$_POST[codUsuario]' ";
		
		$this -> conexion -> Consultar( $update );
		
		
		MessageOk( "La Clave se Actualizo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Actualizar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
}

$action = new ChangePassword( $this -> conexion );

?>