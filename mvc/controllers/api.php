<?php
require_once GRAPHSQLI_LAB_ROOT . 'config/config.php';

//api URL
class api extends Controller
{
    private $User;

    public function __construct()
    {
        $this->User = $this->model("User");
    }

    public function index()
    {
        http_response_code(403);
        header('Location: /page-error-403.html');
        exit();
    }

    //Function handle login request
    public function login()
    {
        $template = "";
        //Get and decode json from input
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);
        if (isset($input["action"]) && $input["action"] === "login") {
            try {
                //Checking empty fields
                if (empty($input["username"])) {
                    throw new Exception("Username is required!");
                }
                if (empty($input["password"])) {
                    throw new Exception("Password is required!");
                }
                if (empty($input["user_token"])) {
                    throw new Exception("User token is required!");
                }
                //Delete space and special char in form data
                $username = trim($input["username"]);
                $password = hash('md5', trim($input["password"]));
                $user_token = trim($input["user_token"]);
                //Anti-CSRF
                if (array_key_exists("session_token", $_SESSION)) {
                    $session_token = $_SESSION['session_token'];
                } else {
                    $session_token = "";
                }
                //Check CSRF token
                if ($this->CheckToken($user_token, $session_token)) {
                    throw new Exception("CSRF token is incorrect, try again!"); //error message
                }
                $result = $this->User->LoginCheck($username, $password); //check if user is login
                if ($result == false) {
                    throw new Exception("Username or Password is wrong, try again!"); //error message
                } else {
                    session_regenerate_id();
                    $_SESSION['username'] = $result['Username']; //save username to session
                    $this->SetDefaultLevel();//set default level in config.php
                    $msg = "Login success as " . $result['Username'];
                    $template = [
                        'result' => $msg
                    ];
                }
            } //end of try block
            catch (Exception $e) {
                $msg = $e->getMessage();
                $template = [
                    'error' => [
                        'message' => $msg
                    ]
                ];
            }
        } else {
            $msg = "Something was wrong!";
            $template = [
                'error' => [
                    'message' => $msg
                ]
            ];
        }
        //Response result
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($template);
    }

    //Function handle create/reset database request
    public function create_db()
    {
        $template = "";
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        if (isset($input["action"]) && $input["action"] === "create_db") {
            //checking empty fields
            if (empty($input["user_token"])) {
                throw new Exception("User token is required!");
            }
            //delete space and special char in form data
            $user_token = trim($input["user_token"]);
            //Anti-CSRF
            if (array_key_exists("session_token", $_SESSION)) {
                $session_token = $_SESSION['session_token'];
            } else {
                $session_token = "";
            }
            //Check CSRF token
            if ($this->CheckToken($user_token, $session_token)) {
                throw new Exception("CSRF token is incorrect, try again!"); //error messenge
            }
            $result = $this->InitDatabase();
            if ($result === true) {
                $template = [
                    'result' => "Create database success"
                ];
            } else {
                $template = [
                    'error' => [
                        'message' => $result
                    ]
                ];
            }
        } else {
            $msg = "Something was wrong!";
            $template = [
                'error' => [
                    'message' => $msg
                ]
            ];
        }
        //Response result
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($template);
    }

    //Function handle set level for lab request

    private function InitDatabase()
    {
        global $_Config;
        //If db user is not root
        if ($_Config['db_user'] == "root") {
            return "You should not use root user to connect to MySQL database. Create another user and grant permission on {$_Config['db_database']}. Read README.md for instruction";
        }
        //Create connection to database
        try {
            $con = new PDO('mysql:host=' . $_Config['db_server'] . ';port=' . $_Config['db_port'], $_Config['db_user'], $_Config['db_password']);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0); //Not allow executed multi query
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
        //Drop if database already has been created
        $drop_query = "DROP DATABASE IF EXISTS {$_Config[ 'db_database' ]};";
        try {
            $statement = $con->prepare($drop_query);
            if ($statement->execute() === false) {
                return "Could not drop existing database";
            }
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
        //Create database
        $create_query = "CREATE DATABASE {$_Config[ 'db_database' ]};";
        try {
            $statement = $con->prepare($create_query);
            if ($statement->execute() === false) {
                return "Could not create database";
            }
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
        //Select database
        $select_db_query = "USE {$_Config[ 'db_database' ]}";
        try {
            $statement = $con->prepare($select_db_query);
            if ($statement->execute() === false) {
                return "Could not use database";
            }
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
        //Create table
        $create_table_query = "CREATE TABLE users (UserId int(6),FirstName varchar(15),LastName varchar(15), Username varchar(15), Password varchar(32), PRIMARY KEY (UserId));";
        try {
            $statement = $con->prepare($create_table_query);
            if ($statement->execute() === false) {
                return "users table has been already created in {$_Config['db_database']} database";
            }
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
        //Insert data into table
        $insert_query = "INSERT INTO users VALUES
            ('1','admin','admin','admin',MD5('password')),
            ('2','Gordon','Brown','gordonb',MD5('abc123')),
            ('3','Hack','Me','1337',MD5('charley')),
            ('4','Pablo','Picasso','pablo',MD5('letmein')),
            ('5','Bob','Smith','smithy',MD5('password'));";
        try {
            $statement = $con->prepare($insert_query);
            if ($statement->execute() === false) {
                return "Data could not be inserted into users table";
            }
        } catch (PDOException $exception) {
            return $exception->getMessage();
        }
        return true;
    }

    //Function handle change id request in high level lab

    public function set_level()
    {
        if (isset($_SESSION["username"])) {
            $template = "";
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);

            if (isset($input["action"]) && $input["action"] === "set_level") {
                //checking empty fields
                if (empty($input["lab_level"])) {
                    throw new Exception("Lab level is required!");
                }
                if (empty($input["user_token"])) {
                    throw new Exception("User token is required!");
                }
                //delete space and special char in form data
                $lab_level = strtolower(trim($input["lab_level"]));
                switch ($lab_level) {
                    case 'low':
                        $lab_level = 'low';
                        break;
                    case 'medium':
                        $lab_level = 'medium';
                        break;
                    case 'high':
                        $lab_level = 'high';
                        break;
                    default:
                        $lab_level = 'impossible';
                }
                $user_token = trim($input["user_token"]);
                //Anti-CSRF
                if (array_key_exists("session_token", $_SESSION)) {
                    $session_token = $_SESSION['session_token'];
                } else {
                    $session_token = "";
                }
                //Check CSRF token
                if ($this->CheckToken($user_token, $session_token)) {
                    throw new Exception("CSRF token is incorrect, try again!"); //error messenge
                }
                $this->SetLevel($lab_level);
                if (strtolower(trim($_COOKIE["lab_level"])) === $lab_level) {
                    $template = [
                        'result' => "Security level set to {$lab_level}"
                    ];
                } else {
                    $template = [
                        'error' => [
                            'message' => "Something was wrong!"
                        ]
                    ];
                }
            } else {
                $msg = "Something was wrong!";
                $template = [
                    'error' => [
                        'message' => $msg
                    ]
                ];
            }
            //Response result
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($template);
        } else {
            //This action require user has been logged to use
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    //Function create database and insert data to that database

    public function change_id()
    {
        if (isset($_SESSION["username"])) {
            $template = "";
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);

            if (isset($input["action"]) && $input["action"] === "change_id") {
                //checking empty fields
                if (empty($input["id"])) {
                    throw new Exception("User id is required!");
                }
                //delete space and special char in form data
                $id = strtolower(trim($input["id"]));
                $_SESSION['id'] = $id;
                $template = [
                    'result' => "User id set to {$id}"
                ];
            } else {
                $msg = "Something was wrong!";
                $template = [
                    'error' => [
                        'message' => $msg
                    ]
                ];
            }
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($template);
        } else {
            //This action require user has been logged to use
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }
}