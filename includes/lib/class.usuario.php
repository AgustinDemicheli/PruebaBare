<?
// Modulo de traducciones, para soportar el multilenguaje duro, es decir, de contenidos estaticos.

class usuario
{
	// Propiedades
	var $_id;		// ID de Usuario
	var $_nombre;		// Nombre
	var $_apellido;		// Apellido
	var $_usuario;		// Nombre de usuario
	var $_clave;		// Clave de Ingreso
	var $_telefono;		// Telefono de Contacto
	var $_activo;		// Esta habilitado ?
	var $_idioma;		// Idioma Seleccionado
	
	var $_logged;		// Booleana para comprobar el estado de logueo del usuario
	var $_id_intra;		// ID de Usuario
	var $_nombre_intra;	// Nombre
	var $_apellido_intra;	// Apellido
	var $_usuario_intra;	// Nombre de usuario
	var $_clave_intra;	// Clave de Ingreso
	var $_fecha_nac_intra;	// Telefono de Contacto
	var $_activo_intra;	// Esta habilitado ?
	var $_completo;		// Equipo completo
	
	var $_logged_intra;	// Booleana para comprobar el estado de logueo del usuario

	// Parte de los permisos, donde se almacena que puede y que no hacer este usuario.
	var $_paginas;		// Paginas que puede ver de la administracion.
	var $_campos;		// Campos que puede editar este chabon de la administracion
	var $_menu;		// ID del menu activo, para comprobar permisos de usuario
	var $_menuRightsEnabled;// Si al menu activo, se le aplican los permisos
	var $_menuRights;	// Permisos del usuario para el menu activo

	function usuario($usr="",$pass="",$site="")
	{
		
		if($site == "admin")
		{
			$this->logged = false;
			if($usr&&$pass) $this->login($usr,$pass,"admin");
		} 
		elseif($site == "intranet")
		{
			$this->logged_intra = false;
			if($usr&&$pass) $this->login($usr,$pass,"intranet");
		} 
		else
			return false;
	}

	function login($usr,$pass,$site)
	{
		global $conn;
		global $lang;

		
		if($site == "admin")
		{
			$sql = "select * from admin_usuarios where usuario = '".addslashes($usr)."' and clave = '".addslashes($pass)."' AND activo = 'S'";
			$rs = $conn->execute($sql);

			if($rs->numrows==1)
			{
				$this->_id        = $rs->field("id");
				$this->_nombre	  = $rs->field("nombre");
				$this->_apellido  = $rs->field("apellido");
				$this->_usuario   = $rs->field("usuario");
				$this->_clave     = $rs->field("clave");
				$this->_telefono  = $rs->field("telefono");
				$this->_activo    = $rs->field("activo");
				$this->_idioma    = $rs->field("lenguaje_id");
				$this->_grupo     = $rs->field("grupo_id");
				$this->_email     = $rs->field("email");
				$this->_grupodesc = $this->ObtenerTipo($this->_id);
				$this->_padre     = $rs->field("padre_id");

				$this->_logged   = true;

				$lang = new lenguaje($this->_idioma);
				$this->cargar_permisos();
			}
		}
		elseif($site == "intranet")
		{
			$sql = "select * from jue_usuarios where email = '".addslashes($usr)."' and password = '".addslashes($pass)."'"; //  (sacado "and activo = 'S'") 31/08/2006 por misha
			//echo $sql;die();
			$rs = $conn->execute($sql);

			if($rs->numrows==1)
			{
				$this->_id_intra        = $rs->field("id");
				$this->_nombre_intra	  = $rs->field("nombre");
				$this->_apellido_intra  = $rs->field("apellido");
				$this->_usuario_intra   = $rs->field("email");
				$this->_clave_intra     = $rs->field("password");
				$this->_activo_intra    = $rs->field("activo");
				$this->_pais_usuario    = $rs->field("id_pais");
				$this->_completo		= $rs->field("plantel_completo");
				$this->_nick			= $rs->field("nickname");
				$this->_equipo   		= $rs->field("nom_equipo");
				$this->_logged_intra   = true;

			}
		}
		else
		{
			$this->_logged   = false;
			$this->_logged_intra   = false;
		}

	}

