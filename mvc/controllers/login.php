<?php

class login extends Controller
{

    public function __construct()
    {
    }

    function index()
    {
        if (isset($_SESSION["username"])) {
            header("location: /home");
            exit();
        } else {
            $this->GenerateSessionToken();
            $this->view("page-login", [
                "title" => "Login :: GraphSQLi Lab v " . $this->GetVersion(),
                "csrf_token" => $_SESSION['session_token']
            ]);
        }
    }

}