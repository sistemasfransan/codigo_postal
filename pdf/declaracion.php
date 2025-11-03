<?

class PDF extends FPDF
{
	var $data = array();
	var $head = array();
	var $conexion = NULL;
	var $total = 0;
	
	var $codProfesor = ""; 
	
	function PDF()
	{
		parent::FPDF( 'P', 'mm', array( 220, 285 ) );
		$this -> conexion = new Conexion();
		$this -> AddFont('neue','','neue.php');
		$this -> AddFont('neue','I','neue.php');
		$this -> AddFont('neue','B','neue_bold.php');
	}
	 
	function Header()
	{
	}
	
	function Hoja1() 
	{
		//Logo.
		$this->SetTextColor(0,0,0);
		$this -> Image( $_SERVER[DOCUMENT_ROOT]."/".DIRAPP."/images/pdf/pdf1.png", 0, 0, 220 );
		
		$this -> SetXY( 115, 30 ); 
		$this -> SetTextColor( 200, 0, 0 );
		$this -> SetFont( 'neue', 'B', 25 );		
		$this -> Cell( 0, 20, "$_GET[davConsecutivo]", 0, 1, 'C' );
	}
	
	function Hoja2()
	{
		$this -> Image( $_SERVER[DOCUMENT_ROOT]."/".DIRAPP."/images/pdf/pdf2.png", 0, 0, 220 );
		
		$this -> SetXY( 115, 30 ); 
		$this -> SetTextColor( 200, 0, 0 );
		$this -> SetFont( 'neue', 'B', 25 );		
		$this -> Cell( 0, 20, "$_GET[davConsecutivo]", 0, 1, 'C' );
		
	}
	
	function Hoja3()
	{
		$this -> Image( $_SERVER[DOCUMENT_ROOT]."/".DIRAPP."/images/pdf/pdf3.png", 0, 0, 220 );
	}
	
	function Hoja4()
	{
		$this -> Image( $_SERVER[DOCUMENT_ROOT]."/".DIRAPP."/images/pdf/pdf4.png", 0, 0, 220 );
	}
	
	function Column( $label, $w="", $ln="" )
	{
		$this->SetDrawColor( 150, 150, 150 );
		$this->SetTextColor( 50, 50, 50 );
		$this->SetFillColor( 240, 240, 240 );
		$this->SetFont( 'neue','', 10 );
		$this->Cell( $w, 5, "$label", 1, $ln, 'C', 1 );
	}
	
	function Info( $label="", $w="", $ln="", $align = "C")
	{
		$this->SetDrawColor( 150, 150, 150 );
		$this->SetFillColor( 255, 255, 255 );
		$this->SetTextColor( 50, 50, 50 );	
		$this->SetFont( 'neue','', 9 );
		$this->Cell( $w, 5,"$label", 1, $ln, $align, 1 );
		$this->SetFillColor( 0, 0, 0 );
	}
	
	function Subti( $data, $ln, $w = "" , $h = "" )
	{
		$this->SetDrawColor( 150, 150, 150 );
		$this->SetTextColor( 255, 255, 255 ); 
		$this->SetFillColor( 129, 162, 61 );
		$this->SetFont( 'neue','B', 10 );
		$this->Cell( $w, $h, "$data", 1, $ln, 'L', 1 );
	}
	
	function Subti2( $data, $ln, $w = "" , $h = "" )
	{
		$this->SetDrawColor( 150, 150, 150 );
		$this->SetTextColor( 100, 100, 100 );
		$this->SetFillColor( 255, 255, 255 );
		$this->SetFont( 'neue','B', 9 );
		$this->Cell( $w, $h, "$data", 1, $ln, 'L', 1 );
	}
	
	function Label( $data, $ln, $w = "" , $h = "" )
	{
		$this->SetDrawColor( 150, 150, 150 );
		$this->SetTextColor( 50, 50, 50 );
		$this->SetFillColor( 240, 240, 240 );
		$this->SetFont( 'neue', '', 9 );
		$this->Cell( $w, $h, "$data", 1, $ln, 'R', 1 );
	}
	
	function Campo( $data, $ln, $w = "" , $h = "" )
	{
		$this->SetDrawColor( 150, 150, 150 );
		$this->SetTextColor( 100, 100, 100 );
		$this->SetFillColor( 255, 255, 255 );
		$this->SetFont( 'neue','', 9 );
		$this->Cell( $w, $h, "$data", 1, $ln, 'L', 1 );
	}
	
	function Celda( $label, $data, $ln )
	{
		$this->SetTextColor(255,255,255);
		$this->SetFillColor( 168, 164, 117 );
		$this->SetFont( 'neue','B', 20 );
		$this->Cell( 49, 10," $label: ", 1, 0, 'R', 1 );
		
		$this->SetTextColor( 0, 0, 0 );
		$this->SetFillColor( 11, 32, 115 );
		$this->SetFont( 'neue','', 20 );
		$this->Cell( 49, 5," $data ", 1, $ln, 'L' );
	}
	
	
	
	function cellTable( $ancho, $alto , $dato , $align , $x, $y )
	{
		$this->SetFont('neue','', 7 );
		$this -> SetXY( $x, $y );			
		$this->Cell( $ancho, ($alto *9) , "", 1, 1, 'L' );
		$this -> SetXY( $x+2, $y+2 );
		$this->MultiCell( $ancho-4, $alto,  $dato, 0, $align );
	}
	
	function TextArea( $data, $w = "" )
	{
		$this -> MultiCell( $w, 6, $data, 1, "J" );
	}
	
	function Moneda( $val )
	{
		$val = str_replace( ",", "", $val );
		$val = str_replace( ".", "", $val );
		$val = str_replace( " ", "", $val );	
		return "$ $val";
	}
	
	function Footer()
	{	
	}
}	

?>