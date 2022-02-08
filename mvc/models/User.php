<?php
require_once GRAPHSQLI_LAB_ROOT . 'config/config.php';

class User
{
    private $sec_pdo;

    function __construct()
    {
        try {
            global $_Config;
            $this->sec_pdo = new PDO('mysql:host=' . $_Config['db_server'] . ';dbname=' . $_Config['db_database'] . ';port=' . $_Config['db_port'] . ';charset=utf8', $_Config['db_user'], $_Config['db_password']);
            $this->sec_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->sec_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0); //Not allow executed multi query
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }

    function LoginCheck($username, $password)
    {
        $query = "SELECT * FROM users WHERE Username = :username AND Password = :password";
        try {
            $statement = $this->sec_pdo->prepare($query);
            $statement->bindValue(':username', $username, PDO::PARAM_STR);
            $statement->bindValue(':password', $password, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $statement->fetch();
            } else return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    function GetAmountUser()
    {
        $query = "SELECT COUNT(*) AS num FROM users ";
        try {
            $statement = $this->sec_pdo->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $statement->fetch();
            } else return false;
        } catch (PDOException $e) {
            return false;
        }
    }


}