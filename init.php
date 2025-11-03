<?php 
echo "<meta http-equiv='Content-Type' content='text/html; charset=".ENCODING."' />";
echo "\n<title>".TITLE."</title>";

$src_css = "css/";
$dir_css = dir( $src_css );
while( $css = $dir_css -> read() )
{
	if( $css != "." && $css != ".." && $css != "images" )
		echo "\n<link href='css/$css' rel='stylesheet' type='text/css' />";
}
$dir_css -> close();
?>
<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="js/script.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.timepicker.js" type="text/javascript"></script>
<script src="js/fn.js" type="text/javascript"></script>
<script src="js/es.js" type="text/javascript"></script>
<script src="js/jquery.dataTables.js" type="text/javascript"></script>
<script src="js/jquery.lightbox-0.5.js" type="text/javascript"></script>
<script src="js/colorpicker.js" type="text/javascript"></script>
<script src="js/menu.js" type="text/javascript"></script>
<script src="js/chosen.jquery.js" type="text/javascript"></script>
<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
<script type="text/javascript">

$(function()
{
	$('#listInfo a').lightBox();
	$('.fg-button').hover( function() { $(this).addClass('ui-state-hover'); },  function() { $(this).removeClass('ui-state-hover'); });
	$( "#accordion" ).accordion();		
});

jQuery(document).ready(function()
	{
		jQuery(".chzn-select").chosen({no_results_text: "No se encontraron resultados"}); 
	
	});


</script>
<?

if( !$_GET[codOption] ) $_GET[codOption] = $_POST[codOption];

if( $_GET[codOption] )
{
	$option = "SELECT a.rutOption
				FROM tab_options a
				WHERE a.codOption = '$_GET[codOption]' ";

	$option = $this -> conexion -> Consultar( $option, "a" );
	$option = $option[rutOption];
	$option = explode( "/", $option );
	$option = $option[1];
	
	echo "\n<script src='js/$option.js' type='text/javascript'></script>\n";	
}


if( !$_SESSION[session_user] )
{
?>
<script src="js/jquery.validationEngine-es.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
	jQuery(document).ready(function()
	{
		// binds form submission and fields to the validation engine
		jQuery("#formulario").validationEngine();
	});
</script>

<?
}

?>