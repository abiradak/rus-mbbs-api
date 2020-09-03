<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, X-Auth-Token, Origin, Application'"); 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    
    include_once '../config/database.php';
    include_once '../class/user.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new User($db);

    $stmt = $items->getAllUser();
    $itemCount = $stmt->rowCount();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

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
        $userList = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $e = array(
                "phone" => $phone,
            );
            array_push($userList, $e);
        }
        foreach ($userList as $key => $value) {
            if($value['phone'] == $data->phone) {
                $add = $items->updateUser($data->name , $data->email , $data->phone , $data->last_otp);
                http_response_code(200);
                echo json_encode(
                    array("status" => true)
                );
            }
        }
    } else {
        $add = $items->addUser($data->name , $data->email , $data->phone , $data->last_otp);
        http_response_code(200);
        echo json_encode(
            array("status" => true)
        );
    }
?>