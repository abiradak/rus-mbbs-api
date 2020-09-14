<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, X-Auth-Token, Origin, Application'"); 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../class/collage.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Collages($db);

    $collage_id = $_GET['id'] ?? '';

    $col_id = (int)$collage_id;

    if(empty($collage_id) || $collage_id = ''){
        http_response_code(400);
        echo json_encode(
            array(
                "status" => true,
                "message" => "Cannot Access this Api!"
            )
        );
    } else {
        $stmt = $items->getcollageDetails($col_id);
        $itemCount = $stmt->rowCount();
        
        if($itemCount > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            http_response_code(200);
            echo json_encode(
                array(
                    "status" => true,
                    "data" => $row,
                )
            );
        }
    }

?>