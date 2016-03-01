<?

// Clases para MySQL

class TRecordset {

    var $fields_by_name; //array
    var $fields_by_index; //array
    var $cur = 0;    // cursor
    var $numfields;
    var $numrows;
    var $fieldname;   // array
    var $eof;

    function Close() {
        if ($this->cur)
            @mysql_free_result($this->cur);
    }

    function MoveNext() { //fetch
        $data = @mysql_fetch_row($this->cur);
        if (is_array($data)) {
            for ($i = 0; $i < mysql_num_fields($this->cur); $i++) {
                $this->fields_by_name[mysql_field_name($this->cur, $i)] = $data[$i];
                $this->fields_by_index[$i] = $data[$i];
            }
            $this->numrows = mysql_num_rows($this->cur);
            $this->eof = false;
        } else {
            if ($this->cur)
                $this->numrows = @mysql_num_rows($this->cur);
            $this->eof = true;
        }
    }

    function Field($id) {

        if (is_integer($id)) {
            return $this->fields_by_index[$id];
        } else {
            return $this->fields_by_name[$id];
        }
    }

    function recordset() {
        return $this->fields_by_name;
    }

    /**
     *
     * @param type $key nombre del campo de la tabla para ser la clave
     * @return type un array con key->array con campos tipo dictionary
     */
    function asDictionaryWithKey($key) {
        $cursorActual = $this->cur;
        //$this->cur = 0; //esto hace que procese desde la primera fila

        $arrData = array();
        while (!$this->eof) {
            $nombre = $this->Field($key);
            $arrData[$nombre] = array();
            //$cls = $i %2 ? ' class="bl"' : '';
            //array_push($arrData, 'cls'=>$cls);
            foreach ($this->fields_by_name as $field => $value) {
                $arrData[$nombre][$field] = $this->Field($field);
            }
            $this->MoveNext();
        }

        //volvemos a dejar el cursor en su lugar
        $this->cur = $cursorActual;
        return $arrData;
    }

    /**
     * Devuelve un array de filas / columnas con los datos del RS
     * @return type 
     */
    function asArray() {
        $cursorActual = $this->cur;
        //$this->cur = 0; //esto hace que procese desde la primera fila

        $arrData = array();
        while (!$this->eof) {
            $arrData = array();
            foreach ($this->fields_by_name as $field => $value) {
                $arrData[$field] = $this->Field($field);
            }
            $this->MoveNext();
        }

        //volvemos a dejar el cursor en su lugar
        $this->cur = $cursorActual;
        return $arrData;
    }

}

class TConnection {

    var $conn = 0;
    var $conn2 = 0;
    var $rs;
    var $ultimoId = 0;
    var $rds = array();

    function UltimoId() {

        return $this->ultimoId;
    }

    function Connect($host, $user, $password, $db = "") {
        // Retorna un objeto TConnection		
        $this->conn = @mysql_connect($host, $user, $password, true); // con @ no muestra mensajes de error
        if ($this->conn && $db)
            mysql_select_db($db, $this->conn);
        return $this;
    }

    function Disconnect() {
        if ($this->conn)
            mysql_close($this->conn);
    }

    function Execute($sqlstat, $log = true, $set = 0) {
        global $desarrollo;
        // Retorna un objeto TRecordset
        $this->rs = new TRecordset;
        if ($this->conn)
            $this->rs->cur = mysql_query($sqlstat, $this->conn);
        if ($this->rs->cur) {
            $this->rs->numfields = @mysql_num_fields($this->rs->cur);
            for ($i = 0; $i < $this->rs->numfields; $i++) {
                $this->rs->fieldname[$i] = mysql_field_name($this->rs->cur, $i);
            }
            $this->rs->MoveNext();
        }

        if ($this->rs->cur) {

            if (mysql_insert_id($this->conn) > 0) {
                $this->ultimoId = mysql_insert_id($this->conn);
            }
            if ($set == 1)
                $this->setRecordset();
            return $this->rs;
        } else {
            if ($desarrollo) {
                echo "<font color=red>" . mysql_errno() . ": " . mysql_error() . "<br>\n$sqlstat</font><BR>";
                die();
            }
            return false;
        }
    }

    private function setRecordset() {
        //vacio el array sino, por cada consulta se acumulan elementos
        $this->rds = array();

        $j = 0;
        while (!$this->rs->eof) {
            for ($x = 0; $x < count($this->rs->fieldname); $x++) {
                $this->rds[$j][$this->rs->fieldname[$x]] = funciones::base()->desanitize($this->rs->field($this->rs->fieldname[$x]));
            }
            $this->rs->movenext();
            $j++;
        }
    }

    public function getRecordset($sqlstat, $log = true) {
        $this->execute($sqlstat, $log, $set = 1);
        return $this->rds;
    }

}

?>