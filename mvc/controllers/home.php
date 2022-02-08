<?php

//home URL
class home extends Controller
{

    public function __construct()
    {
    }

    function index()
    {
        $title = "Welcome :: GraphSQLi Lab v" . $this->GetVersion();
        $id = "home";
        $MenuHtml = $this->CreateMenuBlocks($id);
        $InfoHtml = $this->GetCurrentInfo($id, "", "");
        $body = "
            <div class=\"row\">
                <ul class=\"col-md-3 list-unstyled\">
                    <li class=\"widget-holder widget-bg\">
                        <a href=\"#welcome\" class=\"widget-body d-flex\">
                            <div class=\"mr-3\"><i class=\"fa fa-navicon fs-22 text-primary\"></i>
                            </div>
                            <div>
                                <h5 class=\"mt-0 mb-1 fs-16 fw-400 sub-heading-font-family\">Welcome</h5><small>About GrapSQLi Lab.</small>
                            </div>
                        </a>
                    </li>
                    <li class=\"widget-holder widget-bg\">
                        <a href=\"#sqli\" class=\"widget-body d-flex\">
                            <div class=\"mr-3\"><i class=\"fa fa-database fs-22 text-info\"></i>
                            </div>
                            <div>
                                <h5 class=\"mt-0 mb-1 fs-16 fw-400 sub-heading-font-family\">SQL Injection</h5><small>About SQL Injection.</small>
                            </div>
                        </a>
                    </li>
                    <li class=\"widget-holder widget-bg\">
                        <a href=\"#graphql\" class=\"widget-body d-flex\">
                            <div class=\"mr-3\"><i class=\"fa fa-server fs-22 text-danger\"></i>
                            </div>
                            <div>
                                <h5 class=\"mt-0 mb-1 fs-16 fw-400 sub-heading-font-family\">GraphQL</h5><small>About GraphQL.</small>
                            </div>
                        </a>
                    </li>
                    <li class=\"widget-holder widget-bg\">
                        <a href=\"#instructions\" class=\"widget-body d-flex\">
                            <div class=\"mr-3\"><i class=\"fa fa-info fs-22 text-success\"></i>
                            </div>
                            <div>
                                <h5 class=\"mt-0 mb-1 fs-16 fw-400 sub-heading-font-family\">Instructions</h5><small>General instructions.</small>
                            </div>
                        </a>
                    </li>
                    <li class=\"widget-holder widget-bg\">
                        <a href=\"#warning\" class=\"widget-body d-flex\">
                            <div class=\"mr-3\"><i class=\"fa fa-warning fs-22 text-warning\"></i>
                            </div>
                            <div>
                                <h5 class=\"mt-0 mb-1 fs-16 fw-400 sub-heading-font-family\">Warning</h5><small>Warning about deploy.</small>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.col-md-3 -->
                <div class=\"col-md-9\">
                    <div class=\"widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <div class=\"accordion accordion-minimal\" id=\"welcome\" role=\"tablist\" aria-multiselectable=\"true\">
                                    <div class=\"d-flex mb-4 mt-2\">
                                        <div class=\"mr-3\"><i class=\"fa fa-navicon fs-24 text-primary\"></i>
                                        </div>
                                        <div>
                                            <h5 class=\"mt-0 mb-1 fs-22 fw-400 sub-heading-font-family\">Welcome</h5><small>About GrapSQLi Lab.</small>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"WelcomeHeading\">
                                            <h5 class=\"card-title fw-300\"><a role=\"button\" data-toggle=\"collapse\" data-parent=\"#welcome\" data-target=\"#aboutLab\" aria-expanded=\"true\" aria-controls=\"aboutLab\"><strong>GraphSQLi Lab</strong></a></h5>
                                        </div>
                                        <div id=\"aboutLab\" class=\"card-collapse collapse show\" role=\"tabpanel\" aria-labelledby=\"WelcomeHeading\">
                                            <div class=\"card-block\">This lab is a web application, using PHP, MySQL and GraphQL API that contains 5 laboratories corresponding to 5 main types of SQL Injection, which is:
                                            <ul>
                                                <li>Union based SQL Injection</li>
                                                <li>Time based SQL Injection</li>
                                                <li>Error based SQL Injection</li>
                                                <li>Boolean based SQL Injection</li>
                                                <li>Out of band SQL Injection</li>
                                            </ul>
                                            With Union based SQL Injection, Time based SQL Injection, Error based SQL Injection and Boolean based SQL Injection lab, there will be 4 levels including: low, medium, high and impossible<br><br>
                                            With Out of band SQL Injection lab, since this type of SQL Injection is quite rare in the real web environment, there will only be 2 levels: normal and impossible<br><br>
                                            The  main goal of GraphSQLi Lab is to create an environment that supports lecturers/students to teach/practice 
                                            about GraphQL security and SQL Injection vulnerabilities. Also helps developers  better understand the processes of securing GraphQL web 
                                            applications<br><br>The aim of this lab is to 
                                             <b>practice exploiting forms of SQL Injection in a GraphQL API</b>, with <b>various levels of difficultly</b>, with a simple straightforward interface.</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.accordion -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <div class=\"widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <div class=\"accordion accordion-minimal\" id=\"sqli\" role=\"tablist\" aria-multiselectable=\"true\">
                                    <div class=\"d-flex mb-4 mt-2\">
                                        <div class=\"mr-3\"><i class=\"fa fa-database fs-24 text-info\"></i>
                                        </div>
                                        <div>
                                            <h5 class=\"mt-0 mb-1 fs-22 fw-400 sub-heading-font-family\">SQL Injection</h5><small>About SQL Injection.</small>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"AbouSQLiHeading\">
                                            <h5 class=\"card-title fw-300\"><a role=\"button\" data-toggle=\"collapse\" data-parent=\"#sqli\" data-target=\"#aboutSQLi\" aria-expanded=\"true\" aria-controls=\"aboutSQLi\"><strong>SQL Injection.</strong></a></h5>
                                        </div>
                                        <div id=\"aboutSQLi\" class=\"card-collapse collapse show\" role=\"tabpanel\" aria-labelledby=\"AbouSQLiHeading\">
                                            <div class=\"card-block\">SQL Injection (SQLi) is a type of an injection attack that makes it possible to execute malicious SQL statements. These statements control
                                                 a database server behind a web application. Attackers can use SQL Injection vulnerabilities to bypass application security measures. They can go around 
                                                 authentication and authorization of a web page or web application and retrieve the content of the entire SQL database. They can also use SQL Injection to add, 
                                                 modify, and delete records in the database.<br><br>An SQL Injection vulnerability may affect any website or web application that uses an SQL database such as 
                                                 MySQL, Oracle, SQL Server, or others. Criminals may use it to gain unauthorized access to your sensitive data: customer information, personal data, 
                                                 trade secrets, intellectual property, and more. SQL Injection attacks are one of the oldest, most prevalent, and most dangerous web application vulnerabilities. The OWASP organization (Open Web Application Security Project) lists injections in their OWASP Top 10 2017 document as the number one threat to web application security.</p></div>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"TypeSQLiHeading\">
                                            <h5 class=\"card-title fw-300\"><a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#sqli\" data-target=\"#typeSQLi\" aria-expanded=\"false\" aria-controls=\"typeSQLi\"><strong>Types of SQL Injection</strong></a></h5>
                                        </div>
                                        <div id=\"typeSQLi\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"TypeSQLiHeading\">
                                            <div class=\"card-block\">SQL Injection can be classified into three major categories – In-band SQLi, Inferential SQLi and Out-of-band SQLi.<br><br><b>In-band SQL Injection</b> 
                                                is the most common and easy-to-exploit of SQL Injection attacks. In-band SQL Injection occurs when an attacker is able to use the same communication channel to both launch 
                                                the attack and gather results. The two most common types of in-band SQL Injection are <b>Error based SQLi</b> and <b>Union based SQLi</b>.<br><br>
                                                <b>Inferential SQL Injection</b>, unlike in-band SQLi, may take longer for an attacker to exploit, however, it is just as dangerous as any other form of 
                                                SQL Injection. In an inferential SQLi attack, no data is actually transferred via the web application and the attacker would not be able to see the result of 
                                                an attack in-band (which is why such attacks are commonly referred to as “<b>Blind SQL Injection</b>”). Instead, an attacker is able to reconstruct the database 
                                                structure by sending payloads, observing the web application’s response and the resulting behavior of the database server. The two types of inferential SQL Injection are <b>Boolean based SQLi</b> and <b>Time based SQLi</b>.
                                                <br><br><b>Out-of-band SQL Injection</b> is not very common, mostly because it depends on features being enabled on the database server being used by the web 
                                                application. Out-of-band SQL Injection occurs when an attacker is unable to use the same channel to launch the attack and gather results.</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.accordion -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <div class=\"widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <div class=\"accordion accordion-minimal\" id=\"graphql\" role=\"tablist\" aria-multiselectable=\"true\">
                                    <div class=\"d-flex mb-4 mt-2\">
                                        <div class=\"mr-3\"><i class=\"fa fa-server fs-24 text-danger\"></i>
                                        </div>
                                        <div>
                                            <h5 class=\"mt-0 mb-1 fs-22 fw-400 sub-heading-font-family\">GraphQL</h5><small>About GraphQL.</small>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"AbouGraphQLHeading\">
                                            <h5 class=\"card-title fw-300\"><a role=\"button\" data-toggle=\"collapse\" data-parent=\"#graphql\" data-target=\"#aboutGraphQL\" aria-expanded=\"true\" aria-controls=\"aboutGraphQL\"><strong>GraphQL</strong></a></h5>
                                        </div>
                                        <div id=\"aboutGraphQL\" class=\"card-collapse collapse show\" role=\"tabpanel\" aria-labelledby=\"AbouGraphQLHeading\">
                                            <div class=\"card-block\">GraphQL is an open-source data query and manipulation language for APIs, and a runtime for fulfilling queries with existing data. 
                                                GraphQL was developed internally by Facebook in 2012 before being publicly released in 2015. On 7 November 2018, the GraphQL project was moved from 
                                                Facebook to the newly-established GraphQL Foundation, hosted by the non-profit Linux Foundation. Since 2012, GraphQL's rise has followed the adoption 
                                                timeline as set out by Lee Byron, GraphQL's creator, with accuracy. Byron's goal is to make GraphQL omnipresent across web platforms.<br><br>
                                                It provides an approach to developing web APIs and has been compared and contrasted with REST and other web service architectures. It allows clients to 
                                                define the structure of the data required, and the same structure of the data is returned from the server, therefore preventing excessively large 
                                                amounts of data from being returned, but this has implications for how effective web caching of query results can be. The flexibility and richness of the 
                                                query language also adds complexity that may not be worthwhile for simple APIs Despite the name, GraphQL does not provide the richness of graph operations 
                                                that one might find in a full-fledged graph database such as Neo4j, or even in dialects of SQL that support transitive closure. For example, a GraphQL 
                                                interface that reports the parents of an individual cannot return, in a single query, the set of all their ancestors.<br><br>
                                                GraphQL consists of a type system, query language and execution semantics, static validation, and type introspection. It supports reading, 
                                                writing (mutating), and subscribing to changes to data (realtime updates – most commonly implemented using Websockets). GraphQL servers are available 
                                                for multiple languages, including Haskell, JavaScript, Perl, Python, Ruby, Java, C++, C#, Scala, Go, Rust, Elixir, Erlang, PHP, R, D and Clojure.<br><br>
                                                On 9 February 2018, the GraphQL Schema Definition Language (SDL) became part of the specification.</div>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"MoreHeading\">
                                            <h5 class=\"card-title fw-300\"><a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#graphql\" data-target=\"#moreAboutGraphQL\" aria-expanded=\"false\" aria-controls=\"moreAboutGraphQL\"><strong>More about GraphQL</strong></a></h5>
                                        </div>
                                        <div id=\"moreAboutGraphQL\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"MoreHeading\">
                                            <div class=\"card-block\">You can read more about GraphQL and the security issues surrounding GraphQL here:<br><br>
                                                <ul>
                                                    <li>" . $this->GetExternalHtmlLink('https://graphql.org/learn/', 'GraphQL Document') . "</li>
                                                    <li>" . $this->GetExternalHtmlLink('https://infosecwriteups.com/hacking-graphql-for-fun-and-profit-part-1-understanding-graphql-basics-72bb3dd22efa', 'Hacking GraphQL for Fun and Profit — Part 1') . "</li>
                                                    <li>" . $this->GetExternalHtmlLink('https://infosecwriteups.com/hacking-graphql-for-fun-and-profit-part-2-methodology-and-examples-5992093bcc24', 'Hacking GraphQL for Fun and Profit — Part 2') . "</li>
                                                    <li>" . $this->GetExternalHtmlLink('https://the-bilal-rizwan.medium.com/graphql-common-vulnerabilities-how-to-exploit-them-464f9fdce696', 'GraphQL — Common vulnerabilities & how to exploit them') . "</li>
                                                </ul></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.accordion -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <div class=\"widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <div class=\"accordion accordion-minimal\" id=\"instructions\" role=\"tablist\" aria-multiselectable=\"true\">
                                    <div class=\"d-flex mb-4 mt-2\">
                                        <div class=\"mr-3\"><i class=\"fa fa-info fs-24 text-success\"></i>
                                        </div>
                                        <div>
                                            <h5 class=\"mt-0 mb-1 fs-22 fw-400 sub-heading-font-family\">Instructions</h5><small>General instructions.</small>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"generalInsHeading\">
                                            <h5 class=\"card-title fw-300\"><a role=\"button\" data-toggle=\"collapse\" data-parent=\"#instructions\" data-target=\"#generalIns\" aria-expanded=\"true\" aria-controls=\"generalIns\"><strong>General Instructions</strong></a></h5>
                                        </div>
                                        <div id=\"generalIns\" class=\"card-collapse collapse show\" role=\"tabpanel\" aria-labelledby=\"generalInsHeading\">
                                            <div class=\"card-block\">User can choose any type of SQL Injection at any difficulty to practice mining. There is not a fixed object to complete a module; however users 
                                                should feel that they have successfully exploited the system as best as they possible could by using SQL Injections.</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.accordion -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <div class=\"widget-holder\">
                        <div class=\"widget-bg\">
                            <div class=\"widget-body clearfix\">
                                <div class=\"accordion accordion-minimal\" id=\"warning\" role=\"tablist\" aria-multiselectable=\"true\">
                                    <div class=\"d-flex mb-4 mt-2\">
                                        <div class=\"mr-3\"><i class=\"fa fa-warning fs-24 text-warning\"></i>
                                        </div>
                                        <div>
                                            <h5 class=\"mt-0 mb-1 fs-22 fw-400 sub-heading-font-family\">Warning</h5><small>Warning about deploy.</small>
                                        </div>
                                    </div>
                                    <div class=\"card\">
                                        <div class=\"card-header\" role=\"tab\" id=\"warningHeading\">
                                            <h5 class=\"card-title fw-300\"><a role=\"button\" data-toggle=\"collapse\" data-parent=\"#warning\" data-target=\"#warningDeploy\" aria-expanded=\"true\" aria-controls=\"warningDeploy\"><strong>Warning</strong></a></h5>
                                        </div>
                                        <div id=\"warningDeploy\" class=\"card-collapse collapse show\" role=\"tabpanel\" aria-labelledby=\"warningHeading\">
                                            <div class=\"card-block\">GraphSQLi Lab contains multi types of SQLi vulnerability! One of them can lead to information security risks if deployed on Internet facing servers. <b>Do not deploy it on your hosting provider's public html folder 
                                                or any Internet facing servers</b>, as they will be compromised. It is recommend using a virtual machine 
                                                (such as " . $this->GetExternalHtmlLink('https://www.virtualbox.org/', 'VirtualBox') . " or " .
            $this->GetExternalHtmlLink('https://www.vmware.com/', 'VMware') . "), which is set to NAT networking mode. 
                                                Inside a guest machine, you can download and install " . $this->GetExternalHtmlLink('https://laragon.org/download/index.html', 'Laragon') .
            " for the web server and database.</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.accordion -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                </div>
                <!-- /.col-md-9 -->
            </div>";
        $this->view("page-template", [
            "menu" => $MenuHtml,
            "info" => $InfoHtml,
            "title" => $title,
            "page_body" => $body
        ]);
    }
}
