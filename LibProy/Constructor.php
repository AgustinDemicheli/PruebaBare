<?php

/**
 * clase abstracta de consultas sqls
 * Gustavo Fisache
 * 2009 - 2011
 *  */
abstract class Constructor {

    protected $id;              //valor del campo primario
    protected $restaurado;           //luego de restaurar se seta a true
    private $campos;              //array de campos de la consulta
    private $valores;              //array donde se va a insertar todos los valores luego de restaurar
    private $tabla;              //entidad o nombre de la tabla
    private $condicion;
    private $condicion_inicial = " activo='S' AND estado='A' ";
    private $campo_id = "id";            //nombre del campo primario de la tabla "ID"
    private $join;
    private $campos_tabla;            //campos de la tabla origen
    private $indexAsterisco = "";           //si se invoca el * para traer todos los campos de la tabla
    private $imagenes = array();           //las imagene que querramos que se genere el thumbs
    private $test = false;

    /*
     * Contructor del la clase que tiene que ser heredada
     */

    public function Constructor($tabla, $campos, $id = 0) {
        $this->tabla = trim($tabla);
        $this->campos = $campos;
        if ($id) {
            $this->id = $id;
        }

        /*         * traigo todos los campos locales de la tabla para poder modificar* */
        $index = 0;
        foreach ($this->campos as $key => $valor) {

            if ($key == $this->tabla . ".*" || $key == "*") {
                $this->indexAsterisco = $index;
            }
            $index++;
        }
    }

    /**
     * Setea el id para ser restaurado
     * @param unknown_type $id
     */
    public function SetId($id) {
        $this->id = $id;
    }

    /**
     * es el nombre de la clave primaria, en casi todos los casos es id.
     * @param $nombre_campo
     */
    public function SetCampoId($nombre_campo) {
        $this->campo_id = $nombre_campo;
    }

    /**
     * esto es para los campos de activo y estado, a veces no existen esto campos.
     * @param string $condicion_inicial
     */
    public function SetCondicionInicial($condicion_inicial) {
        $this->condicion_inicial = $condicion_inicial;
    }

    /**
     * setea otra condicion además de las comunes
     * @param unknown_type $condicion
     */
    public function SetCondicion($condicion) {
        //otras condiciones, aparte de estado y activo
        $this->condicion = $condicion;
    }

    /**
     * join del select.
     * @param unknown_type $join
     */
    public function SetJoin($join) {
        $this->join = $join;
        $this->campo_id = $this->tabla . "." . $this->campo_id;
        $this->condicion_inicial = " " . $this->tabla . ".activo='S' AND " . $this->tabla . ".estado='A' ";
    }

    /**
     * Trae el registro, detallado por el id
     * @param int $id
     * @param boolean $sacarCondicionInicial
     */
    public function Restaurar($id = "", $sacarCondicionInicial = false) {

        if ($id) {
            $this->SetId($id);
        }
        if ($this->id) {

            global $conn;
            $campos = "";

            foreach ($this->campos as $key => $valor) {

                if ($valor != "") {
                    $campos .=$key . " as " . $valor . ",";
                } else {
                    $campos .=$key . ",";
                }
            }

            $campos = substr($campos, 0, -1);

            $sql = "SELECT " . $campos . "
			    					  FROM " . $this->tabla . " " . $this->join . "
			    					  WHERE " . $this->campo_id . "='" . $this->id . "'
			    					  " . (!$sacarCondicionInicial ? " AND " . $this->condicion_inicial : $otracondicion) . " " . ($this->condicion != "" ? " AND " . $this->condicion : "");

            $rs = $conn->Execute($sql);

            if ($this->test == true) {
                echo $sql;
            }

            if (!$rs->eof) {

                while (!$rs->eof && $rs != false) {

                    foreach ($this->campos as $key => $valor) {
                        //si hay *, busco todos los campos y valores
                        if ($key != $this->tabla . ".*" && $key != "*") {


                            if ($valor != "") {

                                $this->valores[$valor] = stripslashes($rs->Field($valor));
                                if ($rs->Field($valor) != "") {
                                    $indeximagen = $this->existeEnArrayImagenes($valor);
                                    $Vindeximagen = explode(",", $indeximagen);
                                    for ($ff = 0; $ff < count($Vindeximagen); $ff++) {
                                        if ($Vindeximagen[$ff] != "") {
                                            $this->valores[$valor . "_" . $ff] = Multimedia::GetImagenStatic($this->imagenes[$Vindeximagen[$ff]][$valor][0]['width'], $this->imagenes[$Vindeximagen[$ff]][$valor][1]['height'], $rs->Field($valor));
                                        }
                                    }
                                }
                            } else {
                                $this->valores[$key] = stripslashes($rs->Field($key));
                            }
                        } else {
                            unset($this->campos[$key]);
                            unset($this->valores[$key]);
                        }
                    }

                    $rs->MoveNext();
                }

                if ($this->indexAsterisco > 0) {

                    $rs->MoveFirst();
                    $xx = array();
                    for ($k = 0; $k < $rs->numfields; $k++) {
                        $xx[$rs->fieldname[$k]] = $rs->Field($rs->fieldname[$k]);
                    }

                    while (!$rs->eof && $rs != false) {
                        //si hay *, busco todos los campos y valores
                        foreach ($xx as $key => $valor) {
                            if (!array_key_exists($key, $this->valores)) {
                                $this->campos[$this->tabla . "." . $key] = $key;
                                $this->valores[$key] = $valor;
                            }
                        }
                        $rs->MoveNext();
                    }
                }
                //este array se usa ppara poder modificar (update)
                foreach ($this->campos as $key => $valor) {
                    if (strstr($key, $this->tabla . ".") != "") {
                        $this->campos_tabla[$valor] = $this->valores[$valor];
                    }
                }
            }

            $this->restaurado = true;
        } else {
            $this->restaurado = false;
            try {
                throw new MyException("Debe setear el ID antes de retaurar, acceda a la funcion SetId(x)");
            } catch (MyException $e) {
                $e->getError();
            }
        }
    }

