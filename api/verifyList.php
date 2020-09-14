<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, X-Auth-Token, Origin, Application'"); 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header("Content-Type: application/json; charset=UTF-8");


    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS , PUT, PATCH");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    
    include_once '../config/database.php';
    include_once '../class/verify.php';

    // $token = null;
    $headers = getallheaders();
    foreach ($headers as $key => $value) {
        if($key == 'Authorization') {
           $token = $value; 
        }
    }

    if (!empty($token) && $token != '') {
        if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            if($matches[1] === '07dff5fdceb078b06c97a89a3f9a2bf5') {
                $res = getList();
            } else {
                http_response_code(401);
                echo json_encode(
                    array("message" => "Unauthorized.")
                );
            }
        }
    } else {
        http_response_code(403);
        echo json_encode(
            array("message" => "No Token Provided.")
        );
    }



    function getList() {
        $database = new Database();
        $db = $database->getConnection();

        $items = new Verify($db);

        $phone_num = $_GET['phone'] ?? '';

        $phone = (int)$phone_num;

        if(empty($phone) || $phone = ''){
            http_response_code(400);
            echo json_encode(
                array(
                    "status" => true,
                    "message" => "User Have To Verify!"
                )
            );
        } else {
            $stmt = $items->getVerifyList($phone_num);

            $itemCount = $stmt->rowCount();

            if($itemCount > 0){
                
                $verifyList = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $e = array(
                        "id" => $id,
                        "phone" => $phone,
                        "collage_id" => $collage_id,
                    );
                    array_push($verifyList, $e);

                }
                
                echo json_encode($verifyList);
            }

            else{
                http_response_code(404);
                echo json_encode(
                    array("message" => "No record found.")
                );
            }
        }
    }
?>