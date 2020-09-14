<?php

    class Collages{



        // Connection

        private $conn;



        // Table

        private $db_table = "collages";



        // Columns

        public $id;

        public $name;

        public $location;

        public $description;

        public $image;

        public $details;



        // Db connection

        public function __construct($db){

            $this->conn = $db;

        }



        // GET ALL

        public function getAllCollages(){

            $sqlQuery = "SELECT id, name, location, description , image FROM " . $this->db_table . "";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->execute();

            return $stmt;

        }



        // GET SINGLE USER

        public function getcollageDetails($id) {
            $sqlQuery = "SELECT id, name, location, description , image , details FROM " . $this->db_table . " WHERE id = $id";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->execute();

            return $stmt;

        }

    }

?>