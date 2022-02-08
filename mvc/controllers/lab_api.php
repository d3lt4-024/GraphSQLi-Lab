<?php

class lab_api extends Controller
{
    public function __construct()
    {
    }

    function index()
    {
        http_response_code(403);
        header('Location: /page-error-403.html');
        exit();
    }

    function BooleanBased()
    {
        if (isset($_SESSION["username"])) {
            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            if ($CurrentLevel === "low") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/boolean-based/low.php';
            } else if ($CurrentLevel === "medium") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/boolean-based/medium.php';
            } else if ($CurrentLevel === "high") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/boolean-based/high.php';
            } else {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/boolean-based/impossible.php';
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function ErrorBased()
    {
        if (isset($_SESSION["username"])) {
            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            if ($CurrentLevel === "low") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/error-based/low.php';
            } else if ($CurrentLevel === "medium") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/error-based/medium.php';
            } else if ($CurrentLevel === "high") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/error-based/high.php';
            } else {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/error-based/impossible.php';
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function OutOfBand()
    {
        if (isset($_SESSION["username"])) {
            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            if ($CurrentLevel === "impossible") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/out-of-band/impossible.php';
            } else {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/out-of-band/normal.php';
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function TimeBased()
    {
        if (isset($_SESSION["username"])) {
            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            if ($CurrentLevel === "low") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/time-based/low.php';
            } else if ($CurrentLevel === "medium") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/time-based/medium.php';
            } else if ($CurrentLevel === "high") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/time-based/high.php';
            } else {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/time-based/impossible.php';
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function UnionBased()
    {
        if (isset($_SESSION["username"])) {
            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            if ($CurrentLevel === "low") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/union-based/low.php';
            } else if ($CurrentLevel === "medium") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/union-based/medium.php';
            } else if ($CurrentLevel === "high") {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/union-based/high.php';
            } else {
                require_once GRAPHSQLI_LAB_ROOT . 'mvc/controllers/lab_api_source/union-based/impossible.php';
            }
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }
}