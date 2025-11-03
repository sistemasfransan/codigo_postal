<ul id="nav">
<li><a href='index.php'>Inicio</a></li>
<? 

$codPerfil = $_SESSION[session_user][codPerfil];

$modulos = "SELECT a.nomOption, a.codOption
			FROM tab_options a,
				 tab_permisos b
			WHERE a.codParent IS NULL AND
				  a.codOption = b.codOption AND
				  b.codPerfil = '$codPerfil' 
			ORDER BY a.indOrden ";

$result = $this -> conexion -> Consultar( $modulos, "a", TRUE );

if( $result )
foreach( $result as $row )
{
	echo "<li><a href='#'>$row[nomOption]</a>";
	
	$modulos = "SELECT a.nomOption, a.codOption, a.rutOption
				FROM tab_options a,
					 tab_permisos b
				WHERE a.codParent = '$row[codOption]' AND
					  a.codOption = b.codOption AND
					  b.codPerfil = '$codPerfil' 
				ORDER BY a.indOrden ";

	$options = $this -> conexion -> Consultar( $modulos, "a", TRUE );
	
	if( $options )
	{
		echo "<ul>";
		foreach( $options as $option )
			echo "<li><a href='index.php?codOption=$option[codOption]' >&bull; $option[nomOption]</a></li>";
		echo "</ul>";
	}
	
	echo "</li>";
}
?>
<li><a href='logout.php' title="Salir" >Salir</a></li>
</ul>
