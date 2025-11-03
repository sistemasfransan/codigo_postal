<?	
	$form = new Form( array( "name" => "form" ) );
	
	$form -> Panel( $titulo, "100%" );
	
	$form -> Row();
	$form -> Label( array( "label" => "* NIT Empresa: ", "for" => "nitEmpresa" ) );
	$form -> TextField( array( "name" => "nitEmpresa", "maxlength" => "20", "validate" => "n", "size" => "20", "value" => $_POST[nitEmpresa] ) );		
	$form -> Label( array( "label" => "* Razon Social: ", "for" => "nomEmpresa" ) );
	$form -> TextField( array( "maxlength" => "40", "size" => "40", "name" => "nomEmpresa" , "value" => $_POST[nomEmpresa]) );
	$form -> closeRow();

	$form -> Row();
	$form -> Label( array( "label" => "* Encargado: ", "for" => "gerEmpresa" ) );
	$form -> TextField( array( "name" => "gerEmpresa", "maxlength" => "30", "size" => "30", "value" => $_POST[gerEmpresa] ) );		
	$form -> Label( array( "label" => "* Direcci&oacute;n: ", "for" => "dirEmpresa" ) );
	$form -> TextField( array( "maxlength" => "50", "size" => "30", "name" => "dirEmpresa" , "value" => $_POST[dirEmpresa]) );
	$form -> closeRow();	
	
	$form -> Row();
	$form -> Label( array( "label" => "Telefono: ", "for" => "telEmpresa" ) );
	$form -> TextField( array( "name" => "telEmpresa", "maxlength" => "10", "validate" => "n", "size" => "10", "value" => $_POST[telEmpresa] ) );		
	$form -> Label( array( "label" => "Celular: ", "for" => "celEmpresa" ) );
	$form -> TextField( array( "maxlength" => "10", "size" => "10", "validate" => "n", "name" => "celEmpresa" , "value" => $_POST[celEmpresa]) );
	$form -> closeRow();	
	
	$form -> Row();
	$form -> Label( array( "label" => "* Correo: ", "for" => "corEmpresa" ) );
	$form -> TextField( array( "name" => "corEmpresa", "maxlength" => "50", "validate" => "", "size" => "30", "value" => $_POST[corEmpresa] ) );		
	$form -> Label( array( "label" => "* Ciudad: ", "for" => "ciuEmpresa" ) );
	$form -> ComboBox( array( "name" => "ciuEmpresa" ), $ciudades, $_POST[ciuEmpresa] );
	$form -> closeRow();

	$form -> Row();
	$form -> Label( array( "label" => "* Regimen: ", "for" => "codRegimen" ) );
	$form -> ComboBox( array( "name" => "codRegimen" ), $regimen, $_POST[codRegimen] );
	$form -> closeRow();	
	
	$form -> ClosePanel();
	
	$form -> Button( "Guardar", array( "onclick" => "ValidarDatos()" ) );
	
	$form -> Hidden( array( "name" => "state", "value" => $state ) );
	$form -> Hidden( array( "name" => "indAgregar" ) );
	$form -> Hidden( array( "name" => "indBorrar" ) );
	$form -> closeForm();
?>