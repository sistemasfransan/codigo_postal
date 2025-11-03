<?

class UpdateProfile
{
	var $conexion;
	
	function UpdateProfile( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		switch( $_REQUEST[state] )
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
		$usuarios = "SELECT *
				  	 FROM tab_usuarios a,
						  tab_empresas b,
						  tab_perfiles c
				  	 WHERE a.indEstado = '1' AND
						   a.codPerfil = c.codPerfil AND 
						   a.codEmpresa = b.codEmpresa "; 
		
		$usuarios = $this -> conexion -> Consultar( $usuarios, "a", TRUE );
		
		return $usuarios;
	}
	
	function Listar()
	{
		$usuarios = $this -> getUsuarios();
		
		echo "<h2 style='text-align:left' >Listado de Usuarios para Actualizar</h2>";
		echo "<br>";
		echo "<table width='100%' >";
		
			
		echo "<tr>";
		echo "<td class='cellHead' colspan='5' >Se encontraron ".sizeof( $usuarios )." usuarios.</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td class='cellHead'>Login</td>";
		echo "<td class='cellHead'>Nombre Usuario</td>";
		echo "<td class='cellHead'>Email</td>";
		echo "<td class='cellHead'>Perfil</td>";
		echo "<td class='cellHead'>Empresa</td>";
		echo "</tr>";
		
		
		
		if( $usuarios )
		foreach( $usuarios as $row )
		{
			$row[codUsuario] = "<a href='index.php?codOption=$_GET[codOption]&state=form&codUsuario=$row[codUsuario]'>$row[codUsuario]</a>";
			
			echo "<tr>";
			echo "<td class='cellInfo' align='left'>$row[codUsuario]</td>";
			echo "<td class='cellInfo' align='left'>$row[nomUsuario]</td>";
			echo "<td class='cellInfo' align='left'>$row[emaUsuario]</td>";
			echo "<td class='cellInfo' align='left'>$row[nomPerfil]</td>";
			echo "<td class='cellInfo' align='left'>$row[nomEmpresa]</td>";
			echo "</tr>";
		}			
		echo "</table>";
	}
	
	function getUsuarioInfo( $codUsuario )
	{
		$usuario = "SELECT *
				   FROM tab_usuarios a 
				   WHERE a.codUsuario = '$codUsuario' ";
		
		$usuario = $this -> conexion -> Consultar( $usuario, "a" );
		return $usuario;
	}
	
	function Datos()
	{
		if( !isset( $_POST[nomUsuario] ) )
		{
			$usuario = $this -> getUsuarioInfo( $_GET[codUsuario] );
			$_POST[nomUsuario] = $usuario[nomUsuario];
			$_POST[codUsuario] = $usuario[codUsuario];
			$_POST[codPerfil] = $usuario[codPerfil];
			$_POST[emaUsuario] = $usuario[emaUsuario];
			$_POST[codSitio] = $usuario[codSitio];
		}
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( "Actualizar Usuario", "100%" );
		
		$form -> Row();
		$form -> Label( array( "label" => "Nombre Usuario:", "for" => "nomUsuario" ) );
		$form -> TextField( array( "maxlenght" => "100", "size" => "30", "name" => "nomUsuario", "value" => $_POST[nomUsuario] ) );
		
		$form -> Label( array( "label" => "Login Usuario:", "for" => "codUsuario" ) );
		$form -> TextField( array( "maxlenght" => "20", "size" => "20", "name" => "codUsuario", "value" => $_POST[codUsuario], "readonly" => "true" ) );
		$form -> closeRow();

		$form -> Row();
		$form -> Label( array( "label" => "Perfil:", "for" => "codPerfil" ) );
		$form -> ComboBox( array( "name" => "codPerfil" ), $this -> getPerfiles(), $_POST[codPerfil] );
		$form -> Label( array( "label" => "Correo Usuario:", "for" => "emaUsuario" ) );
		$form -> TextField( array( "maxlenght" => "255", "size" => "30", "name" => "emaUsuario", "value" => $_POST[emaUsuario] ) );
		$form -> closeRow();	
		
		$form->Row();
        $form->Label(array("label" => "Empresa:", "for" => "codEmpresa"));
        $form->ComboBox(array("name" => "codEmpresa", "style" => "width:250px" ), $this -> getEmpresas(), $_POST[codEmpresa]);
        $form->closeRow();
		
		$form -> closePanel();
		
		$form -> Button( "Actualizar", array( "onclick" => "ValidarUsuario( \"Actualizar\" )" ) );
		$form -> Button( "Cancelar", array( "onclick" => "form.submit()" ) );
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		
		$form -> closeForm();
	}
	
	
	function getEmpresas()
	{
		$sitios = "SELECT a.codEmpresa, a.nomEmpresa
				   FROM tab_empresas a 
				   ORDER BY 2";
		
		$sitios = $this -> conexion -> Consultar( $sitios, "i", TRUE );
		$sitios = array_merge( array( array( "", "---" ) ), $sitios ) ;
		
		return $sitios;
	}
	
	function Actualizar()
	{
		$this -> conexion -> Start();
		$usuario = $_SESSION[session_user][codUsuario];
		
		$update = "UPDATE tab_usuarios
				   SET nomUsuario = '$_POST[nomUsuario]',
				   	   emaUsuario = '$_POST[emaUsuario]',
					   codPerfil = '$_POST[codPerfil]',
					   codEmpresa = '$_POST[codEmpresa]',
					   usrEdicion = '$usuario',
					   fecEdicion = NOW()
				   WHERE codUsuario = '$_POST[codUsuario]' ";
		
		$this -> conexion -> Consultar( $update );
		
		MessageOk( "El Usuario <b>$_POST[nomUsuario]</b> se Actualizo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Actualizar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function getProcesos()
	{
		$select = "SELECT a.codProceso, a.nomProceso
				   FROM tab_procesos a 
				   WHERE a.indEstado = '1' ";
		
		$select = $this -> conexion -> Consultar( $select, "i", TRUE );
		$select  = array_merge( array( array( "", "---" ) ), $select  );
		
		return $select;
	}
	
	function getPerfiles()
	{
		$perfiles = "SELECT a.codPerfil, a.nomPerfil
				  	 FROM tab_perfiles a 
					 WHERE a.indEstado = '1' ";
		
		$perfiles = $this -> conexion -> Consultar( $perfiles, "i", TRUE );
		$perfiles  = array_merge( array( array( "", "---" ) ), $perfiles  );
		
		return $perfiles;
	}	
}

$action = new UpdateProfile( $this -> conexion );

?>