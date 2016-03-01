<?

/**
 * funcciones de fechas
 * @author Gustavo Fiasche
 * 2011
 */
class FuncionesBase {

    /**
     * retorna con unidad de medida
     * @param int $bytes
     */
    public function getFormat_bytes($bytes) {
        if ($bytes < 1024)
            return $bytes . ' B';
        elseif ($bytes < 1048576)
            return round($bytes / 1024, 2) . ' KB';
        elseif ($bytes < 1073741824)
            return round($bytes / 1048576, 2) . ' MB';
        elseif ($bytes < 1099511627776)
            return round($bytes / 1073741824, 2) . ' GB';
        else
            return round($bytes / 1099511627776, 2) . ' TB';
    }

    /**
     * retorna la url real del video youtube
     * @param string $url
     */
    public function getFormatoUrlYoutube($url) {
        $vUrl = split("/", $url);

        return "http://" . $vUrl [2] . "/watch?v=" . $vUrl [4];
    }

    /**
     * url, http://
     * @param string $url
     * @param string $limpiarhttp
     */
    public function getFormatoLink($url, $limpiarhttp = "") {

        if ($limpiarhttp == "") {
            return "http://" . str_replace("http://", "", $url);
            ;
        } else {
            return str_replace("http://", "", $url);
        }
    }

    /**
     * funcion para limpiar variables que se usan para GET y POST, manual
     * @param string $str
     * @param string $tipo
     */
    public function getLimpiar_request($str, $tipo = false) {

        $str = htmlentities(strip_tags(html_entity_decode(urldecode($str))), ENT_QUOTES);
        if ($tipo) {
            switch ($tipo) {
                case "int" :
                    $str = intval($str);
                    break;
                case "float" :
                    $str = floatval($str);
                    break;
            }
        }

        return $str;
    }

    public function sanitize($str) {
        $value = $str;
        if (!get_magic_quotes_gpc()) {
            $value = addslashes($str);
        }
        return $value;
    }

    public function desanitize($str) {
        $value = $str;
        if (!get_magic_quotes_gpc()) {
            $value = stripslashes($str);
        }
        return $value;
    }

    /**
     * retorna url no amigable 
     * @param unknown_type $page
     * @param unknown_type $parameters
     */
    public function getHref($page = '', $parameters = '') {
        $link = "";
        if (!$page)
            die(PrintError('Error, no determino la pagina'));

        if ($parameters) {
            #Si es array separo los nombres y valores y los uno en la direccion web
            if (is_array($parameters)) {
                $link .= $page . '?';
                $nombre = array_keys($parameters);
                $valor = array_values($parameters);

                for ($i = 0; $i < count($nombre); $i++) {
                    if (is_array($valor [$i])) {
                        $subArray = $nombre [$i];
                        for ($j = 0; $j < count($valor [$i]); $j++) {
                            $subValue = $valor [$i] [$j];
                            $link .= '&' . $subArray . '%5B%5D=' . self::OutputString($subValue);
                        }
                    } else {
                        $link .= ($i ? '&' : '') . $nombre [$i] . '=' . self::OutputString($valor [$i]);
                    }
                }
            }
            else
                $link .= $page . '?' . self::OutputString($parameters);
            $separator = '&';
        } else {

            $link .= $page;
            $separator = '?';
        }

        # Si tiene al final de la direccion un caracter & o ?, lo saco
        while ((substr($link, - 1) == '&') || (substr($link, - 1) == '?'))
            $link = substr($link, 0, - 1);

        return $link;
    }

    /**
     * dep getHref
     * @param $data
     * @param $parse
     */
    private function ParseInputData($data, $parse) {
        return strtr(trim($data), $parse);
    }

    /**
     * dep getHref
     * @param unknown_type $string
     * @param unknown_type $translate
     */
    private function OutputString($string, $translate = false) {

        if ($translate == false)
            return self::ParseInputData($string, array('"' => '&quot;'));
        else
            return self::ParseInputData($string, $translate);
    }

    /**
     * retorna un string cortado por la cantidad de caracteres (corta si supera la cantidad)
     * @param string $string
     * @param int $maxchars
     */
    public function getCstring($string, $length) {
        $tString = split(' ', $string);
        $rString = '';
        $sString = 0;

        for ($index = 0; $index < count($tString); $index++) {
            if (strlen($rString . chr(32) . $tString [$index]) < $length) {
                $rString .= chr(32) . $tString [$index];
            } else {
                $sString = 1;

                break;
            }
        }

        if ($sString == 0) {
            return $rString;
        } else {
            return $rString . '...';
        }
    }

    /**
     * decode
     * @param string $texto
     */
    public function ansiToplain($texto) {
        $texto = str_replace('<BR>', '<BR />', $texto);
        $texto = str_replace('<STRONG>', '<b>', $texto);
        $texto = str_replace('</STRONG>', '</b>', $texto);
        $texto = str_replace('а', 'a', $texto);
        $texto = str_replace('б', 'a', $texto);
        $texto = str_replace('д', 'a', $texto);
        $texto = str_replace('и', 'e', $texto);
        $texto = str_replace('й', 'e', $texto);
        $texto = str_replace('л', 'e', $texto);
        $texto = str_replace('м', 'i', $texto);
        $texto = str_replace('н', 'i', $texto);
        $texto = str_replace('п', 'i', $texto);
        $texto = str_replace('т', 'o', $texto);
        $texto = str_replace('у', 'o', $texto);
        $texto = str_replace('ц', 'o', $texto);
        $texto = str_replace('щ', 'u', $texto);
        $texto = str_replace('ъ', 'u', $texto);
        $texto = str_replace('ь', 'u', $texto);
        $texto = str_replace('�?', 'n', $texto);
        $texto = str_replace("'", '"', $texto);
        return $texto;
    }

    /**
     * limpia codigo html
     * @param string $tagSource
     */
    public function removeEvilAttributes($tagSource) {
        $stripAttrib = '/ (style|class)="(.*?)"/i';
        //$stripAttrib = '/ (style)="(.*?)"/i'; 
        $tagSource = stripslashes($tagSource);
        $tagSource = preg_replace($stripAttrib, '', $tagSource);
        return $tagSource;
    }

    /**
     * limpia codigo html
     * @param string $source
     */
    public function removeEvilTags($source) {
        $allowedTags = '<a><br><b><h1><h2><h3><h4><i>' . '<li><ol><p><strong><table>' . '<tr><td><th><u><ul><img><div>';
        $source = stripslashes(strip_tags($source, $allowedTags));
        return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
    }

    /**
     * limpia codigo html
     * @param string $source
     */
    public function removeEvilTagsSpecial($source) {
        $allowedTags = '<a><br><b><h1><h2><h3><h4><i>' . '<strong>';
        $source = stripslashes(strip_tags($source, $allowedTags));
        return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
    }

    /**
     * retorna fecha en (d-m-a)
     * @param date $fecha
     */
    public function getMysql_a_normal($fecha) {
        preg_match("@([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})@", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
        return $lafecha;
    }

    /**
     * retorna fecha en (a-m-d)
     * @param date $fecha
     */
    function getNormal_a_mysql($fecha) {
        preg_match("@([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})@", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $lafecha;
    }

    /**
     * retorna string aleatoreo
     * @param $length
     */
    function randomkeys($length) {
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        $key = "";
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern {rand(0, 35)};
        }
        return $key;
    }

}