<?php
    class User{

        // Connection
        private $conn;

        // Table
        private $db_table = "user_info";

        // Columns
        public $id;
        public $name;
        public $email;
        public $phone;
        // public $uniq_id;
        public $last_otp;
        public $is_verified;
        

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL USER
        public function getAllUser() {
            $sqlQuery = "SELECT id, name, email, phone , last_otp , is_verified FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // ADD USER
        public function addUser($name , $email = null , $phone , $last_otp) {
            $sqlQuery = "INSERT INTO " . $this->db_table . " (name, email, phone, last_otp)
            VALUES ('$name' , '$email' , '$phone' , '$last_otp')";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt; 
        }

        //UPDATE USER
        public function updateUser($name , $email = null , $phone , $last_otp) {
            $sqlQuery = "UPDATE " . $this->db_table . " SET name = '$name' , email = '$email' , last_otp = $last_otp, is_verified = '0' WHERE phone = $phone";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
            
        }

        //GET USER WITH PHONE NO
        public function getSingleUser($phone) {
            $sqlQuery = "SELECT last_otp FROM " . $this->db_table . " WHERE phone = $phone LIMIT 1";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        //DELETE OTP AFTER VERIFY
        public function deleteOtp($phone) {
            $sqlQuery = "UPDATE " . $this->db_table . " SET last_otp = '', is_verified = '1' WHERE phone = $phone";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
    }
?>