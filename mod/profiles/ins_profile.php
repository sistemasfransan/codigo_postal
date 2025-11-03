<?

class InsertProfile
{
	var $conexion;
	
	function InsertProfile( $conexion )
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
				$this -> Registrar();
			break;
			default:
				$this -> Form();
			break;
		}
	}
	
	function Registrar()
	{
		$this -> conexion -> Start();
		$usuario = $_SESSION[session_user][codUsuari];
		
		$insert = "INSERT INTO tab_perfiles
					(
						codPerfil , nomPerfil , desPerfil,
						indEstado , usrCreacion , fecCreacion
					)
					VALUES 
					(
						'$_POST[codPerfil]' , '$_POST[nomPerfil]' , '$_POST[desPerfil]' ,
						'1', '$usuario' , NOW()
					)";
		
		$this -> conexion -> Consultar( $insert );
		
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
		
		MessageOk( "El Perfil <b>$_POST[nomPerfil]</b> se registro Exitosamente." );
		
		$this -> conexion -> Commit();
		
		$form = new Form( array( "name" => "form" ) );
		$form -> Button( "Registrar Otro", array( "onclick" => "form.submit()" ) );
		$form -> closeForm();
	}
	
	function Form()
	{
		$form = new Form( array( "name" => "form" ) );
		$form -> Panel( "Registrar Perfil Nuevo", "100%" );
		$form -> Row();
		$form -> Label( array( "label" => "Codigo Perfil:", "for" => "codPerfil" ) );
		$form -> TextField( array( "validate" => "n", "readonly" => "true", "value" => $this -> getCodigo(), "name" => "codPerfil" ) );
		$form -> Label( array( "label" => "Nombre Perfil:", "for" => "nomPerfil" ) );
		$form -> TextField( array( "maxlenght" => "100", "size" => "30", "name" => "nomPerfil" ) );
		$form -> closeRow();
		
		$form -> Row();
		$form -> Label( array( "label" => "Descripci&oacute;n Perfil:", "for" => "desPerfil", "valign" => "top" ) );
		$form -> TextField( array( "maxlenght" => "100", "size" => "100" , "colspan" => "3", "name" => "desPerfil" ) );
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
						echo "<td align=right width='12.5%' nowrap class='tdPefil' >";
						echo "<label for='option[".$row[codOption]."][".$i[codOption]."]' >$i[nomOption]</label></td>";
						echo "<td align=left width='12.5%' nowrap  class='tdPefil' >";
						echo "<input type='checkbox' value='$i[codOption]'  name='option[".$row[codOption]."][".$i[codOption]."]' id='option[".$row[codOption]."][".$i[codOption]."]' ></td>";
						
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
		$form -> Button( "Registrar", array( "onclick" => "ValidarPerfil( \"Registrar\" )" ) );
		echo "</div>";
		$form -> Hidden( array( "name" => "state", "value" => "" ) );
		$form -> closeForm();
	}
	
	function getCodigo()
	{
		$codigo = "SELECT MAX( a.codPerfil ) as codigo
				   FROM tab_perfiles a ";
		
		$codigo = $this -> conexion -> Consultar( $codigo, "a" );
		
		if( !$codigo[codigo] ) $codigo[codigo] = 1;
		else $codigo[codigo]++;
		
		return $codigo[codigo];
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
}

$action = new InsertProfile( $this -> conexion );

?>