<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/regLog/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'database.php';
include_once 'query.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user->email = $data->email;
$email_exists = $user->emailExists();
if($email_exists && password_verify($data->password, $user->password)){



    http_response_code(200);

      echo json_encode(
              array(
                  "message" => "Successful login.",

              )
          );

  }
  else{

      http_response_code(401);

      echo json_encode(array("message" => "Login failed."));
  }
  ?>
