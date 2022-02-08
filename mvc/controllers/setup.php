<?php
require_once GRAPHSQLI_LAB_ROOT . 'config/config.php';

class setup extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        //Title and menu block
        $title = "Setup :: GraphSQLi Lab v" . $this->GetVersion();
        $id = "setup";
        $MenuHtml = $this->CreateMenuBlocks($id);
        $InfoHtml = $this->GetCurrentInfo($id, "", "");

        //Script js call api for create database
        if (isset($_SESSION["username"])) {
            $script_call_api = "
            <script type=\"text/javascript\">
                $(document).ready(function() {
                    $('#createDbForm').submit(function(e) {
                        e.preventDefault();
                        var formData = {
                            'user_token': document.getElementById('UserTokenField').value,
                            'action':  document.getElementById('createDbBtn').value
                        };
                        $.ajax({
                            type: \"POST\",
                            url: '/api/create_db',
                            datatype: 'json',
                            data: JSON.stringify(formData),
                            success: function(result)
                            {
                                if(typeof result.error == \"undefined\"){
                                    swal({
                                        title: 'Success',
                                        text: result.result,
                                        type: 'success'
                                    });
                                } else{
                                    swal({
                                        title: 'Error',
                                        text: result.error.message,
                                         type: 'error'
                                    });
                                }
                            }
                        });
                    });
                });                                
            </script>            
            ";
        } else {
            $script_call_api = "
            <script type=\"text/javascript\">
                $(document).ready(function() {
                    $('#createDbForm').submit(function(e) {
                        e.preventDefault();
                        var formData = {
                            'user_token': document.getElementById('UserTokenField').value,
                            'action':  document.getElementById('createDbBtn').value
                        };
                        $.ajax({
                            type: \"POST\",
                            url: '/api/create_db',
                            datatype: 'json',
                            data: JSON.stringify(formData),
                            success: function(result)
                            {
                                if(typeof result.error == \"undefined\"){
                                    swal({
                                        title: 'Success',
                                        text: result.result,
                                        type: 'success'
                                    });
                                    window.location.href = \"\login\";
                                } else{
                                    swal({
                                        title: 'Error',
                                        text: result.error.message,
                                         type: 'error'
                                    });
                                }
                            }
                        });
                    });
                });                                
            </script>  
            ";
        }
        //Config variable
        global $_Config;
        $SERVER_NAME = 'Web Server SERVER_NAME: <b>' . $_SERVER['SERVER_NAME'] . '</b>';
        $OS = 'Operating system: <b>' . (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : '*nix') . '</b>';
        $phpMySQL = 'PHP module mysql: <span class="' . ((extension_loaded('mysqli') && function_exists('mysqli_query')) ? 'success">Installed' : 'failure">Missing') . '</span>';  //vuln lab will use mysqli for low, medium and hard level
        $phpPDO = 'PHP module pdo_mysql: <span class="' . (extension_loaded('pdo_mysql') ? 'success">Installed' : 'failure">Missing') . '</span>'; //use for impossible vuln lab and core connection
        $database_type_name = "MySQL/MariaDB";
        $MYSQL_USER = 'Database username: <b>' . $_Config['db_user'] . '</b>';
        $MYSQL_PASS = 'Database password: <b>' . (($_Config['db_password'] != "") ? '******' : '*blank*') . '</b>';
        $MYSQL_DB = 'Database database: <b>' . $_Config['db_database'] . '</b>';
        $MYSQL_SERVER = 'Database host: <b>' . $_Config['db_server'] . '</b>';
        $MYSQL_PORT = 'Database port: <b>' . $_Config['db_port'] . '</b>';
        //Page body
        $this->GenerateSessionToken();
        $body = "
        <div class=\"row\">
            <div class=\"col-md-12 widget-holder\">
                <div class=\"widget-bg\">
                    <div class=\"widget-body clearfix\">
                        <h1 class=\"box-title\">Database Setup</h1>
                        <p>Click on the 'Create / Reset Database' button below to create or reset your database.<br />
                            If you get an error make sure you have the correct user credentials in: <b>" . realpath(getcwd() . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php") . "</b></p>
                        
                        <p>If the database already exists, <b>it will be cleared and the data will be reset</b>.<br />
                            You can also use this to reset the administrator credentials (\"<b>admin</b> // <b>password</b>\") at any stage.</p>
                    </div>
                    <!-- /.widget-body -->
                </div>
                <!-- /.widget-bg -->
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-md-12 widget-holder\">
                <div class=\"widget-bg\">
                    <div class=\"widget-body clearfix\">
                        <h1 class=\"box-title\">Setup Check</h1>
                        {$SERVER_NAME}<br />
                        <br />
                        {$OS}<br />
                        <br />
                        PHP version: <b>" . phpversion() . "</b><br />
                        {$phpMySQL}<br />
                        {$phpPDO}<br />
                        <br />
                        Backend database: <b>{$database_type_name}</b><br />
                        {$MYSQL_USER}<br />
                        {$MYSQL_PASS}<br />
                        {$MYSQL_DB}<br />
                        {$MYSQL_SERVER}<br />
                        {$MYSQL_PORT}<br />
                        <br />        
                        <br /><br /><br />
                        <!-- Create db button -->
                        <form  method=\"POST\" id=\"createDbForm\">
                             <div class=\"form-group\">
                                <div class=\"form-group row\">
                                    <div class=\"col-md-1 btn-list\">
                                        <button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"create_db\" name=\"create_db\" id=\"createDbBtn\">Create / Reset Database</button>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <input type='hidden' name='user_token' value='{$_SESSION[ 'session_token' ]}' id=\"UserTokenField\"/>
                            </div>
                        </form>
                        {$script_call_api}
                    </div>
                    <!-- /.widget-body -->
                </div>
                <!-- /.widget-bg -->
            </div>
        </div>
        ";
        $this->view("page-template", [
            "menu" => $MenuHtml,
            "info" => $InfoHtml,
            "title" => $title,
            "page_body" => $body
        ]);
    }

}