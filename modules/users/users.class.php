<?php

//-----------------------------------------------------------------------------

namespace mycms;

//-----------------------------------------------------------------------------

class users {

//-----------------------------------------------------------------------------

    public $role = 'guest';
    public $id   = 0;
    private $hash = '$#($%^$^$%^#*^@@):"';  // for remember password
    private $expiry = 1209600;  // for remember password

//-----------------------------------------------------------------------------

    public function __construct() {
        global $g;

        // if remember me checkbox checked!
        if(isset($_COOKIE['user']) &&  $id = $this->remember_decode($_COOKIE['user'])){
            $_SESSION['id'] = $id;
        }

        if(isset($_SESSION['id'])){
            $p = array(
                array($_SESSION['id'], 'integer')
            );

            $result = $g['db']->query('SELECT role FROM users WHERE id = ### LIMIT 1', $p);
            $this->role = $result['rows'][0]['role'];
            $this->id   = $_SESSION['id'];
        }

        $ret['error'] = false;
        return $ret;
    }

//-----------------------------------------------------------------------------

    public function login($u, $p, $remember = false) {
        global $g;

        $p = array(
            array($u, 'string'),
            array($p, 'password')
        );

        $r = $g['db']->query('SELECT * FROM users WHERE
                        username = ### AND password = ### AND status = "active" LIMIT 1', $p);

        if(!$r['error'] && $r['count']){
            session_regenerate_id();
            $row = $r['rows'][0];
            $_SESSION['id'] = $row['id'];
            $this->role = $row['role'];

            if($remember){
                setcookie('user',
                          $this->remember_encode($row['id'], $row['username'], $row['password']),
                          time() + $this->expiry, '/');
            }
        }

        return $r;
    }

//-----------------------------------------------------------------------------

    public function add($username, $password, $fullname, $email, $role) {
        global $g;

        $p = array(
            array($username, 'string'),
            array($password, 'password'),
            array($fullname, 'string'),
            array($email,    'string'),
            array($role,     'string')
        );

        $r = $g['db']->query('INSERT INTO users
                             (username, password, fullname, email, role, status)
                             VALUES
                             (###, ###, ###, ###, ###, "active")', $p);

        if(!$r['error']){
            $g['error']->push(L_USERS_ADD_OK, 'info');
        } else {
            $g['error']->push(L_USERS_ADD_ERROR, 'error');
        }

        return $r;
    }

//-----------------------------------------------------------------------------

    public function admin_edit($id, $username, $password, $fullname, $email, $role, $status) {
        global $g;

        if(trim($password) == ''){
            $p = array(
                array($username, 'string'),
                array($fullname, 'string'),
                array($email,    'string'),
                array($role,     'string'),
                array($status,   'string'),
                array($id,       'integer')
            );

            $r = $g['db']->query('UPDATE users SET
                                 username = ###, fullname = ###, email = ###,
                                 role = ###, status = ### WHERE id = ###', $p);
        } else {
            $p = array(
                array($username, 'string'),
                array($password, 'password'),
                array($fullname, 'string'),
                array($email,    'string'),
                array($role,     'string'),
                array($status,   'string'),
                array($id,       'integer')
            );

            $r = $g['db']->query('UPDATE users SET
                                 username = ###, password = ###, fullname = ###, email = ###,
                                 role = ###, status = ### WHERE id = ###', $p);
        }

        return $r;
    }

//-----------------------------------------------------------------------------

    public function edit($id, $password, $fullname, $email) {
        global $g;

        if(trim($password) == ''){
            $p = array(
                array($fullname,   'string'),
                array($email,      'string'),
                array($id,         'integer')
            );

            $r = $g['db']->query('UPDATE users SET
                                 fullname = ###, email = ### WHERE id = ###', $p);
        } else {
            $p = array(
                array($password,   'password'),
                array($fullname,   'string'),
                array($email,      'string'),
                array($id,         'integer')
            );

            $r = $g['db']->query('UPDATE users SET
                                 password = ###, fullname = ###, email = ### WHERE id = ###', $p);
        }

        return $r;
    }

//-----------------------------------------------------------------------------

    public function validate($id, $code) {
        global $g;

        $p = array(
            array($id,   'integer'),
            array($code, 'string')
        );

        $r = $g['db']->query('SELECT * FROM users WHERE
                        id = ### AND unique_key = ### AND status = "inactive" LIMIT 1', $p);

        if(!$r['error']){
            $p = array(
                array($id, 'integer')
            );

            $g['db']->query('UPDATE users SET status = "active", unique_key="" WHERE id = ###', $p);
            $g['error']->push(L_USERS_VALIDATION_OK, 'info');
        } else {
            $g['error']->push(L_USERS_VALIDATION_ERROR, 'error');
        }
        var_dump($r);
        return $r;
    }

//-----------------------------------------------------------------------------

    public function change_password($id, $code, $password) {
        global $g;

        $p = array(
            array($password,    'password'),
            array($id,          'integer'),
            array($validation,  'string')
        );

        $r = $g['db']->query('UPDATE users SET password = ###, validation=NULL
                             WHERE id = ### AND validation = ###', $p);

        if(!$r['error']){
            $g['error']->push(L_USERS_FORGOT_CHANGE_OK, 'info');
        } else {
            $g['error']->push(L_USERS_FORGOT_CHANGE_ERROR, 'error');
        }
    }

//-----------------------------------------------------------------------------

    public function set($var, $val, $id = false) {
        global $g;

        if(!$id){
            $id = $g['user']->id;
        }

        $p = array(
            array($val, 'string'),
            array($id, 'integer')
        );

        $r = $g['db']->query("UPDATE users SET `$var` = ### WHERE id = ###", $p);

        return $r;
    }

//-----------------------------------------------------------------------------

    public function logout() {
        if(isset($_SESSION['id']))
        {
            // delete cookie and session
            setcookie('user', '', 0, '/');
            session_destroy();
        }
    }

//-----------------------------------------------------------------------------

    private function remember_encode($id, $username, $password) {
        return base64_encode($id . ',' . sha1(md5($username . $password . $this->hash)));
    }

//-----------------------------------------------------------------------------

    private function remember_decode($str) {
        global $g;

        list($id) = explode(',', base64_decode($str));

        if(!$id) return false;

        $r = self::get_one_by_id($id);

        if($r['error']) return false;

        $hash = $this->remember_encode($id, $r['rows'][0]['username'], $r['rows'][0]['password']);

        if($hash === $str){
            return $id;
        }else{
            return false;
        }
    }

//-----------------------------------------------------------------------------

    public function get($var, $id = false) {
        global $g;

        if(!$id){
            $id = $g['user']->id;
        }

        $p = array(
            array($id, 'integer')
        );

        return $g['db']->query("SELECT `{$var}` FROM users WHERE
                        id = ### LIMIT 1", $p);
    }

//-----------------------------------------------------------------------------

    public function get_one_page($page = 1, $count = 10) {
        global $g;

        return $g['db']->query('SELECT * FROM users
                               LIMIT ' . intval(($page - 1) * $count) . ',' . intval($count));
    }

//-----------------------------------------------------------------------------

    public function get_max_pages($count = 10) {
        global $g;

        $r = $g['db']->query('SELECT COUNT(*) c FROM users');
        $r = $r['rows'][0]['c'];

        return ceil($r / $count);
    }

//-----------------------------------------------------------------------------

    public function get_one_filtered_page($username, $page = 1, $count = 10) {
        global $g;

        $p = array(
            array("%{$username}%", 'string')
        );

        return $g['db']->query('SELECT * FROM users WHERE username LIKE ###
                               LIMIT ' . intval(($page - 1) * $count) . ',' . intval($count), $p);
    }

//-----------------------------------------------------------------------------

    public function get_max_filtered_pages($username, $count = 10) {
        global $g;

        $p = array(
            array("%{$username}%", 'string')
        );

        $r = $g['db']->query('SELECT COUNT(*) c FROM users WHERE username LIKE ###', $p);

        $r = $r['rows'][0]['c'];

        return ceil($r / $count);
    }

//-----------------------------------------------------------------------------

    public function get_one_by_id($id = false) {
        global $g;

        if(!$id){
            $id = $g['user']->id;
        }

        $p = array(
            array($id, 'integer')
        );

        return $g['db']->query('SELECT * FROM users WHERE id = ### LIMIT 1', $p);
    }

//-----------------------------------------------------------------------------

    public function get_roles() {
        global $g;

        $r = $g['db']->query('SHOW COLUMNS FROM users LIKE "role"');
        preg_match_all("/'(.*?)'/", $r['rows'][0]['Type'], $enum_array);

        return $enum_array[1];
    }

//-----------------------------------------------------------------------------

    public function get_statuses() {
        global $g;

        $r = $g['db']->query('SHOW COLUMNS FROM users LIKE "status"');
        preg_match_all("/'(.*?)'/", $r['rows'][0]['Type'], $enum_array);

        return $enum_array[1];
    }

//-----------------------------------------------------------------------------

    public function change_profile($id, $password, $fullname, $city, $gender, $birth_date) {
        global $g;

        $p = array(
            array($password,   'password'),
            array($fullname,   'string'),
            array($city,       'string'),
            array($gender,     'string'),
            array($birth_date, 'string'),
            array($id,         'integer')
        );

        return $g['db']->query('UPDATE users SET password = ###, fullname = ###,
                               city = ###, gender = ###, birth_date = ###
                               WHERE id = ### AND validation = ###', $p);
    }

//-----------------------------------------------------------------------------

    public function get_yegans($user_id) {
        global $g;

        return $g['db']->query("SELECT yegan_id FROM user_yegan WHERE user_id = $user_id");
    }

//-----------------------------------------------------------------------------

    public function has_yegan($user_id, $yegan_id) {
        global $g;
        $r = $g['user']->role;

        if($r == "admin" || $r == "chief" || $r == "chief specialist")
            return true;

        $res = users::get_yegans($user_id);

        foreach($res['rows'] as $row)
        {
            $yid = $row['yegan_id'];
            if($yid == substr($yegan_id, 0, strlen($yid)))
                return $yid;
        }

        return false;
    }

//-----------------------------------------------------------------------------

    public function add_yegan($user_id, $yegan_id) {
        if(!isset($user_id) || !isset($yegan_id)) return;
        echo "user is : $user_id and yegan: $yegan_id";
        global $g;

        $res = $g['db']->query("SELECT * FROM user_yegan WHERE user_id = $user_id AND yegan_id = $yegan_id");
        if($res['count'] == 0)
            return $g['db']->query("INSERT INTO user_yegan(user_id, yegan_id) VALUE ($user_id, $yegan_id)");
    }

//-----------------------------------------------------------------------------

    public function remove_yegan($user_id, $yegan_id) {
        echo "test";
        if(!isset($user_id) || !isset($yegan_id)) return;

        global $g;

        $g['db']->query("DELETE FROM user_yegan WHERE user_id = $user_id AND yegan_id = $yegan_id");
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------

