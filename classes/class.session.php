<?php

Class Session {

    public function __construct($__config) {

        session_start();

        $_SESSION['ini_config'] = $__config;
        $_SESSION['db'] = $__config['DATABASE']['db'];
        $_SESSION['host'] = $__config['DATABASE']['host'];
        $_SESSION['user'] = $__config['DATABASE']['user'];
        $_SESSION['pass'] = $__config['DATABASE']['pass'];
    }

    public function Logausuario($_dados) {

        $_SESSION['email'] = $_dados['email'];
        $_SESSION['id_user'] = $_dados['id_user'];

        return $_SESSION;
    }
}

?>