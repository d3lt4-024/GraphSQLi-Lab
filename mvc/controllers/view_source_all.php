<?php

class view_source_all extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        http_response_code(403);
        header('Location: /page-error-403.html');
        exit();
    }

    public function BooleanBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Boolean Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Boolean Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $DbConnectSource = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/boolean-based/DatabaseConnect.php"), true);
            $SourceAPILow = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/boolean-based/low.php"), true);
            $SourceAPIMedium = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/boolean-based/medium.php"), true);
            $SourceAPIHigh = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/boolean-based/high.php"), true);
            $SourceAPIImpossible = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/boolean-based/impossible.php"), true);

            $body = "
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">DatabaseConnect.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$DbConnectSource}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">low.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPILow}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">medium.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIMedium}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">high.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIHigh}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">impossible.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIImpossible}
                                </div>
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

    public function ErrorBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Error Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Error Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $DbConnectSource = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/error-based/DatabaseConnect.php"), true);
            $SourceAPILow = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/error-based/low.php"), true);
            $SourceAPIMedium = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/error-based/medium.php"), true);
            $SourceAPIHigh = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/error-based/high.php"), true);
            $SourceAPIImpossible = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/error-based/impossible.php"), true);

            $body = "
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">DatabaseConnect.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$DbConnectSource}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">low.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPILow}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">medium.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIMedium}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">high.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIHigh}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">impossible.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIImpossible}
                                </div>
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

    public function OutOfBand()
    {
        if (isset($_SESSION["username"])) {
            $title = "Out of band SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Out of band SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $DbConnectSource = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/out-of-band/DatabaseConnect.php"), true);
            $SourceAPINormal = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/out-of-band/normal.php"), true);
            $SourceAPIImpossible = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/out-of-band/impossible.php"), true);

            $body = "
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">DatabaseConnect.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$DbConnectSource}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">normal.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPINormal}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">impossible.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIImpossible}
                                </div>
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

    public function TimeBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Time Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Time Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $DbConnectSource = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/time-based/DatabaseConnect.php"), true);
            $SourceAPILow = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/time-based/low.php"), true);
            $SourceAPIMedium = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/time-based/medium.php"), true);
            $SourceAPIHigh = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/time-based/high.php"), true);
            $SourceAPIImpossible = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/time-based/impossible.php"), true);

            $body = "
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">DatabaseConnect.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$DbConnectSource}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">low.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPILow}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">medium.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIMedium}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">high.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIHigh}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">impossible.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIImpossible}
                                </div>
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

    public function UnionBased()
    {
        if (isset($_SESSION["username"])) {
            $title = "Union Based SQLi :: GraphSQLi Lab v" . $this->GetVersion();
            $id = "Union Based SQLi";
            $MenuHtml = $this->CreateMenuBlocks($id);
            $InfoHtml = $this->GetCurrentInfo($id, "", "");

            $DbConnectSource = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/union-based/DatabaseConnect.php"), true);
            $SourceAPILow = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/union-based/low.php"), true);
            $SourceAPIMedium = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/union-based/medium.php"), true);
            $SourceAPIHigh = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/union-based/high.php"), true);
            $SourceAPIImpossible = highlight_string(@file_get_contents(GRAPHSQLI_LAB_ROOT . "mvc/controllers/lab_api_source/union-based/impossible.php"), true);

            $body = "
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">DatabaseConnect.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$DbConnectSource}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">low.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPILow}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">medium.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIMedium}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">high.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIHigh}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"row\">
                    <div class=\"col-md-12 widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <h1 class=\"box-title\">impossible.php</h1>
                                <div class=\"widget-body clearfix\">
                                    {$SourceAPIImpossible}
                                </div>
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