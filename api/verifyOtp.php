<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, X-Auth-Token, Origin, Application'"); 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    
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
    include_once '../class/user.php';

    $headers = apache_request_headers();
    $token = $headers['Authorization'] ?? '';

    if (!empty($token) && $token != '') {
        if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            if($matches[1] === '07dff5fdceb078b06c97a89a3f9a2bf5') {
                $res = verifyOtp();
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


    function verifyOtp() {
        $database = new Database();
        $db = $database->getConnection();

        $last_otp = $_GET['otp'] ?? '';
        $phone = $_GET['phone'] ?? '';


        if($last_otp != '' ) {
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
        } else {
            http_response_code(204);
            echo json_encode(
                array("message" => "Otp Not Provided")
            );
        }
    }



    // $database = new Database();
    // $db = $database->getConnection();

    // $last_otp = $_GET['otp'] ?? '';
    // $phone = $_GET['phone'] ?? '';


    // if($last_otp != '' ) {
    //     $items = new User($db);

    //     $stmt = $items->getSingleUser($phone);

    //     $itemCount = $stmt->rowCount();
        
    //     if($itemCount > 0){
    //         $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if($row['last_otp'] == $last_otp) {
    //             $items->deleteOtp($phone);
                
    //             http_response_code(200);
    //             echo json_encode(
    //                 array("message" => "Otp Verify Successfully Done.")
    //             );
    //         } else {
    //             http_response_code(401);
    //             echo json_encode(
    //                 array("message" => "Invalid OTP.")
    //             );
    //         }
    //     }
    // } else {
    //     http_response_code(204);
    //     echo json_encode(
    //         array("message" => "Otp Not Provided")
    //     );
    // }

?>