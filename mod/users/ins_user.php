<?

class InsertUser
{

    var $conexion;

    function InsertUser($conexion)
    {
        $this->conexion = $conexion;
        $this->Menu();
    }

    function Menu()
    {
        switch ( $_REQUEST[state])
        {
            case "regist":
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
                        fecCreacion, codEmpresa
                    ) 
                    VALUES 
                    (
                        '$_POST[codUsuario]', '$_POST[clvUsuario]', '$_POST[nomUsuario]', 
                        '$_POST[emaUsuario]', '$_POST[codPerfil]', '$usuario',
                        NOW(), '$_POST[codEmpresa]'
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
        $form->Panel("Registrar Nuevo Usuario", "100%");

        $form->Row();
        $form->Label(array("label" => "Nombre Usuario:", "for" => "nomUsuario"));
        $form->TextField(array("maxlenght" => "100", "size" => "30", "name" => "nomUsuario", "value" => $_POST[nomUsuario]));

        $form->Label(array("label" => "Login Usuario:", "for" => "codUsuario"));
        $form->TextField(array("maxlenght" => "20", "size" => "20", "name" => "codUsuario", "value" => $_POST[codUsuario], "onchange" => "form.submit()"));
        $form->closeRow();

        $form->Row();
        $form->Label(array("label" => "Clave Usuario:", "for" => "clvUsuario"));
        $form->Password(array("maxlenght" => "100", "size" => "30", "name" => "clvUsuario", "value" => $_POST[clvUsuario]));

        $form->Label(array("label" => "Confirmar Clave:", "for" => "clvUsuario2"));
        $form->Password(array("maxlenght" => "100", "size" => "30", "name" => "clvUsuario2", "value" => $_POST[clvUsuario2]));
        $form->closeRow();

        $form->Row();
        $form->Label(array("label" => "Perfil:", "for" => "codPerfil"));
        $form->ComboBox(array("name" => "codPerfil"), $this->getPerfiles(), $_POST[codPerfil]);
        $form->Label(array("label" => "Correo Usuario:", "for" => "emaUsuario"));
        $form->TextField(array("maxlenght" => "255", "size" => "30", "name" => "emaUsuario", "value" => $_POST[emaUsuario] ));
        $form->closeRow();
		
		$form->Row();
        $form->Label(array("label" => "Empresa:", "for" => "codEmpresa"));
        $form->ComboBox(array("name" => "codEmpresa"), $this -> getEmpresas(), $_POST[codEmpresa]);
        $form->closeRow();

        $form->closePanel();
		echo "<br/>";
        $form->Button("Registrar", array("onclick" => "ValidarUsuario( \"Registrar\" )"));
        $form->Hidden(array("name" => "state", "value" => ""));

        $form->closeForm();
    }
	
	function getEmpresas()
    {
        $perfiles = "SELECT a.codEmpresa, a.nomEmpresa
				  	 FROM tab_empresas a 
				  	 ORDER BY 2";

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
}

$action = new InsertUser($this->conexion);
?>