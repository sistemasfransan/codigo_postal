<?
class Table
{
	public function Table( $vars="" )
	{
		if( $vars['class'] ) $vars['class'] = "class='$vars[class]'";
		
		if( !$vars[width] ) $vars[width] = "100%";
		
		echo "\n<table cellpadding='0' cellspacing='0' border='0' width='$vars[width]' $vars[class] id='$vars[id]' >";
	}
	
	public function Close()
	{
		echo "\n</table>";
	}
	
	public function tr( $html="" )
	{
		if( $html )
			echo "\n<tr>$html</tr>";
		else
			echo "\n<tr>";
	}
	
	public function closeTr()
	{
		echo "\n</tr>";
	}
	
	public function th( $html="" )
	{
		if( $html )
			echo "\n<th>$html</th>";
		else
			echo "\n<th>";
	}
	
	public function closeTh()
	{
		echo "\n</th>";
	}
	
	public function td( $html="" )
	{
		if( $html )
			echo "\n<td>$html</td>";
		else
			echo "\n<td>";
	}
	
	public function closeTd()
	{
		echo "\n</td>";
	}
	
	public function Generate( $array = "" )
	{
		$str = " ";
		
		if( is_array( $array ) )
		{
			for( $i = 0; $i < sizeof( $array ); $i++ )
			{
				$row = current( $array );	
				
				if( $row )
				{
					$key = key( $array );
					$str .= "$key='$row' ";
				}
				
				next( $array );
			}
		}
		
		return $str;
	}
	
	public function ln()
	{
		echo "\n<br>";
	}
}
?>