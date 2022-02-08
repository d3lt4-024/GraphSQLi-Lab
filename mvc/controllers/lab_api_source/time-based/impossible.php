<?php
require_once GRAPHSQLI_LAB_ROOT . 'vendor/autoload.php';
require_once 'DatabaseConnect.php';

use App\DatabaseConnect;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

error_reporting(0);
try {
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
                    'args' => [
                        'id' => Type::id()
                    ],
                    'resolve' => function ($root, $args) {
                        $id = $args['id'];
                        $querySQL = "SELECT UserId, FirstName, LastName FROM users WHERE UserId  = :id LIMIT 1";
                        //Prepare query with security PDO
                        $statement = $GLOBALS["__sec_pdo"]->prepare($querySQL);
                        //Bind id value to query
                        $statement->bindValue(':id', $id, PDO::PARAM_INT);
                        //Execute query
                        $statement->execute();
                        //Get and return user from query db
                        return $statement->fetchAll();
                    }
                    //Using prepared statements is the most effective way to prevent SQLi
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

