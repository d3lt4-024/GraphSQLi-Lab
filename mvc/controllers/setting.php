<?php

class setting extends Controller
{
    public function __construct()
    {
    }

    function index()
    {
        if (isset($_SESSION["username"])) {
            $title = "Setting :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "home";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $CurrentLevel = "";
            if (isset($_COOKIE['lab_level'])) {
                $CurrentLevel = strtolower(trim($_COOKIE['lab_level']));
                switch ($CurrentLevel) {
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

            $securityOptionsHtml = '';
            foreach (array('low', 'medium', 'high', 'impossible') as $securityLevel) {
                $selected = '';
                if ($securityLevel === $CurrentLevel) {
                    $selected = ' selected="selected"';
                }
                $securityOptionsHtml .= "<option value=\"{$securityLevel}\"{$selected}>" . ucfirst($securityLevel) . "</option>";
            }

            $this->GenerateSessionToken();
            $body = "
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Setting for GraphSQLi Lab Level</h1>
                            <div class=\"widget-body clearfix\">
                            Lab level is currently: <b>" . ucfirst($CurrentLevel) . "</b>
                            <br><br>
                            With Union based SQL Injection, Time based SQL Injection, Error based SQL Injection and Boolean based SQL Injection lab, there will be 4 levels including:
                            <ul>
                                <li> Low - This security level is completely vulnerable and <b>has no security measures at all</b>. It's use is to be as an example of how web 
                                application vulnerabilities manifest through bad coding practices and to serve as a platform to teach or learn basic exploitation techniques. And 
                                in this level, you can use some open source tools to automatically exploit SQL Injection</li>
                                <li> Medium - This setting is mainly to give an example to the user of <b>bad security practices</b>, where the developer has tried but failed to 
                                secure an application. It also acts as a challenge to users to refine their exploitation techniques. And 
                                in this level, you can still use some open source tools to automate SQL Injection exploits but it will need a few tweaks to be successful.</li>
                                <li> High - This option is an extension to the medium difficulty, with a mixture of <b>harder or alternative bad practices</b> to attempt to secure the 
                                code. And in this level, you won't be able to use some open source tools to automate SQL Injection exploits. 
                                Instead, you should write your own auto-exploit code</li>
                                <li> Impossible - This level should be <b>secure against all vulnerabilities</b>. It is used to compare the vulnerable source code to the secure source 
                                code.</li>
                            </ul><br>
                            With Out of band SQL Injection lab, there will be 2 levels including: normal and impossible.
                            <ul>
                                <li> Normal - Same to low level at Union based SQL Injection, Time based SQL Injection, Error based SQL Injection and Boolean based SQL Injection lab. If 
                                lab level at Union based SQL Injection, Time based SQL Injection, Error based SQL Injection and Boolean based SQL Injection lab is low, medium or high, level of 
                                Out of band SQL Injection lab will be set to normal</li>
                                <li> Impossible - This level should be <b>secure against all vulnerabilities</b>. It is used to compare the vulnerable source code to the secure source 
                                code.</li>
                            </ul><br><br>
                            </div>
                            <form method=\"POST\" id=\"setLevelForm\">
                                <div class=\"form-group row\">
                                        <label class=\"col-md-1 col-form-label\" for=\"l13\">Lab Level</label>
                                        <div class=\"col-md-3\">
                                            <div class=\"input-group\">
                                                <select class=\"form-control\" name=\"level\" id=\"setLevelOpt\">
                                                    {$securityOptionsHtml}
                                                </select>
                                            </div>
                                        </div>
                                        <div class=\"col-md-3\">
                                            <div class=\"input-group\">
                                                <button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"set_level\"
                                                         id=\"setLevelBtn\"'>Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <div class=\"form-group row\">
                                            <input type='hidden' name='user_token' value='{$_SESSION[ 'session_token' ]}' id=\"UserTokenField\"/>
                                        </div>
                                    </div>
                            </form>
                            <script type=\"text/javascript\">
                                $(document).ready(function() {
                                    $('#setLevelForm').submit(function(e) {
                                        e.preventDefault();
                                        var formData = {
                                            'lab_level': document.getElementById('setLevelOpt').value,
                                            'user_token': document.getElementById('UserTokenField').value,
                                            'action':  document.getElementById('setLevelBtn').value
                                        };
                                        $.ajax({
                                            type: \"POST\",
                                            url: '/api/set_level',
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
                                                    location.reload();
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
                        </div>
                    </div>
                </div>
            </div>
            ";
            $this->view("page-template", [
                "menu" => $MenuHtml,
                "info" => $InfoHtml,
                "title" => $title,
                "page_body" => $body
            ]);
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }
}