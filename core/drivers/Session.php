<?php
namespace Core\Drivers;

use Models;

class Session {

    private static $instance;

    public function __construct() {
        $session = new sessionHandler();
        session_set_save_handler(array($session, 'open'),
                         array($session, 'close'),
                         array($session, 'read'),
                         array($session, 'write'),
                         array($session, 'destroy'),
                         array($session, 'gc'));
        session_start();
    }

    public static function sessionInstance() {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function sessionStarted() {
        if(session_id() == '') {
            return false;
        } else {
            return true;
        }
    }

    public static function sessionExists($session) {
        if(self::sessionStarted() == false) {
            $s = self::sessionInstance();
        }
        if(isset($_SESSION[$session])) {
            return true;
        } else {
            return false;
        }
    }

    public static function set($session, $value) {
        if(self::sessionStarted() != true) {
            $s = self::sessionInstance();
        }
        $_SESSION[$session] = $value;
        if(self::sessionExists($session) == false) {
            throw new Exception('Unable to Create Session');
        }
    }

    public static function get($session) {
        if(self::sessionStarted() != true) {
            $s = self::sessionInstance();
        }
        if(isset($_SESSION[$session])) {
            return $_SESSION[$session];
        }

        throw new Exception("Variable de session {$session} no existe.");
    }

    public static  function close() {
        $s = self::sessionInstance();
        session_destroy();
    }

}

class SessionHandler{

    protected $table = 'sessions';

    public function open() {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        $session = Models\Session::find_by_id($id);
        if( $session ) {
            return $session->data;
        } else {
            return false;
        }
    }

    public function write($id, $data) {
        $session = Models\Session::find_by_id($id);
        if( $session ){
            $session->data = $data;
            $session->save();
            return true;
        } else {
            $session = Models\Session::create(
                        array(
                            "id" => $id,
                            "data" => $data
                        )
                    );
        }
        return false;
    }

    public function destroy($id) {
        $session = Models\Session::find_by_id($id);
        if( $session )
            $session->delete();
        return true;
    }

    public function gc($max) {
        $query = sprintf("DELETE FROM %s WHERE `created_at` < '%s'", $this->table, time() - intval($max));
        return dbDriver::execQuery($query);
    }

}