    //Trae el valor del campo o el valor de cada campo restaurado
    /**
     * si no se setea el nombre del campo, trae todos los valores (el campo coincide con el "as xxx")
     * @param strimg $nombreCampo opcional
     */
    public function GetValor($nombreCampo = "") {

        if ($this->restaurado) {

            if ($nombreCampo != "") {
                return $this->valores[$nombreCampo];
            } else {
                return $this->valores;
            }
        } else {
            try {
                throw new MyException("Debe restaurar los datos, acceda a la funcion SetId(x) y luego Restaurar()");
            } catch (MyException $e) {
                $e->getError();
            }
        }
    }

    /**
     * trae registros de la tabla
     * @param int $limit
     * @param string $order
     * @param boolean $paginar
     * @param int $registros_x_pagina
     * @param int $cant_paginas
     * @param int $pagina_actual
     * @param string $filtro
     * @param boolean $amigable
     */
    public function Lista($limit = 0, $order = "id DESC", $paginar = false, $registros_x_pagina = 0, $cant_paginas = 0, $pagina_actual = 1, $filtro = "", $amigable = false) {

        global $conn;
        $campos = "";

        foreach ($this->campos as $key => $valor) {

            if ($valor != "") {
                $campos .=$key . " as " . $valor . ",";
            } else {
                $campos .=$key . ",";
            }
        }

        $campos = substr($campos, 0, -1);

        $consulta = "SELECT " . $campos . "
	    					  FROM " . $this->tabla . " " . $this->join . "
	    					  WHERE " . $this->condicion_inicial . " " . ($this->condicion != "" ? " AND " . $this->condicion : "") . " " . ($filtro != "" ? " AND " . $filtro : "") . "
	    					  ORDER BY " . $order .
                ($limit ? " LIMIT " . $limit : "");

        if ($paginar == false) {

            $retorno = array();
            if ($this->test == true) {
                echo $consulta;
            }

            $rs = $conn->Execute($consulta);
            $i = 0;
            while (!$rs->eof) {
                $indeximagen = -1;
                foreach ($this->campos as $key => $valor) {
                    $indeximagen = -1;

                    if ($key != $this->tabla . ".*" && $key != "*") {
                        if ($valor != "") {
                            $retorno[$i][$valor] = stripslashes($rs->Field($valor));
                            if ($rs->Field($valor) != "") {
                                $indeximagen = $this->existeEnArrayImagenes($valor);
                                $Vindeximagen = explode(",", $indeximagen);
                                for ($ff = 0; $ff < count($Vindeximagen); $ff++) {
                                    if ($Vindeximagen[$ff] != "") {
                                        $retorno[$i][$valor . "_" . $ff] = Multimedia::GetImagenStatic($this->imagenes[$Vindeximagen[$ff]][$valor][0]['width'], $this->imagenes[$Vindeximagen[$ff]][$valor][1]['height'], $rs->Field($valor));
                                    }
                                }
                            }
                        } else {

                            $retorno[$i][$key] = stripslashes($rs->Field($key));
                        }
                    } else {
                        unset($retorno[$i][$key]);
                    }
                }

                $i++;
                $rs->MoveNext();
            }

            /* $rs->MoveFirst();
              $xx = array();
              for ($k=0; $k<$rs->numfields; $k++){
              $xx[$rs->fieldname[$k]] =  $rs->field($rs->fieldname[$k]);
              }

              //print '<pre>'; print_r($retorno); print '</pre>';
              $i=0;
              while (!$rs->eof && $rs!=false) {
              //si hay *, busco todos los campos y valores
              foreach ($xx  as $key=>$valor){
              if (!array_key_exists($key,$retorno[$i])){
              $retorno[$i][$key] = $rs->field($key);
              }
              }
              $i++;
              $rs->MoveNext();
              } */
        } else {

            $paginado = new Paginado($registros_x_pagina, $cant_paginas, $consulta, $this->test);

            //11-02-2010 bug. solucionado gdf
            $retorno['registros'] = $paginado->Listar($pagina_actual, $this->imagenes);
            if (!$amigable) {
                $retorno['paginado'] = $paginado->GetPaginado();
                $retorno['paginado_multimedia'] = $paginado->GetMultimediaPaginado();
            } else {
                $retorno['paginado'] = $paginado->GetPaginadoAmigable();
                $retorno['paginado_multimedia'] = $paginado->GetMultimediaPaginadoAmigable();
            }

            if (!is_array($retorno['registros'])) {
                $retorno['registros'] = array();
                $retorno['paginado'] = "";
            }
        }

        return $retorno;
    }

