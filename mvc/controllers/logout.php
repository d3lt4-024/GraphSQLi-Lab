<?php

class logout extends Controller
{

    public function __construct()
    {
    }

    function index()
    {
        session_destroy();
        setcookie("lab_level", "", time() - 3600, "/", false, true);
        header("location: /");
    }
}