	function cargar_permisos()
	{
		global $conn;
		$this->_paginas = array();
		$this->_campos  = array();

		// Permisos del menu activo
		if(isset($_GET['menu'])) {
			$_SESSION['sessMenu']			= $_GET['menu'];

			$this->_menu				= $_SESSION['sessMenu'];

			$recordset				= $conn->execute("select admin_menu.opciones from admin_menu where admin_menu.id = '" . $this->_menu . "'");

			$_SESSION['sessMenuRightsEnabled']	= $recordset->field('opciones');

			$this->_menuRightsEnabled		= $_SESSION['sessMenuRightsEnabled'];

			$recordset				= $conn->execute("select admin_usuarios_permisos.permisos from admin_usuarios_permisos inner join admin_menu on(admin_usuarios_permisos.menu_id = admin_menu.id) where admin_usuarios_permisos.menu_id = '" . $this->_menu . "' and admin_usuarios_permisos.usuario_id = '" . $this->_id . "' and admin_menu.opciones = 'S'");

			if($recordset->field("permisos") != '') {
				$_SESSION['sessMenuRights']	= strrev(decbin($recordset->field("permisos")));

				$this->_menuRights		= $_SESSION['sessMenuRights'];
			}else{
				$_SESSION['sessMenuRights']	= '-1';

				$this->_menuRights		= $_SESSION['sessMenuRights'];
			}
		}else{
			$this->_menu			= $_SESSION['sessMenu'];
			$this->_menuRightsEnabled	= $_SESSION['sessMenuRightsEnabled'];
			$this->_menuRights		= $_SESSION['sessMenuRights'];
		}

		// Deberiamos primero sacar los permisos del grupo
		$sql = "select
				am1.id as menu_id,am1.link
			from 
				admin_menu am1 
				left join admin_menu am2 on ( am2.permisosde = am1.id )
				left join admin_usuarios_menu aum on 
				(IF(am1.permisosde = 0 ,am1.id, am1.permisosde) = aum.menu_id ) 
			where 
				aum.usuario_id = ".$this->_id."
			group by am1.id";

		$rs = $conn->execute($sql);
		
		if($rs->numrows>0)
		{
			while(!$rs->eof)
			{
				array_push($this->_paginas,array("pagina"=>$rs->field("link"),"id"=>$rs->field("menu_id")));
				$rs->movenext();
			}
		}

		// Para los campos es a la inversa, deberiamos traer todos y eliminar los que no pueda ver, y marcar los que no pueda modificar.

		$sql = "select ac.*,at.nombre as tabla from admin_campos ac left join admin_tablas at on (ac.tabla_id = at.id) order by at.nombre ,ac.nombre";
		$rs  = $conn->execute($sql);

		if($rs->numrows>0)
		{
			while(!$rs->eof)
			{
				if(!is_array( $this->_campos[$rs->field("tabla")])) $this->_campos[$rs->field("tabla")] = array();
				array_push($this->_campos[$rs->field("tabla")],array("campo" => $rs->field("nombre")));
				$rs->movenext();
			}
		}

		// Ahora deberiamos sacar los campos que no van, y luego agregarle el flag de que si no lo puede editar pero si ver, para que las cosas se proceses como deberian ser.

		$sql = "select auc.*, at.nombre as tabla, ac.nombre as campo from admin_usuarios_campo auc left join admin_campos ac on (auc.campo_id = ac.id) left join admin_tablas at on (ac.tabla_id = at.id) where usuario_id = ".$this->_id;
		$rs  = $conn->execute($sql);

		if($rs->numrows>0)
		{
			while(!$rs->eof)
			{
				$tabla = $rs->field("tabla");
				$campo = $rs->field("campo");
				// Me fijo si el campo ese existe en el array de elementos y lo saco, o lo saco directamente porque seguro existe

				for($i=0;$i<count($this->_campos[$tabla]);$i++)
				{
					if($this->_campos[$tabla][$i]["campo"]==$campo)
					{
						$this->_campos[$tabla][$i]["visible"] = $rs->field("visible");
						$this->_campos[$tabla][$i]["editable"] = $rs->field("editable");
						break;
					}
				}
				$rs->movenext();
			}
		}
	}

