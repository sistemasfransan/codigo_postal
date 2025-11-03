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
	
	function getPefiles()
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
		$perfiles = $this -> getPefiles();
		
		echo "<h2 style='text-align:left' >Listado de Perfiles Disponibles para Actualizar</h2>";
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td class='cellHead'>Codigo</td>";
		echo "<td class='cellHead'>Nombre Perfil</td>";
		echo "<td class='cellHead'>Descripci&oacute;n</td>";
		echo "</tr>";
		
		if( $perfiles )
		foreach( $perfiles as $row )
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
			echo "<td class='cellInfo' align='left' colspan='3' >No se encontraron perfiles disponibles para Actualizar</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}
	
	function getInfoPerfil( $codPerfil )
	{
		$perfil = "SELECT a.codPerfil, a.nomPerfil, a.desPerfil
				   FROM tab_perfiles a 
				   WHERE a.codPerfil = '$codPerfil' AND
					 	 a.codPerfil != '1' ";
		
		$perfil = $this -> conexion -> Consultar( $perfil, "a" );
		return $perfil;
	}
	
	function Datos()
	{
		$perfil = $this -> getInfoPerfil( $_GET[codPerfil] );
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( "Actualizar Perfil ", "100%" );
		$form -> Row();
		$form -> Label( array( "label" => "Codigo Perfil:", "for" => "codPerfil" ) );
		$form -> TextField( array( "validate" => "n", "readonly" => "true", "value" => $_GET[codPerfil], "name" => "codPerfil" ) );
		$form -> Label( array( "label" => "Nombre Perfil:", "for" => "nomPerfil" ) );
		$form -> TextField( array( "maxlenght" => "100", "size" => "30", "name" => "nomPerfil" , "value" => $perfil[nomPerfil] ) );
		$form -> closeRow();
		
		$form -> Row();
		$form -> Label( array( "label" => "Descripci&oacute;n Perfil:", "for" => "desPerfil", "valign" => "top" ) );
		$form -> TextField( array( "maxlenght" => "100", "size" => "100" , "colspan" => "3", "name" => "desPerfil" , "value" => $perfil[desPerfil] ) );
		$form -> closeRow();
		$form -> closePanel();
		
		$modulos = $this -> getModulos();
		
		if( $modulos )
		{
			echo "<h2>Modulos</h2>";
			echo "<br>";
			echo "<div id='accordion'>";
			foreach( $modulos as $row )
			{
				echo "<h3 style='text-align:left' ><a href='#'>$row[nomOption]</a></h3>";
				echo "<div>";
				
				$options = $this -> getOptions( $row[codOption] );
				if( $options )
				{
					echo "<table width=100% >";
					echo "<tr>";
					
					$count = 0;
					
					foreach( $options as $i )
					{
						$check = $this -> getCheckOptions( $_GET[codPerfil], $i[codOption] );
						
						echo "<td align=right width='12.5%' nowrap class='tdPefil' >";
						echo "<label for='option[".$row[codOption]."][".$i[codOption]."]' >$i[nomOption]</label></td>";
						echo "<td align=left width='12.5%' nowrap  class='tdPefil' >";
						echo "<input type='checkbox' value='$i[codOption]' $check name='option[".$row[codOption]."][".$i[codOption]."]' id='option[".$row[codOption]."][".$i[codOption]."]' ></td>";
						
						$count++;
						
						if( $count == 4 )
						{
							$count = 0;
							echo "</tr>";
						}
					}
					if( $count )
					echo "</tr>";
					echo "</table>";
				}
				
				echo "</div>";	
			}
			echo "</div>";
		}
		echo "<br>";
		echo "<div align='center' >";
		$form -> Button( "Actualizar", array( "onclick" => "ValidarPerfil( \"Actualizar\" )" ) );
		$form -> Button( "Cancelar", array( "onclick" => "form.submit()" ) );
		echo "</div>";
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		$form -> closeForm();
	}
	
	function Actualizar()
	{
		$this -> conexion -> Start();
		$usuario = $_SESSION[session_user][codUsuari];
		
		$update = "UPDATE tab_perfiles
				   SET nomPerfil = '$_POST[nomPerfil]',
				   	   desPerfil = '$_POST[desPerfil]',
					   usrEdicion = '$usuario',
					   fecEdicion = NOW()
				   WHERE codPerfil = '$_POST[codPerfil]' ";
		
		$this -> conexion -> Consultar( $update );
		
		$delete = "DELETE FROM tab_permisos
				   WHERE codPerfil = '$_POST[codPerfil]' ";
		
		$this -> conexion -> Consultar( $delete );
		
		$i = 0;
		$options = $_POST[option];
		$llaves = array_keys( $_POST[option] );		
		foreach( $llaves as $row )
		{
			$modulo = "INSERT INTO tab_permisos 
						(
							codPerfil , codOption
						)
						VALUES 
						(
							'$_POST[codPerfil]',  '$row'
						)";
			
			$this -> conexion -> Consultar( $modulo );
		}
		
		foreach( $options as $row )
		{
			foreach( $row as  $option )
			{
				$option = "INSERT INTO tab_permisos 
							(
								codPerfil , codOption
							)
							VALUES 
							(
								'$_POST[codPerfil]',  '$option'
							)";
				
				$this -> conexion -> Consultar( $option );
			}
		}
		
		MessageOk( "El Perfil <b>$_POST[nomPerfil]</b> se Actualizo Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Actualizar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function getModulos()
	{
		$modulos = "SELECT a.nomOption, a.codOption
					FROM tab_options a
					WHERE a.codParent IS NULL ";
		
		$modulos = $this -> conexion -> Consultar( $modulos, "a", TRUE );
		
		return $modulos;
	}
	
	function getOptions( $codOption )
	{
		$options = "SELECT a.nomOption, a.codOption
					FROM tab_options a
					WHERE a.codParent = '$codOption' ";
		
		$options = $this -> conexion -> Consultar( $options, "a", TRUE );
		
		return $options;
	}
	
	function getCheckOptions( $codPerfil, $codOption )
	{
		$options = "SELECT 1
					FROM tab_permisos a
					WHERE a.codPerfil = '$codPerfil' AND
						  a.codOption = '$codOption' ";
		
		$options = $this -> conexion -> Consultar( $options, "a", TRUE );
		
		if( $options )
			return "checked";
		
		return "";
	}
}

$action = new UpdateProfile( $this -> conexion );

?>