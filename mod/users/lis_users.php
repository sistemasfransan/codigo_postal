<?

class ListadoUsuarios
{
	var $conexion;
	
	function ListadoUsuarios( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		$this -> Listar();
	}	
	
	function getUsuarios()
	{
		$usuarios = "SELECT a.codUsuario, a.nomUsuario, 
							a.emaUsuario, b.nomPerfil, a.indEstado
				  	 FROM tab_usuarios a,
						  tab_perfiles b
				  	 WHERE a.codPerfil = b.codPerfil ";
		
		$usuarios = $this -> conexion -> Consultar( $usuarios, "i", TRUE );
		
		return $usuarios;
	}
	
	function Listar()
	{
		$usuarios = $this -> getUsuarios();
		
		echo "<h2 style='text-align:left' >Listado de Usuarios</h2>";
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td class='cellHead'>Login</td>";
		echo "<td class='cellHead'>Nombre Usuario</td>";
		echo "<td class='cellHead'>Email</td>";
		echo "<td class='cellHead'>Perfil</td>";
		echo "<td class='cellHead'>Estado</td>";
		echo "</tr>";
		
		if( $usuarios )
		foreach( $usuarios as $row )
		{
			if( !$row[4] ) $row[4] = "<span style='color:#900' >Desactivado</span>";
			else $row[4] = "Activo";
			
			echo "<tr>";
			echo "<td class='cellInfo' align='left'>$row[0]</td>";
			echo "<td class='cellInfo' align='left'>$row[1]</td>";
			echo "<td class='cellInfo' align='left'>$row[2]</td>";
			echo "<td class='cellInfo' align='left'>$row[3]</td>";
			echo "<td class='cellInfo' align='left'>$row[4]</td>";
			echo "</tr>";
		}			
		echo "</table>";
	}
}

$action = new ListadoUsuarios( $this -> conexion );

?>