    /**
     * consulta sql y retorna campo, valores, puede ser paginado... no soporta generador de thumbs
     * @param sql $consulta
     * @param boolean $paginar
     * @param int $registros_x_pagina
     * @param int $cant_paginas
     * @param int $pagina_actual
     * @param boolean $amigable
     */
    static public function ExecuteSql($consulta, $paginar = false, $registros_x_pagina = 0, $cant_paginas = 0, $pagina_actual = 1, $amigable = false) {

        global $conn;

        $retorno = array();
        if (!$paginar) {

            $campos = "";

            $rs = $conn->Execute($consulta);
            $i = 0;

            while (!$rs->eof && $rs != false) {

                for ($k = 0; $k < $rs->numfields; $k++) {
                    $retorno[$i][$rs->fieldname[$k]] = stripslashes($rs->Field($rs->fieldname[$k]));
                }


                $i++;
                $rs->MoveNext();
            }
        } else {
            $retorno['registros'] = array();

            $paginado = new Paginado($registros_x_pagina, $cant_paginas, $consulta);
            $retorno['registros'] = $paginado->Listar($pagina_actual);
            if (!$amigable) {

                $retorno['paginado'] = $paginado->GetPaginado();
            } else {

                $retorno['paginado'] = $paginado->GetPaginadoAmigable();
            }
        }

        return $retorno;
    }

    /**
     * imprime las consultas
     * @param unknown_type $valor
     */
    public function SetTest($valor) {
        $this->test = $valor;
    }

    /**
     * setea el valor del campo
     * @param string $key
     * @param string $value
     */
    public function SetValor($key, $value) {
        if ($this->restaurado) {
            $this->valores[$key] = $value;
        }

        //esto es pata el insert y el update
        $this->campos_tabla[$key] = $value;
    }

    /**
     * se agregan los campos que son imagenes y requieren ser generados
     * @param unknown_type $key
     * @param unknown_type $width
     * @param unknown_type $height
     */
    public function SetArrayImage($key, $width = 0, $height = 0) {
        $width = intval($width);
        $height = intval($height);

        $indexArray = count($this->imagenes);

        if ($indexArray < 0)
            $indexArray = 0;

        $this->imagenes[$indexArray][$key] = array();
        array_push($this->imagenes[$indexArray][$key], array("width" => $width));
        array_push($this->imagenes[$indexArray][$key], array("height" => $height));
    }

    protected function existeEnArrayImagenes($valor) {
        $retorno;
        for ($ii = 0; $ii < count($this->imagenes); $ii++) {

            foreach ($this->imagenes[$ii] as $key => $val) {
                if ($key == $valor) {
                    $retorno .= $ii . ",";
                }
            }
        }
        return substr($retorno, 0, -1);
    }

    /**
     * Modifica los datos del objeto
     * @param boolean $restaurar
     * @param string $filtro
     */
    public function Modificar($restaurar = true, $filtro = "") {
        global $conn;

        if ($this->restaurado) {

            $campos = "";
            $valores = "";

            foreach ($this->campos_tabla as $key => $valor) {

                if ($this->campo_id != $this->tabla . "." . $key) {
                    $campos .=$key . "='" . addslashes($valor) . "',";
                }
            }
            $campos = substr($campos, 0, -1);

            $sql = "UPDATE " . $this->tabla . " SET " . $campos . " WHERE " . $this->campo_id . "='" . $this->id . "' " . ($filtro != "" ? " AND " . $filtro : "") . "";

            if ($this->test == false) {
                if (count($this->campos_tabla) > 0) {

                    $conn->Execute($sql);
                    if ($restaurar) {
                        $this->Restaurar($this->id);
                    }
                    return 1;
                } else {
                    return 0;
                }
            } else {
                echo $sql;
                return "-1";
            }
        } else {

            try {
                throw new MyException("Debe restaurar los datos, acceda a la funcion SetId(x) y luego Restaurar()");
            } catch (MyException $e) {
                $e->getError();
            }
        }
    }

}

?>