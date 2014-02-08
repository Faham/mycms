<?php

class db
{
    
    function __construct()
    {
        global $g;
        
        mysql_connect($g['db_host'],$g['db_user'],$g['db_pass'],null);//,MYSQL_CLIENT_SSL);
        mysql_select_db($g['db_name']);
        mysql_query('SET NAMES "utf8"');
        mysql_query("SET time_zone = '{$g['timezone']}'");
    }
        
    function query($q, $params = array())
    {
        global $g;
        
        if(count($params) != substr_count($q, '###')){
            $ret['error']   = true;
            $ret['rows']    = array();
            $ret['msg']     = 'Count is wrong!';
            $ret['msg_no']  = 0;
            
            return $ret;
        }

		$q = str_replace('!!!', $g['db_table_prefix'] . '_', $q);

        $params = system::check_params($params);

        foreach($params as $par)
        {
            // if there is a ### sign!
            if(strpos($par[0], '###') !== false){
                $par[0] = str_replace('###', '##__^^_^_#!#!', $par[0]);
            }
            
            switch(strtolower($par[1])){
                case 'integer':
                case 'int':
                case 'number':
                case 'num':
                    $par[0] = intval($par[0]);
                    break;
                
                case 'float':
                case 'double':
                    $par[0] = floatval($par[0]);
                    $par[0] = '"' . $par[0] . '"';
                    break;
                
                case 'boolean':
                    $par[0] = (boolean) $par[0];
                    break;
                
                case 'password':
                    $par[0] = md5($par[0]);
                    $par[0] = '"' . $par[0] . '"';
                    break;
                
                case 'null':
                    $par[0] = 'NULL';
                    break;
                
                case 'like':
                    $old = array('ي','ك');
                    $new = array('ی', 'ک');
                    $par[0] = trim(str_replace($old, $new, $par[0]));
                    $par[0] = mysql_real_escape_string($par[0]);
                    $par[0] = '"%' . $par[0] . '%"';
                    
                    break;
                
                case 'column':
                    $temp = explode('.', $par[0]);
                    $temp[1] = trim($temp[1]);
                    $temp[1] = mysql_real_escape_string($temp[1]);
                    $par[0] = $temp[0] . '.`' . $temp[1] . '`';
                    
                    break;
                
                default:
                    $old = array('ي','ك');
                    $new = array('ی', 'ک');
                    $par[0] = trim(str_replace($old, $new, $par[0]));
                    $par[0] = mysql_real_escape_string($par[0]);
                    $par[0] = '"' . $par[0] . '"';
            }
            
            //$q = substr($q, 0, strpos($q, '###')) . '"' . $par[0] . '"' . substr($q, strpos($q, '###') + 3);
            $q = substr($q, 0, strpos($q, '###')) . $par[0] . substr($q, strpos($q, '###') + 3);
        }
        
        // first remove all safe ### characters
        $q = str_replace('##__^^_^_#!#!', '###', $q);
        
		if ($g['runmode'] ==  'debug')
			$g['error']->push($q);

        $res = mysql_query($q);
        @$g['total_q']++;
        
        $qtype = strtolower(substr(trim($q),0,6));
        
        if($res && ($qtype == 'select' || substr($qtype, 0, 4) == 'show')){
            $array = array();
            
            while($myfetch = mysql_fetch_assoc($res))
            {
                $array[] = $myfetch;
            }
            
            $ret['count'] = mysql_num_rows($res);
            $ret['rows']    = $array;
            $ret['error']   = false;
            
        }elseif($res){
            
            if($qtype == 'insert'){
                $ret['id'] = mysql_insert_id();
            }
            
            $ret['count'] = mysql_affected_rows();
            $ret['error']   = false;
            $ret['rows']    = array();
            
        }else{
            
            $ret['error']   = true;
            $ret['rows']    = array();
            $ret['msg']     = mysql_error();
            $ret['msg_no']  = mysql_errno();
            
        }
        
        if($ret['error'])
			$g['error']->push(mysql_error(), 'error');
        return $ret;
    }
    
    function get_type($table, $column)
    {
        $table = mysql_real_escape_string($table);
        $column = mysql_real_escape_string($column);
        
        $res = mysql_query("SHOW COLUMNS FROM $table LIKE '$column'");
        $res = mysql_fetch_assoc($res);
        $res = $res['Type'];
        
        $res = substr($res, 6, -2);
        $res = explode("','", $res);
        
        return $res;
    }
}
