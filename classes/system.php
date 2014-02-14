<?php

//-----------------------------------------------------------------------------

namespace mycms;

//-----------------------------------------------------------------------------

class system {

//-----------------------------------------------------------------------------

    public static function getmodule($url) {
        global $g;

        foreach ($g['urls'] as $p => $m) {
            $p = str_replace('/', '\/', $p);
            $p = "/$p/";
            $res = preg_match($p, $url);

            if (!empty($res)) {
                return $m;
            }
        }

        return 'pages';
    }

//-----------------------------------------------------------------------------

    public static function genlink($url)
    {
        global $g;

        if($g['rewrite']){
            return $g['weburl'] . $url;
        }

        $admin = false;

        // if the url is homepage (there is no url!)
        if(trim($url) === ''){
            return "index.php";
        }

        // if there is one word in url
        $pos = strpos($url, '/');
        if($pos === false){
            return "index.php?m=$url";
        }

        $do     = substr($url, 0, $pos);
        $params = substr($url, $pos + 1);
        $params = self::interpret_link($do, $params);

        $url = "index.php?m=$do";

        foreach($params as $var => $val){
            $url .= "&amp;{$var}={$val}";
        }

        return $g['weburl'] . $url;
    }

//-----------------------------------------------------------------------------

    public static function interpret_params()
    {
        global $g;
        $query = explode('?', $_SERVER['REQUEST_URI']);
        if(isset($query[1])){
            $query = $query[1];
        } else {
            $query = '';
        }

        if($query){
            $query = explode('&', $query);
            foreach($query as $var){
                list($var, $val) = explode('=', $var);

                $var = trim($var);
                $val = trim($val);
                $_GET[$var] = $val;
            }
        }

        if($g['rewrite']){
            $params = self::interpret_link();
            if($params){
                foreach($params as $var => $val){
                    $var = trim($var);
                    if(!is_array($val)){
                        $val = trim($val);
                    }
                    $_GET[$var] = $val;
                }
            }
        }
    }

//-----------------------------------------------------------------------------

    private static function interpret_link($url = false)
    {
        global $g;

        if(!$url && isset($_GET['params'])){
            $url = $_GET['params'];
        } else {
            $url = '';
        }

        // if last character of string is "/" remove it
        if(strrpos($url, '/') == strlen($url) - 1){
            $url = substr($url, 0, -1);
        }

        $params = explode('/', $url);

        // if there is language
        //if(in_array($params[0], lang::get_only_codes())){
        //    $_GET['lang'] = array_shift($params);
        //}

        //$first = array_shift($params);
        //if(!$first){
        //    $first = 'pages';
        //    $_GET['un'] = array($g['homepage']);
        //}

        $do = $_GET['module'] = self::getmodule($url);

        if(isset($_GET['module'])){
            $file = "modules/$do/$do.ini";
        }

        if(is_file($file)){
            $file = file($file);
        }else{
            $file = false;
        }

        if($file === false || count($file) === 0){
            return false;
        }

        foreach($file as $line){

            $line = trim($line);

            // init return variable
            $return = array();

            $line = explode('::', $line);
            // if this line is not rewrite structure
            if($line[0] != 'rewrite_structure'){
                continue;
            }

            $whole_line = $line[1];
            $line = explode('/', $line[1]);
            // if this is not suitable for this line, continue
            if(count($line) != count($params) && strpos($whole_line, '*') === false){
                continue;
            }
            $c = count($line);
            $pc = count($params);
            for($i = 0; $i < $c; $i++){

                switch(substr($line[$i], 0, 1)){
                    // if this is variable
                    case '%':
                        $var = substr($line[$i], 1);
                        $val = $params[$i];
                        $return[$var] = $val;
                        break;

                    // if this is infinite
                    case '*':
                        $var = substr($line[$i], 1);
                        // move whole remaining array to the return variable
                        for ($j = $i; $j < $pc; $j++){
                            $dummy[] = $params[$j];
                        }

                        $return[$var] = $dummy;
                        unset($params);
                        break;

                    // if this is static
                    // check this is same as what we have in params
                    // if it is not, go to next LINE
                    default:
                        if($line[$i] == $params[$i]){
                            $var = $line[$i];
                            $return[$var] = $var;
                            break;
                        }else{
                            unset($return);
                            continue;
                        }
                        break;
                }
            }

            // if there is something to return
            if(count($return)){
                return $return;
            }
        }
        // if there is no foreach and there is no value to return, return false
        return false;
    }

//-----------------------------------------------------------------------------

    public static function check_params($p)
    {
        for ($i=0;$i < count($p);$i++){
            if(!isset($p[$i][0]) || $p[$i][0] == '')
                $p[$i][1] = 'null';
        }

        return $p;
    }

//-----------------------------------------------------------------------------

    public static function redirect($url = '', $exit = true)
    {

        if($url === '') {
            $url = $_SERVER['HTTP_REFERER'];
        }

        header("Location: $url");
        //header("Connection: close");

        if($exit) {
            exit();
        }
    }

//-----------------------------------------------------------------------------

    public static function get_states()
    {
        global $g;

        $s = $g['db']->query('SELECT * FROM states');

        return $s['rows'];
    }


//-----------------------------------------------------------------------------

    public static function get_cities($state = false)
    {
        global $g;

        if($state){//get all cities for given state
            $c = $g['db']->query('SELECT * FROM cities WHERE state_id =' . $state);
        } else {//gett all cities
            $c = $g['db']->query('SELECT * FROM cities');
        }

        return $c['rows'];
    }

//-----------------------------------------------------------------------------

    public static function trans($str, $module = false)
    {
        if($module == false) {
            return constant('L_' . strtoupper($str));
        } else {
            return constant('L_' . strtoupper($_GET['module']) . '_' . str_replace(' ', '_', strtoupper($str)));
        }
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
