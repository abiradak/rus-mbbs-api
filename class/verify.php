<?php
    class Verify {

        // Connection
        private $conn;

        // Table
        private $db_table = "user_verified_collage";

        // Columns
        public $id;
        public $phone;
        public $collage_id;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // ADD Verify Collage Count
        public function addVerifyDetails($phone , $collage_id) {
            $sqlQuery = "INSERT INTO " . $this->db_table . " (phone, collage_id)
            VALUES ('$phone' , '$collage_id')";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt; 
        }

        // GET VERIFY LIST BY PHONE NO
        public function getVerifyList($phone) {
            $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE phone = $phone";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
    }
?>