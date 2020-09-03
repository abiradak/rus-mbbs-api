<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, X-Auth-Token, Origin, Application'"); 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    
    include_once '../config/database.php';
    include_once '../class/user.php';

    $database = new Database();
    $db = $database->getConnection();

    $last_otp = $_GET['otp'];
    $phone = $_GET['phone'];

    $items = new User($db);

    $stmt = $items->getSingleUser($phone);

    $itemCount = $stmt->rowCount();
    
    if($itemCount > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['last_otp'] == $last_otp) {
            $items->deleteOtp($phone);
            
            http_response_code(200);
            echo json_encode(
                array("message" => "Otp Verify Successfully Done.")
            );
        } else {
            http_response_code(401);
            echo json_encode(
                array("message" => "Invalid OTP.")
            );
        }
    }

?>