	// Setters y Getters.
	function logueado($site) 
	{ 
		if($site == "admin")
			return $this->_logged; 
		elseif($site == "intranet")
			return $this->_logged_intra; 
		else
			return false;
		
	}

	function listapaginas($id=false,$separador=",")
	{
		// Si ID es true, se saca un listado de los id's de las paginas separadas por $separador, si es false, se saca un listado de 

		$rtnValue = "";
		if(count($this->_paginas)>0)
		{
			foreach($this->_paginas as $pagina)
			{
				if($id==true)
					$rtnValue .= $pagina["id"].$separador;
				else
					$rtnValue .= $pagina["pagina"].$separador;

			}

			$rtnValue = substr($rtnValue,0,strlen($rtnValue)-1);
		}else
			$rtnValue = " ";

		return $rtnValue;
	}

	function campo($tabla="",$campo="",$dato="")
	{
		// Esta funcion nos dice si puede ver el campo, si lo puede editar, o si no puede hacer nada
		if($tabla == "") return $this->_campos;
		if($campo == "") return $this->_campos[$tabla];

		if(count($this->_campos)>0)
			foreach($this->_campos[$tabla] as $c)
			{
				if(strtoupper($c["campo"])==strtoupper($campo))
				{
					if($dato=="") return $c;
					if($dato=="visible") return ($c["visible"]=='N'?false:true);
					if($dato=="editable"); return ($c["editable"]=='N'?false:true);
				}
			}
		return "";
	}

	// Funcion para obtener el tipo de usuario, no se necesita instanciar la clase para utilizarla, llamar usuario::ObtenerTipo($usuario_id)
	function ObtenerTipo($usuario_id)
	{
		global $conn;

		if(!is_numeric($usuario_id)) return false;

		$sql = "select agu.nombre from admin_usuarios au left join admin_grupo_usuarios agu on (au.grupo_id = agu.id) where au.id = ".$usuario_id;
		$rs = $conn->execute($sql);

		return $rs->field("nombre");
	}

	// Funcion para obtener el id del padre de este usuario, todo para saber si el que esta editando es el que lo creo y no otro administrador
	function ObtenerPadre($usuario_id)
	{
		global $conn;

		if(!is_numeric($usuario_id)) return false;

		$sql = "select au.padre_id from admin_usuarios where au.id = ".$usuario_id;
		$rs = $conn->execute($sql);

		return $rs->field("padre_id");

	}

	function permiso_ciudades($usuario_id)
	{
		global $conn;

		if(!is_numeric($usuario_id)) return false;

		$rs=$conn->execute("select id_ciudad from admin_usuarios_ciudad where id_usuario = $usuario_id ");
		
		if($rs->numrows > 0)
		{
			$where_ciudades = " in ( 0";
			while(!$rs->eof)
			{
				$where_ciudades .= ", ".$rs->field("id_ciudad");
				$rs->movenext();
			}
			$where_ciudades .= " ) ";
		}
		return $where_ciudades;
	}

	// Funcion para checkear si tiene permisos para realizar la accion debida
	function checkUserRights($rights, $content = '')
	{
		if($rights != '-1' && $this->_menuRightsEnabled == 'S') {
			if($this->_menuRights == '-1' or $this->_menuRights[(strlen(decbin($rights)) - 1)] != '1')
			{
				if($rights == '4' and $content != '') {
					global $conn;

					$recordset	= $conn->Execute("select usuario_id as usuario from admin_auditoria where menu_id = '" . $this->_menu . "' and contenido_id = '" . $content . "' order by fecha limit 1");

					if($recordset->field("usuario") != $this->_id) {
						return 'disabled';
					}
				}else{
					return 'disabled';
				}
			}
		}
	}
}

?>