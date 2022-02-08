<?php

class Controller
{
//Model and view functions
    //Return model
    public function model($model)
    {
        require_once(GRAPHSQLI_LAB_ROOT . "mvc/models/" . $model . ".php");
        return new $model;
    }

    //Include view
    public function view($view, $data = [])
    {
        require_once(GRAPHSQLI_LAB_ROOT . "mvc/views/" . $view . ".php");
    }

//Level functions
    function SetDefaultLevel()
    {
        require_once(GRAPHSQLI_LAB_ROOT . "config/config.php");
        global $_Config;
        $levels = array('low', 'medium', 'high', 'impossible');
        if (!isset($_COOKIE['lab_level']) || !in_array($_COOKIE['lab_level'], $levels)) {
            // Set security cookie to impossible if no cookie exists
            if (in_array($_Config['default_level'], $levels)) {
                $this->SetLevel($_Config['default_level']);
            } else {
                $this->SetLevel('impossible');
            }
        }
    }

    function SetLevel($level)
    {
        setcookie('lab_level', $level, null, '/', null, true, true);
        $_COOKIE['lab_level'] = $level;
    }


//Initialize components in view functions
    //Version components
    function GetVersion()
    {
        return '1.0 Beta';
    }

    //Menu componets
    function CreateMenuBlocks($page_id)
    {
        $MenuBlocks = array();
        $MenuBlocks["main_function"] = array();
        //Main function menu
        if (isset($_SESSION['username'])) {
            $MenuBlocks['main_function'][] = array('id' => 'home', 'name' => 'Home', 'url' => '/home');
            $MenuBlocks['main_function'][] = array('id' => 'instructions', 'name' => 'Instructions', 'url' => '/instructions');
            $MenuBlocks['main_function'][] = array('id' => 'setup', 'name' => 'Setup / Reset DB', 'url' => '/setup');
        } else {
            $MenuBlocks['main_function'][] = array('id' => 'setup', 'name' => 'Setup / Reset DB', 'url' => '/setup');
            $MenuBlocks['main_function'][] = array('id' => 'instructions', 'name' => 'Instructions', 'url' => '/instructions');
        }
        //Vulnerable function menu
        if (isset($_SESSION['username'])) {
            $MenuBlocks["vuln_lab"] = array();
            $MenuBlocks['vuln_lab'][] = array('id' => 'Boolean Based SQLi', 'name' => 'Boolean Based SQL Injection', 'url' => '/lab/BooleanBased/');
            $MenuBlocks['vuln_lab'][] = array('id' => 'Error Based SQLi', 'name' => 'Error Based SQL Injection', 'url' => '/lab/ErrorBased/');
            $MenuBlocks['vuln_lab'][] = array('id' => 'Out of band SQLi', 'name' => 'Out of band SQL Injection', 'url' => '/lab/OutOfBand');
            $MenuBlocks['vuln_lab'][] = array('id' => 'Time Based SQLi', 'name' => 'Time Based SQL Injection', 'url' => '/lab/TimeBased/');
            $MenuBlocks['vuln_lab'][] = array('id' => 'Union Based SQLi', 'name' => 'Union Based SQL Injection', 'url' => '/lab/UnionBased');
        }
        //Config for vulnerable function menu
        $MenuBlocks["lab_config"] = array();
        if (isset($_SESSION['username'])) {
            $MenuBlocks['lab_config'][] = array('id' => 'setting', 'name' => 'Lab Level', 'url' => '/setting');
        }

        if (isset($_SESSION['username'])) {
            $MenuBlocks['logout'] = array();
            $MenuBlocks['logout'][] = array('id' => 'logout', 'name' => 'Logout', 'url' => '/logout');
        }

        $MenuHtml = "";
        foreach ($MenuBlocks as $MenuBlock) {
            $MenuBlockHtml = '';
            foreach ($MenuBlock as $item) {
                $selectedClass = ($item['id'] == $page_id) ? 'color-color-scheme' : '';
                $MenuBlockHtml .= "<li><a href=\"{$item[ 'url' ]}\"><span class=\"{$selectedClass}\">{$item[ 'name' ]}</span></a></li>\n";
            }
            $MenuHtml .= "<li class=\"list-divider\"></li>{$MenuBlockHtml}";
        }
        return $MenuHtml;
    }

    //Get info and config of current user
    function GetCurrentInfo($page_id, $page_help, $page_source): string
    {
        $CurrentLevel = "";
        if (isset($_COOKIE['lab_level'])) {
            $CurrentLevel = strtolower(trim($_COOKIE['lab_level']));
            switch ($CurrentLevel){
                case 'low':
                    $CurrentLevel = 'low';
                    break;
                case 'medium':
                    $CurrentLevel = 'medium';
                    break;
                case 'high':
                    $CurrentLevel = 'high';
                    break;
                default:
                    $CurrentLevel = 'impossible';
            }
        } else {
            $CurrentLevel = 'impossible';
        }
        $CurrentLevel = ucfirst($CurrentLevel);

        $CurrentUser = "";
        if (isset($_SESSION["username"])) {
            $CurrentUser = $_SESSION["username"];
        }

        $UsernameHtml = "<b>Username:</b> {$CurrentUser}";
        $LevelHtml = "<b>Security Level:</b> {$CurrentLevel}";

        $CurrentInfoHtml = "";
        if (isset($_SESSION["username"])) {
            $CurrentInfoHtml = "<h4 class=\"media-heading mr-b-5\">{$UsernameHtml}</h4>\n<h4 class=\"media-heading mr-b-5\">{$LevelHtml}</h4>";
        }
        if ($page_source !== "") {
            $source_url = "/view-source/{$page_id}";
            $ButtonSource = "<button style=\"margin:2px;\" class=\"btn\" type=\"button\" id='source_button' data-source-url='" . $source_url . "}' )\">View Source</button>";
            $CurrentInfoHtml = " $CurrentInfoHtml" . "<div class=\"row\">" . $ButtonSource;
        }
        if ($page_help !== "") {
            $help_url = "/view-help/{$page_id}";
            $ButtonHelp = "<button style=\"margin:2px;\" class=\"btn\" type=\"button\" id='source_button' data-source-url='" . $help_url . "}' )\">View Help</button>";
            $CurrentInfoHtml = " $CurrentInfoHtml" . "<div class=\"row\">" . $ButtonHelp;
        }

        return $CurrentInfoHtml;
    }

    //Make eternal links components
    function GetExternalHtmlLink($link, $text = null): string
    {
        if (is_null($text)) {
            return '<a href="' . $link . '" target="_blank">' . $link . '</a>';
        } else {
            return '<a href="' . $link . '" target="_blank">' . $text . '</a>';
        }
    }

// CSRF token functions
    function CheckToken($user_token, $session_token)
    {  # Validate the given (CSRF) token
        if ($user_token !== $session_token || !isset($session_token)) {
            return false;
        }
    }

    function GenerateSessionToken()
    {  # Generate a brand new (CSRF) token
        if (isset($_SESSION['session_token'])) {
            $this->DestroySessionToken();
        }
        $_SESSION['session_token'] = md5(uniqid());
    }

    function DestroySessionToken()
    {  # Destroy any session with the name 'session_token'
        unset($_SESSION['session_token']);
    }

}

?>