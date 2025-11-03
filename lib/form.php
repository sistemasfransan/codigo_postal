<?php
include "table.php";

class Form extends Table
{
	var $table = false;
	var $noForm = false;
	
	public function Form( $vars="" )
	{
		if( !$vars[id] ) $vars[id] = $vars[name];
		
		$this -> noForm = $vars[noform];
		
		echo "\n<div id='container' >";
		
		if( !$this -> noForm )
			echo "\n<form name='$vars[name]' onload='$vars[onload]' id='$vars[id]' method='post' enctype='multipart/form-data' action='index.php?codOption=$_GET[codOption]' >";		
		
		$table = true;		
	}
	
	public function Panel( $legend = '', $width = '', $id ="", $class = "fieldset" )
	{
		echo "\n<div class='$class' >";
		if( $legend ) 
			echo "\n<h2>$legend</h2>";
		
		parent::Table( array( "width" => $width, "id" => "$id" ) );
	}	
	
	public function PanelButton( $vars="" )
	{
		$vars = $this -> Generate( $vars );
		
		echo "\n<div class='action' $vars='' >";
	}	
	
	public function closePanel()
	{
		parent::Close();
		echo "\n</div>";		
	}
	
	public function closeForm()
	{
		
		if( !$this -> noForm )
		{
			echo "\n<div id='popupDIV' style='display:none' ></div>";
			echo "\n</form>";
		}
		echo "\n</div>";
	}
	
	public function TextArea( $vars="" )
	{
		$value = $vars[value];
		$vars[value] = NULL;
		
		$colspan = "";
		
		if( $vars[colspan] )
		{
			$colspan = " colspan='".$vars[colspan]."' ";
			$vars[colspan] = "";
		}
		
		if( !$vars[id] ) $vars[id] = $vars[name];
				
		$vars = $this -> Generate( $vars );	
		
		echo "\n<td nowrap $colspan class='tdInfo' >";
		echo "\n<textarea class='inputForm' $vars >$value</textarea>";
		echo "\n</td>";
	}
	
