<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

class Otp {
    public function send_sms($mobile,$message) {
        $username = "hemantgupta";
        $password = "u38fCUDGBZ6DsdM";

        $test = "0";

        // Data for text message. This is the text message data.
        $sender = "ALTSTU"; // This is who the message appears to be from.
        $message = $message;
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "user=".$username."&password=".$password."&message=".$message."&sender=".$sender."&mobile=".$mobile."&type=3";
        $ch = curl_init('login.bulksmsgateway.in/sendmessage.php?'.$data);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);    
        return true;
    }
}
