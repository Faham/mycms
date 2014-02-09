<?php

class settings
{
    public static function get($var)
    {
        global $g;

        $p = array(
            array(strtolower($var), 'string')
        );

        $r = $g['db']->query('SELECT * FROM !!!settings WHERE var = ###', $p);

        return $r['rows'][0]['val'];
    }

    public static function get_all()
    {
        echo __FILE__ . ':' . __LINE__ . '(' . __FUNCTION__ . ')' . " not implemented";
        /*
        global $g;

        $r = $g['db']->query('SELECT * FROM !!!settings');

        foreach($r['rows'] as $s){
            $g[$s['var']] = $s['val'];
        }

        return $r;
        */
    }

    public static function set($var, $val)
    {
        global $g;

        $var = strtolower($var);

        // check if already exists or not
        $r = self::get($var);

        // if already exists UPDATE else INSERT
        if(!$r['error']){
            $q = 'UPDATE !!!settings SET val = ### WHERE var = ###';
        }else{
            $q = 'INSERT INTO !!!settings (var, val) VALUES (###, ###)';
        }

        $p = array(
            array($val, 'string'),
            array($var, 'string')
        );

        $g['db']->query($q, $p);
    }

    public static function set_all($vars)
    {
        global $g;

        foreach($vars as $var){
            $var['var'] = strtolower($var['var']);

            $p = array(
                array($var['val'], 'string'),
                array($var['var'], 'string')
            );

            $g['db']->query('UPDATE !!!settings SET val = ### WHERE var = ###', $p);
        }
    }
}
