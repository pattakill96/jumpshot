<?php

    define("UNCONNECTED", "UNCONNECTED");
    define("CONNECTED", "CONNECTED");
    define("ERROR", "ERROR");
    define("SUCCESS", "SUCCESS");


    Class DBMS {

        var
        $host,
        $user,
        $pass,
        $name,
        $status,
        $link,
        $handle;

        function DBMS($host, $user, $pass, $name) {

            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->name = $name;

            $this->status = UNCONNECTED;
        }

        function connect() {

            $this->link = mysqli_connect($this->host, $this->user, $this->pass, $this->name);

            if ($this->link) {
                $this->status = CONNECTED;
            } else {
                $this->status = ERROR;
            }

        }

        function isConnected() {
            return ($this->status == CONNECTED);
        }

        function isError() {
            return ($this->status == ERROR);
        }

        function query($query) {

            $this->handle = mysqli_query($this->link, $query);

            if (!$this->handle) {
                $this->status = ERROR;
            } else {
                $this->status = SUCCESS;
            }
        }

        function sanitize() {

            foreach($_POST as $key => $value) {
                $_POST[$key] = addslashes($value);
            }

        }

        function getResult() {

            $result = false;

            do {
                $data = mysqli_fetch_assoc($this->handle);

                if ($data) {
                    $result[] = $data;
                }

            } while ($data);

            return $result;

        }

        function getNumRows() {
            return mysqli_num_rows($this->handle);
        }

    }

    
    $db = new DBMS("localhost", "root", "", "jumpshot");
            
    $db->connect();

    if (!$db->isConnected()) {
        Header("Location: error.php?code=001");
        exit;
    }



?>
