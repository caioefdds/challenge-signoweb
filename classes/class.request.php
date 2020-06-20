<?php

include('class.query.php');
include('class.session.php');

$__request = new Request();

Class Request {

    private $__query;
    private $__session;

    public function __construct()
    {
        $__config = parse_ini_file("../config.ini", true);

        $this->__query = new Query();
        $this->__session = new Session($__config);

        $this->IdentifyRequest($_POST);
    }

    public function IdentifyRequest($_dados) {

        if($_dados['func'] == 'addvoto') {

            $where['id'] = $_dados['id'];
            $dados['votes'] = intval($_dados['votes']) + 1;

            $this->__query->UpdatePDO($dados, 'tab_enquete_opcao', $where);

            $return['id'] = $where['id'];
            $return['votes'] = $dados['votes'];

            echo json_encode((object)$return);
        } else if($_dados['func'] == 'removePoll') {

            $where['id'] = $_dados['id'];
            $_where['id_enquete'] = $_dados['id'];

            $return['options'] = $this->__query->DeleteOptions($_where);
            $return['poll'] = $this->__query->DeletePoll($where);

            echo json_encode((object)$return);
        }
    }



}
