// JavaScript Document
function Ajax( url, vars, id_div )
{
	$(document).ready(function()
	{
		$.ajax(
		{
			url: url,
			async:true,
			data: vars,
			beforeSend: function(objeto){//Antes de Ejecutar.
			//alert( "se envio " + vars );
			},
			success: function(datos)
			{
				$( "#" + id_div ).empty().html(datos);
			},
			complete: function( objeto, exito ){
			},
			contentType: "application/x-www-form-urlencoded",
			dataType: "html",
			error: function( objeto, quepaso, otroobj )
			{
				alert("Error de Ajax: " + quepaso );
			},
			type: "POST"
		});
	});
}

function change(id)
{
	try 
	{
		getMessage('php/' + id + '.php', '', 'dinamic', 'post');
	} 
	catch (e) 
	{
		alert("Error link " + e.message);
		return false;
	}
	return true;
}

function esMail(campo)
{
	try 
	{
		var correo = /^(.+\@.+\..+)$/;
		var id_campo = campo.id;
		var info = document.getElementById(id_campo + "Info");
		
		var val_campo = campo.value;
		
		if (!correo.test(val_campo)) 
		{
			info.className = 'error';
			info.innerHTML = 'El Campo debe ser correo XXX@xxx.com ';
			return false;
		}
		else 
			if (!campo.value) 
			{
				info.className = 'error';
				info.innerHTML = 'El Campo Es Requerido';
				return false;
			}
			else 
			{
				info.className = 'ok';
				info.innerHTML = 'Campo Correcto';
				return false;
			}
	} 
	catch (e) 
	{
		alert('Error' + e.message);
	}
}

function esMayorA( campo, min )
{
	try 
	{
		var id_campo = campo.id;
		var info = document.getElementById(id_campo + "Info");
		
		var val_campo = campo.value;
		
		if (val_campo.length < min) 
		{
			info.className = 'error';
			info.innerHTML = 'El Campo debe ser mayor de ' + min + ' caracteres.';
			return false;
		}
		else 
			if (!campo.value) 
			{
				info.className = 'error';
				info.innerHTML = 'El Campo Es Requerido';
				return false;
			}
			else 
			{
				info.className = 'ok';
				info.innerHTML = 'Campo Correcto';
				return false;
			}
	} 
	catch (e) 
	{
		alert('Error' + e.message);
	}
}

function esRequerido( campo )
{
	try 
	{
		var id_campo = campo.id;
		var info = document.getElementById( id_campo + "Info" );
		
		if (!campo.value) 
		{
			info.className = 'error';
			//info.innerHTML = 'El Campo Es Requerido';
			return false;
		}
		else 
		{
			info.className = 'ok';
			//info.innerHTML = 'Campo Correcto';
			return false;
		}
	} 
	catch (e) 
	{
		alert('Error' + e.message);
	}
}

function Atras()
{
	var formulario = document.getElementById( "form" );
	var state = document.getElementById( "state" );
	state.value = "";
	formulario.submit();
}



function numero(e)
{
	try 
	{
		var keyPressed = (e.which) ? e.which : event.keyCode;
		return (keyPressed >= 48 && keyPressed <= 57 || keyPressed == 8);
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function esNumero(campo)
{
	try 
	{
		if (isNaN(campo.value)) 
		{
			campo.value = '';
		}
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function decimal(e)
{
	try 
	{
		var keyPressed = (e.which) ? e.which : event.keyCode;
		
		//alert( keyPressed );
		return (keyPressed >= 48 && keyPressed <= 57 || keyPressed == 8 || keyPressed == 46 );
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function esDecimal(campo)
{
	try 
	{
            var valores = "0123456789.";
            var jovo = true;
            var caracter;
            for (i = 0; i < campo.value.length && jovo == true; i++) { 
                caracter = campo.value.slice(i, i+1); 
                if ( valores.indexOf( caracter ) == -1 ) {
                    jovo = false;
                }
            }
            if ( !jovo ) {
                campo.value = "";
            }
	} 
	catch (e) 
	{
		alert( 'Alert '+ e.message);
	}
}

function esTexto(campo)
{
	try 
	{
		if (!isNaN(campo.value)) 
		{
			campo.value = '';
		}
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function letras(e)
{
	try 
	{
		var keyPressed = (e.which) ? e.which : event.keyCode;
		return (keyPressed < 48 || keyPressed > 57);
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function esPlaca(campo)
{
	try 
	{
		if (tipo_placa == '2') 
		{
			var format_placa = /[a-zA-Z]{3}[0-9]{3}/;
			if (!format_placa.test(campo.value)) 
			{
				campo.value = "";
			}
		}
		else
		{
			var format_placa = /[a-zA-Z]{3}[0-9]{2}[a-zA-Z]{1}/;
			if (!format_placa.test(campo.value)) 
			{
				campo.value = "";
			}
		}
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function placa(e, campo)
{
	try 
	{
		var keyPressed = (e.which) ? e.which : event.keyCode;
		//alert( keyPressed );
		var letra = String.fromCharCode(keyPressed);
		var input = campo.value + letra;
		
		if (tipo_placa == '2') 
		{
			for (var i = 0; i < input.length; i++) 
			{
				if (i < 3) 
				{
					if (!isNaN(input.charAt(i)) && keyPressed != 8) 
					{
						return false;
					}
				}
				else 
				{
					if (isNaN(input.charAt(i)) && keyPressed != 8) 
					{
						return false;
					}
				}
				
				if (keyPressed == 32) 
					return false;
			}
		}
		else 
		{
			for (var i = 0; i < input.length; i++) 
			{
				if (i < 3) 
				{
					if (!isNaN(input.charAt(i)) && keyPressed != 8) 
					{
						return false;
					}
				}
				else if (i < 5) 
				{
					if (isNaN(input.charAt(i)) && keyPressed != 8) 
					{
						return false;
					}
				}
				else 
				{
					if (!isNaN(input.charAt(i)) && keyPressed != 8) 
					{
						return false;
					}
				}
				
				if (keyPressed == 32) 
					return false;
			}
		}
		return true;
	} 
	catch (e) 
	{
		//alert( 'Alert '+ e.message);
	}
}

function FormatoMoneda(input)
{
  var num = input.value.replace(/\./g,'');
  if(!isNaN(num)){
    num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    num = num.split('').reverse().join('').replace(/^[\.]/,'');
    input.value = num;
  }
  else{
    input.value = input.value.replace(/[^\d\.]*/g,'');
  }
}


function str_replace( o, n, s )
{
  while ( s.indexOf( o ) != -1 )
  {
    s = s.replace( o, n );
  }
  return s;
}

function openScreen ( url ) 
{
	var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=1050, height=600, top=50, left=50";
	window.open( url + "&popup=1" , "", opciones );
}

function EliminarItem( codigo, nombre, mensaje )
{
	try
	{
		var formulario = document.getElementById( "form" );
		var state = document.getElementById( "state" );
		var codDelete = document.getElementById( "codDelete" );
		
		if( confirm( "¿ Esta Seguro de Eliminar " + mensaje + " "+ nombre + " ? " ) )
		{
			state.value = "delete";
			codDelete.value = codigo;
			formulario.submit();
		}
	}
	catch( e )
	{
		alert( "Error " + e.message  );
	}
}