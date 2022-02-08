<?php
require_once GRAPHSQLI_LAB_ROOT . "public/includes/Parsedown.php";

class instructions extends Controller
{
    private $parsedown;

    public function __construct()
    {
        $this->parsedown = new Parsedown();
    }

    function index()
    {
        $title = "Instructions :: GraphSQLi Lab v" . $this->GetVersion();
        $id = "instructions";
        $MenuHtml = $this->CreateMenuBlocks($id);
        $InfoHtml = $this->GetCurrentInfo($id, "", "");
        $instructions = file_get_contents(GRAPHSQLI_LAB_ROOT . 'README.md');
        $instructions = $this->parsedown->text($instructions);
        $body = "
        <div class=\"row\">
            <div class=\"col-md-12 widget-holder\">
                <div class=\"widget-bg\">
                    <div class=\"widget-body clearfix\">
                        {$instructions}
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