<?php
require_once GRAPHSQLI_LAB_ROOT . 'vendor/autoload.php';
require_once 'DatabaseConnect.php';

use App\DatabaseConnect;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

//Not show error stacktrace
error_reporting(0);
try {
    //Connect database
    DB_Connect();
    //Define user type for GraphQL
    $userType = new ObjectType([
        'name' => 'user',
        'description' => 'User information',
        'fields' => function () use (&$userType) {
            return [
                'UserId' => [ //Column user_id in db
                    'type' => Type::id(),
                    'description' => 'User ID'
                ],
                'FirstName' => [ //Column first_name in db
                    'type' => Type::string(),
                    'description' => 'User first name'
                ],
                'LastName' => [ //Column last_name in db
                    'type' => Type::string(),
                    'description' => 'User last name'
                ]
            ];
        }
    ]);
    //Define query type for GraphQL
    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => function () use ($userType, &$queryType) {
            return [
                'user' => [
                    'type' => Type::listOf($userType), //This will response array of user
                    'description' => 'Return user by id',
                    'resolve' => function ($root, $args) {
                        //Get id from session
                        if (isset($_SESSION['id'])) {
                            $id = $_SESSION['id'];
                        } else {
                            $id = 0;
                        }
                        $querySQL = "SELECT UserId, FirstName, LastName FROM users WHERE UserId  = '$id' LIMIT 1"; //Create query from concat string like this will cause SQLi
                        //Get user from query db
                        $statement = $GLOBALS["__vuln_mysqli"]->query($querySQL);
                        //Return user for response
                        return $statement->fetch_all(MYSQLI_ASSOC);
                    }
                ]
            ];
        }
    ]);
    //Get and json decode input
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    //Get query from input
    $query = $input ['query'];
    //Checks if the input variables are a set
    $variables = isset($input['variables']) ? $input['variables'] : null;
    //Define schema for GraphQL
    $schema = new Schema([
        'query' => $queryType
    ]);
    //Calls the graphQL PHP library execute query with the prepared variables
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    //Convert result to PHP array
    $output = $result->toArray();
} catch (\Exception $e) {
    //Creates the error message in case error happened in Syntax and Validation
    $output = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}
//Template response for client
$template = [
    'result' => "Query User ID in database successful" //Client only know this in response
];
//Encodes the response in a JSON object and echoes back to the client
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($template);

