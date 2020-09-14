<?php
    header("Access-Control-Allow-Origin: *"); 
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With");
    // header("Content-Type: application/json; charset=UTF-8");

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS , PUT, PATCH");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    include_once '../class/otp.php';

    //$token = null;
    $headers = getallheaders();
    foreach ($headers as $key => $value) {
        if($key == 'Authorization') {
           $token = $value; 
        }
    }

    if (!empty($token) && $token != '') {
        if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            if($matches[1] === '07dff5fdceb078b06c97a89a3f9a2bf5') {
                $res = sendMessage();
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
    
    function sendMessage() {

        $otp = mt_rand(1000,9999);

        $phone = $_GET['phone'];

        $email = $_GET['email'] ?? '';

        if(empty($phone) || $phone == '') {
            http_response_code(400);
            echo json_encode(
                array("message" => "Phone Number is Needed")
            );
        } else {

            if (!empty($email) && $email != '' && $email != null) {
                

                $message = 'Use this OTP '. $otp . ' For Login';
                $classOtp = new Otp();

                $result = $classOtp->send_sms($phone , $message);

                $mail = mail($email, 'Verify OTP', $message);

                if($result == true) {
                    
                    echo json_encode([
                        "message" => "Otp Send Successfully",
                        "otp" => $otp,
                        "status" => true
                    ]);
                }

                else {
                    http_response_code(400);
                    echo json_encode(
                        array("message" => "Bad Request.")
                    );
                }
            } else {
    
                $message = 'Use this OTP '. $otp . ' For Login';
                $classOtp = new Otp();
                $result = $classOtp->send_sms($phone , $message);
                if($result == true) {
                    
                    echo json_encode([
                        "message" => "Otp Send Successfully",
                        "otp" => $otp,
                        "status" => true
                    ]);
                }

                else {
                    http_response_code(400);
                    echo json_encode(
                        array("message" => "Bad Request.")
                    );
                }
            }
        }
    }

?>