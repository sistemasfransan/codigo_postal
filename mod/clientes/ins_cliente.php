<?

class RegistrarCliente
{

    var $conexion;

    function RegistrarCliente($conexion)
    {
        $this->conexion = $conexion;
        $this->Menu();
    }

    function Menu()
    {
        switch ($_POST[state])
        {
            case "Registrar":
                $this->Registrar();
                break;
            default:
                $this->Form();
			break;
        }
    }

    function Registrar()
    {
        $this->conexion->Start();
        $usuario = $_SESSION[session_user][codUsuario];
        $_POST[clvUsuario] = md5($_POST[clvUsuario]);

        $insert = "INSERT INTO tab_usuarios 
                    (
                        codUsuario, clvUsuario, nomUsuario, 
                        emaUsuario, codPerfil, usrCreacion,
                        fecCreacion, codSitio
                    ) 
                    VALUES 
                    (
                        '$_POST[codUsuario]', '$_POST[clvUsuario]', '$_POST[nomUsuario]', 
                        '$_POST[emaUsuario]', '$_POST[codPerfil]', '$usuario',
                        NOW(), '$_POST[codSitio]'
                    );";

        $this->conexion->Consultar($insert);
        MessageOk("El Usuario <b>$_POST[nomUsuario]</b> se registro Exitosamente.");

        $this->conexion->Commit();

        $form = new Form(array("name" => "form"));
        $form->Button("Registrar Otro", array("onclick" => "form.submit()"));
        $form->closeForm();
    }

    function ValidarUsuario($codUsuario)
    {
        $valusuari = "SELECT 1
                     FROM tab_usuarios a 
                     WHERE codUsuario = '$codUsuario' ";

        $valusuari = $this->conexion->Consultar($valusuari, "i");

        if ($valusuari)
            return true;

        return false;
    }

    function Form()
    {
		$_POST[codUsuario] = str_replace( " ", "", $_POST[codUsuario] );
        if ($this->ValidarUsuario($_POST[codUsuario]))
        {
            MessageAlert("El Login <b>$_POST[codUsuario]</b> No se Encuentra Disponible.");
            $_POST[codUsuario] = "";
        }

        $form = new Form(array("name" => "form"));
        $form->Panel("Registrar Nuevo Cliente", "100%");
		
		$form->Row();
        $form->Label(array("label" => "Tipo Cliente:", "for" => "codTipo"));
        $form->ComboBox(array("name" => "codTipo"), $this->getTipoClientes(), $_POST[codTipo]);
		
		$form->Label(array("label" => "Vendedor:", "for" => "codUsuario"));
        $form->ComboBox(array("name" => "codUsuario"), $this->getVendedores(), $_POST[codUsuario]);       
        $form->closeRow();

		
        $form->Row();
        $form->Label(array("label" => "Nit Cliente:", "for" => "nitCliente"));
        $form->TextField(array("maxlength" => "20", "size" => "20", "validate" => "n", "name" => "nitCliente", "value" => $_POST[nitCliente]));

        $form->Label(array("label" => "Nombre Cliente:", "for" => "nomCliente"));
        $form->TextField(array("maxlength" => "100", "size" => "30", "name" => "nomCliente", "value" => $_POST[nomCliente]));
        $form->closeRow();
		
		
		$form->Row();
        $form->Label(array("label" => "Ciudad:", "for" => "codCiudad"));
        $form->ComboBox(array("name" => "codCiudad"), $this->getCiudades(), $_POST[codCiudad]);
		
		$form->Label(array("label" => "Direccion:", "for" => "dirCliente"));
        $form->TextField(array("maxlength" => "30", "size" => "25", "name" => "dirCliente", "value" => $_POST[dirCliente]));
       
        $form->closeRow();
		
		
		$form->Row();
		
        $form->Label(array("label" => "Telefono:", "for" => "telCliente"));
        $form->TextField(array("maxlength" => "10", "size" => "10", "validate" => "n", "name" => "telCliente", "value" => $_POST[telCliente]));
       		
		$form->Label(array("label" => "Celular:", "for" => "celCliente"));
        $form->TextField(array("maxlength" => "10", "size" => "10", "validate" => "n", "name" => "celCliente", "value" => $_POST[celCliente]));
       
        $form->closeRow();
		
		
		$form->Row();
		
        $form->Label(array("label" => "Web:", "for" => "webCliente"));
        $form->TextField(array("maxlength" => "20", "size" => "20", "name" => "webCliente", "value" => $_POST[webCliente]));
       
		$form->Label(array("label" => "Correo:", "for" => "corCliente"));
        $form->TextField(array("maxlength" => "20", "size" => "20", "name" => "corCliente", "value" => $_POST[corCliente]));
       
        $form->closeRow();
        

        $form->closePanel();
		echo "<br/>";
        $form->Button("Registrar", array("onclick" => "ValidarCliente( \"Registrar\" )"));
        $form->Hidden(array("name" => "state", "value" => ""));

        $form->closeForm();
    }
	
	function getSitios()
    {
        $perfiles = "SELECT a.codSitio, a.nomSitio
				  	 FROM tab_sitios a ";

        $perfiles = $this->conexion->Consultar($perfiles, "i", TRUE);
        $perfiles = array_merge(array(array("", "---")), $perfiles);

        return $perfiles;
    }
	
	function getTipoClientes()
    {
        $perfiles = "SELECT a.codTipo, a.nomTipo
				  	 FROM tab_tipcliente a 
					 ORDER BY nomTipo";

        $perfiles = $this->conexion->Consultar($perfiles, "i", TRUE);
        $perfiles = array_merge(array(array("", "---")), $perfiles);

        return $perfiles;
    }
	
	function getVendedores()
    {
        $perfiles = "SELECT a.codUsuario, a.nomUsuario
				  	 FROM tab_usuarios a 
					 ORDER BY nomUsuario";

        $perfiles = $this->conexion->Consultar($perfiles, "i", TRUE);
        $perfiles = array_merge(array(array("", "---")), $perfiles);

        return $perfiles;
    }

    function getPerfiles()
    {
        $perfiles = "SELECT a.codPerfil, a.nomPerfil
				  	 FROM tab_perfiles a 
					 WHERE a.indEstado = '1' ";

        $perfiles = $this->conexion->Consultar($perfiles, "i", TRUE);
        $perfiles = array_merge(array(array("", "---")), $perfiles);

        return $perfiles;
    }
	
	function getCiudades()
    {
        $perfiles = "SELECT a.codCiudad, a.nomCiudad
				  	 FROM tab_ciudades a 
					 ORDER BY nomCiudad";

        $perfiles = $this->conexion->Consultar($perfiles, "i", TRUE);
        $perfiles = array_merge(array(array("", "---")), $perfiles);

        return $perfiles;
    }
}

$action = new RegistrarCliente($this->conexion);
?>