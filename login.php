<?
if ($_SESSION[session_user])
{
    include( "mod/home.php" );
    echo "</div>";
    include( "footer.php" );
    echo "</div></div></body></html>";
    die();
}

if (isset($_POST[user]) && isset($_POST[pass]))
{
	echo "<div align=center >";
    MessageAlert("Ingreso Fallido, Verifique Usuario y Clave.");
	echo "</div>"; 
}
?>
<style>
#content
{
	background-color: #146DB4;
}
</style>
<div align=center >
<table border=0 width="100%" >
<tr>
<td align=center >
<form action="index.php" method="post" id="formulario" name="formulario" class="formular " >
	<table cellpadding=0 cellspacing=0 border=0 align=center width="100%">
        <tr><td><h2>Código Postal</h2></td>
        </tr>   
        <tr>
            <td class=cellLogin align=center >
                <input placeholder="Usuario" name='user' type='text' class="validate[required] text-input" id="user" maxlength="50" >
                <br/>
                <input placeholder="Contraseña" name='pass' type="password" class="validate[required] text-input" id="pass" maxlength="50" onKeyPress="return onEnterSubmit( this, event )" >
                <br>
                <div align="center">
                    <button class="fg-button ui-state-default ui-corner-all" type="button" onclick="ValidateLogin()" >Ingresar</button>
                </div>
            </td>
        </tr>
    </table>
</form>
</td>
</tr>
</table>
</div>
