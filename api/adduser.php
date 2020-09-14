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

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once '../config/database.php';
        include_once '../class/user.php';
        include_once '../class/verify.php';

        $database = new Database();
        $db = $database->getConnection();

        $items = new User($db);

        $stmt = $items->getAllUser();
        $itemCount = $stmt->rowCount();

        $verify = new Verify($db);



        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $stmt2 = $items->getSingleUser($data->phone);
        $itemCount2 = $stmt2->rowCount();


        

        // if(empty($data->name) || empty($data->phone) || empty($data->last_otp) || $data->name = '' || $data->phone = '' || $data->last_otp = '') {
        //    http_response_code(400);
        //     echo json_encode(
        //         array("message" => "Fill The Mandetory Fields.")
        //     );
        // } else {

        // }

        // else if (!empty($data->name) && !preg_match("/^[a-zA-Z ]*$/",$data->name)) {
        //   http_response_code(422);
        //     echo json_encode(
        //         array("message" => "Only letters and white space allowed.")
        //     );
        //     exit();
        // }

        // else if (!empty($data->email) && !preg_match("/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i",$data->email)) {
        //   http_response_code(422);
        //     echo json_encode(
        //         array("message" => "Invalid email format.")
        //     ); 
        // }

        // else if (!empty($data->phone) && !preg_match("/^[6-9][0-9]{9}$/",$data->phone)) {
        //   http_response_code(422);
        //     echo json_encode(
        //         array("message" => "Only 10 digit numbers allowed.")
        //     );
        //    exit();  
        // }

        // else if (!empty($data->last_otp) && !preg_match("/^[0-9]{4}$/",$data->last_otp)) {
        //   http_response_code(422);
        //     echo json_encode(
        //         array("message" => "Only Valid Otp")
        //     );
        //    exit();  
        // }


        if($itemCount > 0){
            if ($itemCount2 > 0) {

                $add = $items->updateUser($data->name , $data->email , $data->phone , $data->last_otp);
                $verify_count = $verify->addVerifyDetails($data->phone , $data->collage_id);

                http_response_code(200);
                echo json_encode(
                    array("status" => true)
                );
            } else {
                $add = $items->addUser($data->name , $data->email , $data->phone , $data->last_otp);
                $verify_count = $verify->addVerifyDetails($data->phone , $data->collage_id);
                http_response_code(200);
                echo json_encode(
                    array("status" => true)
                );
            }
            
        } else {
            $add = $items->addUser($data->name , $data->email , $data->phone , $data->last_otp);
            $verify_count = $verify->addVerifyDetails($data->phone , $data->collage_id);
            http_response_code(200);
            echo json_encode(
                array("status" => true)
            );
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array(
                "status" => false,
                "message" => "Cannot Access this Api!"
            )
        );
    }
?>