	public function TextField( $vars="" )
	{
		
		if( !$vars[id] ) $vars[id] = $vars[name];	
		
		$notd = $vars[notd];
		
		$colspan = "";
		
		if( $vars[validate] == 'n' ) 
		{
			$vars[onkeypress] = "return numero( event ); ".$vars[onkeypress];
			$vars[onchange] = 'esNumero( this ); '.$vars[onchange];
			$vars[style] .= ' text-align:right';
			
		}
		
		if( $vars[validate] == 'de' ) 
		{
			$vars[onkeypress] = "return decimal( event ); ".$vars[onkeypress];
			$vars[onchange] = 'esDecimal( this ); '.$vars[onchange];
			$vars[style] .= ' text-align:right';
			
		}
        elseif( $vars[validate] == 'm' ) 
		{
			$vars[onkeyup] = "FormatoMoneda( this ); ".$vars[onkeyup];
			$vars[onchange] = 'FormatoMoneda( this ); '.$vars[onchange];
			$vars[style] .= ' text-align:right';
		}
		elseif( $vars[validate] == 'l' )
		{
			$vars[onkeypress] = "return letras( event ); ".$vars[onkeypress];
			$vars[onchange] = 'esTexto( this ); '.$vars[onchange];
		}		
		elseif( $vars[validate] == 'd' )
		{
			echo "<script>";
			echo " $(function()
					{
						$( '#$vars[id]' ).datepicker();
					}); ";
			echo "</script>";
		}
		elseif( $vars[validate] == 'dt' )
		{
			echo "<script>";
			echo " $(function()
					{
						$( '#$vars[id]' ).timepicker();
					}); ";
			echo "</script>";
		}
		elseif( $vars[validate] == 'c' )
		{
			echo "<script>";
			echo " $(function()
					{
						$('#$vars[id]').ColorPicker(
						{
							onSubmit: function(hsb, hex, rgb, el) 
							{
								$(el).val(hex);
								$(el).ColorPickerHide();
							},
							onBeforeShow: function () 
							{
								$(this).ColorPickerSetColor(this.value);
							}
						})
						.bind('keyup', function()
						{
							$(this).ColorPickerSetColor(this.value);
						});
					}); ";
			echo "</script>";
		}
		
		
		$vars[validate] = "";		
		
		if( $vars['>'] )
		{
			$vars[onchange] = 'esMayorA( this, '.$vars['>'].' ); '.$vars[onchange];
			$vars['>'] = "";
		}			
			
		if( $vars['email'] )
		{
			$vars[onchange] = 'esMail( this ); '.$vars[onchange];
			$vars['email'] = "";
		}			
		
		if( $vars[colspan] )
		{
			$colspan = " colspan='".$vars[colspan]."' ";
			$vars[colspan] = "";
		}
		
		$vars = $this -> Generate( $vars );
		
		
		$classTD = "class='tdInfo'";
		
		if( $notd ) $classTD = "class='cellInfo'";
		
		echo "\n<td nowrap $colspan align='left' $classTD >";		
		echo "\n<input class='inputForm'  type='text' $vars >";
		echo "\n</td>";
	}
	
	public function Infor( $vars="" )
	{
		if( $vars[colspan] )
		{
			$colspan = " colspan='".$vars[colspan]."' ";
			$vars[colspan] = "";
		}
		
		if( !$vars[id] ) $vars[id] = $vars[name];
		$vars = $this -> Generate( $vars );
		
		echo "\n<td nowrap $colspan align='left' class='tdInfo' >";
		echo "\n<input class='inforForm' readonly type='text' $vars >";
		echo "\n</td>";
	}
	
	public function Hidden( $vars="" )
	{
		
		if( !$vars[id] ) $vars[id] = $vars[name];
		$vars = $this -> Generate( $vars );
		
		echo "\n<input type='hidden' $vars >";
	}
	
	
	
	public function Password( $vars="" )
	{
		if( !$vars[id] ) $vars[id] = $vars[name];
		$vars = $this -> Generate( $vars );
		
		echo "\n<td nowrap align='left' class='tdInfo'>";
		echo "\n<input class='inputForm' type='password' $vars >";
		echo "\n</td>";
	}
	
	public function ComboBox( $vars="", $options="", $selected = NULL )
	{
		if( !$vars[id] ) $vars[id] = $vars[name];
		
		$notd = $vars[notd];
		
		if( $vars[colspan] ) $colspan = " colspan='$vars[colspan]' ";
		
		$vars = $this -> Generate( $vars );
		
		$tdSelect = "class='tdInfo'";
		
		if( $notd )
			$tdSelect = " class='cellInfo' ";
		
		echo "\n<td nowrap $tdSelect align='left' $colspan  >";		
		echo "\n<select $vars class='chzn-select' >";
		
		if( $options )
		{
			foreach( $options as $row )
			{
				$sel = "";
				if( $row[0] == $selected ) $sel = " selected ";
				echo "\n<option value='$row[0]' $sel >$row[1]</option>";
			}
		}
		
		echo "\n</select>";
		echo "\n</td>";
	}
	
	public function Combo( $vars="", $options="", $selected = NULL )
	{
		if( !$vars[id] ) $vars[id] = $vars[name];
		$vars = $this -> Generate( $vars );
		
		echo "\n<td nowrap class='tdInfo' align='center' style='width:auto' >";
		echo "\n<select $vars class='selectForm' >";
		
		if( $options )
		{
			foreach( $options as $row )
			{
				$sel = "";
				if( $row[0] == $selected ) $sel = " selected ";
				echo "\n<option value='$row[0]' $sel >$row[1]</option>";
			}
		}
		
		echo "\n</select>";
		echo "\n</td>";
	}
	
	public function CheckBox( $vars="" )
	{
		$vars = $this -> Generate( $vars );
		echo "\n<td nowrap class='tdInfo' style='padding:10px' align=left >";
		echo "\n<input type='checkbox' $vars >";
		echo "\n</td>";
	}
	
	public function File( $vars="" )
	{
		$vars = $this -> Generate( $vars );
		echo "\n<td nowrap class='tdInfo'  >";
		echo "\n<input type='file' $vars >";
		echo "\n</td>";
	}
	
	public function Radio( $vars="")
	{
		$vars = $this -> Generate( $vars );
		
		echo "\n<input type='radio' $vars >";
	}
	
	public function Button( $label, $vars="" )
	{
		if( !$vars[value] ) $vars[value] = "Enviar";
		
		if( $vars[action] ) 
		{
			$vars[onclick] = " Ir( \"$vars[action]\",  \"$vars[code]\" ) ";
			$vars[action] = "";
			$vars[code] = "";
		}
		
		$vars = $this -> Generate( $vars );
		
		echo "<button class='fg-button ui-state-default ui-corner-all' type='button' $vars >$label</button>";
	}
	
	public function Label( $vars = "" )
	{
		echo "\n<td nowrap class='tdInfo' align='right' colspan='$vars[colspan]' >";
		echo "\n<label class='labelForm' title='$vars[title]' valign='$vars[valign]' for='$vars[for]' >";
		echo "\n$vars[label]";			
		echo "\n</label>";
		echo "\n</td>";
	}
	
	public function Row()
	{
		echo "\n<tr>";
	}
	
	public function closeRow()
	{
		echo "\n</tr>";
	}
	
	public function Cell( $vars = "" )
	{
		$vars = $this -> Generate( $vars );
		
		echo "\n<td nowrap $vars >";
	}
	
	public function closeCell()
	{
		echo "\n</td>";
	}
	
	public function Div( $vars = "", $html, $colspan ='' )
	{
		$vars = $this -> Generate( $vars );
		echo "\n<td nowrap colspan='$colspan' ><div $vars>$html</div></td>";
	}
	
	public function Image( $vars = "" )
	{
		$vars = $this -> Generate( $vars );
		
		echo "<img $vars >";
	}
	
	public function Grid( $title, $ajax, $columns )
	{
		echo '<script type="text/javascript">
			$(document).ready(function() {
				$(\'#example\').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"sAjaxSource": "mod/'.$ajax.'"
				} );
			} );
		</script>';
		
		
		echo "<h2>$title</h2>";
		echo "<br>";
		echo '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
              <thead>';
			 
		echo "<tr>"; 
		foreach( $columns as $row )
		{
			echo "<th>$row</th>";
		}
		
		echo "</tr>";
	echo '</thead>
              <tbody>
                <tr>
                  <td colspan="7" class="dataTables_empty">Loading data from server</td>
                </tr>
              </tbody>
            </table>';
	}
}
?>