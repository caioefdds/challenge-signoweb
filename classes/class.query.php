<?php

class Query {
        private $_conn;
        private $_bind;
        private $_where;
        private $_my_conn;

        /** Start database connection */
        private function DB_Connect()
        {
            $this->_my_conn = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['db']);
    
            try {
                $this->_conn = new PDO("mysql:host={$_SESSION['host']};dbname={$_SESSION['db']}", $_SESSION['user'], $_SESSION['pass']);
                $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo ("ERRO CONEXAO PDO");
            }
        }

        /** Close database connection */
        private function DB_Close()
        {
            $this->_conn = null;
            mysqli_close($this->_my_conn);

            return true;
        }

        /**
         * Function to mount Bind of PDO
         * @param string $value
         * @param string $_name
         * @param string $_campo
         * @param string $_operator
         */
        private function PDOMontaBind($value, $_name, $_campo = '', $_operator = '=')
        {
            if ($_campo == '') {
                $_campo = $_name;
            }

            if (($value <> '')and($value <> '%')) {
                if ($_operator == 'like') {
                    $this->_where[] = "{$_campo} {$_operator} :{$_name}";
                    $this->_bind[$_name] = "%{$value}%";
                } else if ($_operator == 'between') {
                    $this->_where[] = "{$_campo} {$_operator} :{$_name}_v1 AND :{$_name}_v2";
                    $this->_bind["{$_name}_v1"] = $value[0];
                    $this->_bind["{$_name}_v2"] = $value[1];
                } else if ($_operator == 'in') {
                    $this->_where[] = "{$_campo} {$_operator}(:{$_name})";
                    $this->_bind[$_name] = $value;
                } else {
                    $this->_where[] = "{$_campo} {$_operator} :{$_name}";
                    $this->_bind[$_name] = $value;
                }
            }
        }

        /**
         * Function to build Where
         */
        private function PDOMontaWhere()
        {
            if (count($this->_where) > 0) {
                $this->_where = "WHERE " . implode(' AND ', $this->_where);
            }
        }

        /**
         * Clear All PDO
         */
        private function PDOClear()
        {
            unset($this->_bind);
            unset($this->_where);
        }

        /**
         * Execute SQL by PDO
         * @param string $_sql
         * @param string $_bind
         * @param string $_action
         * @return mixed
         */
        public function Query_SQL($_sql, $_bind = '', $_action = 'QUERY')
        {
            $this->DB_Connect();

            if (($_bind == '') and (isset($this->_bind))){
                $_bind = $this->_bind;
            }

            $_query = $this->_conn->prepare($_sql);

            foreach ($_bind as $key => $value) {
                $_query->bindValue(":{$key}", $value);
            }

            $_query->execute();

            if ($_action == 'QUERY') {
                $rows = $_query->fetchAll(PDO::FETCH_ASSOC);
                $a = 0;

                foreach ($rows as $key => $value) {
                    $_return[$key] = $value;
                    $a++;
                }
                $_return['contador'] = $a;

                return $_return;
            } else if ($_action == 'INSERT') {
                return $this->_conn->lastInsertId();
            } else if ($_action == 'UPDATE') {
                return $_query->rowCount();
            } else if ($_action == 'DELETE') {
                return $_query->rowCount();
            }
        }

        /**
         * Generic Query Method
         * @param string $_order_by
         * @param string $_table
         * @param string $_where
         * @return mixed
         */
        public function QueryPDO($_table, $_where = '', $_order_by = '')
        {
            $this->PDOClear();
            if ($_where <> '') {
                foreach ($_where as $key => $value) {
                    $this->PDOMontaBind($value, "b_$key", $key);
                }
                $this->PDOMontaWhere();
            } else {
                $this->_where = '';
            }

            $_sql = "
              SELECT
                *
              FROM
                {$_table}
              $this->_where
              {$_order_by}";

            return $this->Query_SQL($_sql,'', 'QUERY');
        }

        /** Generic Insert with PDO
         * @params mixed $_dados
         * @params mixed $_table
         * @params mixed $_nokey
         * @return mixed
         */
        public function InsertPDO($_dados, $_table, $_nokey = false)
        {
            foreach ($_dados as $key => $value) {
                $set[] = "{$key} = :{$key}";
                $_bind[$key] = $value;
            }
            $set_final = implode(',', $set);

            $_sql = "
                INSERT INTO
                    {$_table}
                SET
                    {$set_final}";

            return $this->Query_SQL($_sql, $_bind, 'INSERT');
        }

    /**
     * Generic Update method
     * @param string $_dados
     * @param string $_table
     * @param string $_where
     * @return mixed
     */
    public function UpdatePDO($_dados, $_table, $_where)
    {
        foreach ($_dados as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $_bind[$key] = $value;
        }
        $set_final = implode(',', $set);

        foreach ($_where as $key => $value) {
            $where[] = "{$key} = :where_{$key}";
            $_bind['where_'.$key] = $value;
        }
        $where_final = implode(' and ', $where);

        $_sql = "
          UPDATE
            {$_table}
          SET
            {$set_final}
          WHERE
            {$where_final}";

        return $this->Query_SQL($_sql, $_bind, 'UPDATE');
    }


        /** Class to register new users
         * @params mixed $_dados
         * @return mixed
         */
        public function RegisterUser($_dados)
        {
            $this->PDOClear();
            $this->PDOMontaBind($_dados['email'], 'email', 'email');
            $this->PDOMontaBind($_dados['password'], 'password', 'password');

            foreach ($this->_bind as $key => $value) {
                $set[] = "{$key} = :{$key}";
            }
            $set_final = implode(', ', $set);

            $_sql = "
                INSERT INTO
                    tab_user
                    SET
                     {$set_final}
            ;";

            try {
                $_res = $this->Query_SQL($_sql, '', 'INSERT');
                session_start();
                $_SESSION['email'] = $_dados['email'];
                $_SESSION['id_user'] = $_res;

                return $_res;
            } catch (PDOException $e) {
                return false;
            }
        }

        /**
         * Method to check the date status
         * @param string $_date_start
         * @param string $_date_end
         * @return mixed
         */
        public function DateTest($_date_start, $_date_end)
        {
            $now = date("Y-m-d");
            if ($now >= $_date_start && $now <= $_date_end) {
                $retorno['msg'] = "VISUALIZAR";
                $retorno['color'] = 'color-success';
                $retorno['status'] = 1;
                return $retorno;
            } else if($now > $_date_end) {
                $retorno['msg'] = "ENCERRADA";
                $retorno['color'] = 'color-danger';
                $retorno['status'] = 0;
                return $retorno;
            } else if($now < $_date_start) {
                $retorno['msg'] = "EM BREVE";
                $retorno['color'] = 'color-warning';
                $retorno['status'] = 2;
                return $retorno;
            }
        }

        /**
         * Method to query the polls
         * @param string $_id
         * @return mixed
         */
        public function ShowPoll($_id)
        {
            $this->PDOClear();
            $this->PDOMontaBind($_id, 'b_id', 'e.id');
            $this->PDOMontaWhere();

            $_sql = "
                SELECT
                    e.id, e.title, e.date_start, e.date_end
                FROM
                    tab_enquete e 
                    $this->_where
            ;";
            $_res = $this->Query_SQL($_sql);
            $_res['options'] = $this->ShowPollOptions($_res[0]['id']);

            return $_res;
        }
    /**
     * Method to query the polls options
     * @param string $_id
     * @return mixed
     */
    public function ShowPollOptions($_id)
    {
        $this->PDOClear();
        $this->PDOMontaBind($_id, 'b_id_enquete', 'id_enquete');
        $this->PDOMontaWhere();

        $_sql = "
                SELECT
                    e.id, e.descricao, e.id_enquete, e.votes
                FROM
                    tab_enquete_opcao e 
                    $this->_where
            ;";
        $_res = $this->Query_SQL($_sql);

        return $_res;
    }

    /**
     * Method to delete Polls Options
     * @param string $_dados
     * @return mixed
     */
    public function DeleteOptions($_dados)
    {
        $this->PDOClear();
        $this->PDOMontaBind($_dados['id_enquete'], 'b_id_enquete', 'id_enquete');
        $this->PDOMontaWhere();

        $_sql = "
                DELETE FROM
                    tab_enquete_opcao e 
                $this->_where
        ;";

        $_res = $this->Query_SQL($_sql, '', 'DELETE');

        return $_res;
    }

    /**
     * Method to delete the poll
     * @param string $id
     * @return mixed
     */
    public function DeletePoll($id)
    {
        $this->PDOClear();
        $this->PDOMontaBind($id['id'], 'b_id', 'id');
        $this->PDOMontaWhere();

        $_sql = "
                DELETE FROM
                    tab_enquete
                $this->_where
        ;";

        $_res = $this->Query_SQL($_sql, '', 'DELETE');

        return $_res;
    }
}
