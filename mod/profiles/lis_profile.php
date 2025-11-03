<?

class ListProfiles
{
	var $conexion;
	
	function ListProfiles( $conexion )
	{
		$this -> conexion = $conexion;
		$this -> Menu();
	}
	
	function Menu()
	{
		$this -> Listar();
	}	
	
	function getPefiles()
	{
		$perfiles = "SELECT a.codPerfil, a.nomPerfil, a.desPerfil, a.indEstado
				  	 FROM tab_perfiles a ";
		
		$perfiles = $this -> conexion -> Consultar( $perfiles, "i", TRUE );
		
		return $perfiles;
	}
	
	function Listar()
	{	
		$perfiles = $this -> getPefiles();
		echo "<h2 style='text-align:left' >Listado de Perfiles</h2>";
		echo "<br>";
		echo "<table width='100%' >";
		echo "<tr>";
		echo "<td class='cellHead'>Codigo</td>";
		echo "<td class='cellHead'>Nombre</td>";
		echo "<td class='cellHead'>Descripci&oacute;n</td>";
		echo "<td class='cellHead'>Estado</td>";
		echo "</tr>";
		
		if( $perfiles )
		foreach( $perfiles as $row )
		{
			if( !$row[3] ) $row[3] = "<span style='color:#900' >Desactivado</span>";
			else $row[3] = "Activo";
			
			echo "<tr>";
			echo "<td class='cellInfo' align='center'>$row[0]</td>";
			echo "<td class='cellInfo' align='left'>$row[1]</td>";
			echo "<td class='cellInfo' align='left'>$row[2]</td>";
			echo "<td class='cellInfo' align='center'><b>$row[3]</b></td>";
			echo "</tr>";
		}	
		
		echo "</table>";
	}
}

$action = new ListProfiles( $this -> conexion );

?>