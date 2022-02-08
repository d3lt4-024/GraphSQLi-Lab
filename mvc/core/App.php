<?php
require_once(GRAPHSQLI_LAB_ROOT . "config/config.php");

class App
{
    protected $controller = "home"; //controller: list
    protected $action = "index"; //teacher: teacher
    protected $param = []; //param: 1

    function __construct()
    {
        $arr = $this->UrlProcess();
        //process controller
        if (file_exists(GRAPHSQLI_LAB_ROOT . "mvc/controllers/" . $arr[0] . ".php") === true) {
            $this->controller = $arr[0];
            unset($arr[0]);
        }
        if ($this->controller === "api") {
            //Pass URL if URL is api call
            require_once GRAPHSQLI_LAB_ROOT . "mvc/controllers/" . $this->controller . ".php";
            $this->controller = new $this->controller;
            //process action
            if (isset($arr[1])) {
                if (method_exists($this->controller, $arr[1])) {
                    $this->action = $arr[1];
                }
                unset($arr[1]);
            }
            //process param
            $this->param = $arr ? array_values($arr) : [];
            call_user_func_array([$this->controller, $this->action], $this->param);
        } else {
            //If URL is not API call, check session whether user is logged in or not
            if (isset($_SESSION["username"])) {
                require_once GRAPHSQLI_LAB_ROOT . "mvc/controllers/" . $this->controller . ".php";
                $this->controller = new $this->controller;
                //process action
                if (isset($arr[1])) {
                    if (method_exists($this->controller, $arr[1])) {
                        $this->action = $arr[1];
                    }
                    unset($arr[1]);
                }
                //process param
                $this->param = $arr ? array_values($arr) : [];
                call_user_func_array([$this->controller, $this->action], $this->param);
            } else {
                //If the user has never created a database
                if ($this->checkFirstTimeUse() === true) {
                    //redirect to setup page
                    $this->controller = "setup";
                } else {
                    //redirect to login page
                    $this->controller = "login";
                }
                require_once GRAPHSQLI_LAB_ROOT . "mvc/controllers/" . $this->controller . ".php";
                $this->controller = new $this->controller;
                call_user_func_array([$this->controller, $this->action], $this->param);
            }
        }
    }

    private function UrlProcess()
    {
        $url = "/home";
        if (isset($_GET["url"])) {
            $url = $_GET["url"];
        }
        return explode("/", filter_var(trim($url, "/")));
    }

    //Function check if user has created database or not
    private function checkFirstTimeUse()
    {
        global $_Config;
        try {
            $sec_pdo = new PDO('mysql:host=' . $_Config['db_server'] . ';dbname=' . $_Config['db_database'] . ';port=' . $_Config['db_port'] . ';charset=utf8', $_Config['db_user'], $_Config['db_password']);
            $sec_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sec_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0); //Not allow executed multi query
        } catch (PDOException $exception) {
            $error_msg = $exception->getMessage();
            require_once(GRAPHSQLI_LAB_ROOT . "mvc/views/page-error-500.php");
            die();
        }
        $query = ("SELECT table_schema, table_name, create_time
				FROM information_schema.tables
				WHERE table_schema='{$_Config['db_database']}' AND table_name='users'
				LIMIT 1");
        $statement = $sec_pdo->prepare($query);
        $statement->execute();
        $count = $statement->rowCount();
        if ($count != 1) {
            return true;
        } else return false;
    }
}
