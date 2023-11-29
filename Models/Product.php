<?php

namespace app\Models;

require_once __DIR__ . '/../Config/DatabaseConfig.php';

use app\Config\DatabaseConfig;
use mysqli;

class Product extends DatabaseConfig
{

    public $conn;
    public function __construct()
    {

        // KONEKSI KE DATABASE MYSQL
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->database,
            $this->port
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    //PROSES MENAMPILKAN SEMUA DATA

    public function findAll()
    {

        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        $this->conn->close();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // PROSES MENAMPILAN DATA DENGAN ID

    public function findById($id)
    {

        $sql = "SELECT *FROM products WHERE id =?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->conn->close();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // PROSES INSERT DATA
    public function create($data)
    {
        $productName = $data['product_name'];
        $query = "INSERT INTO products (product_name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $productName);
        $stmt->execute();
        $this->conn->close();
    }


    //Proses Update Data dengan ID
    public function update($data, $id)
    {
        $productName = $data['product_name'];
        $query = "UPDATE products SET product_name =? WHERE id = ?";
        $stmt =  $this->conn->prepare($query);
        $stmt->bind_param("si", $productName, $id);

        // huruf "s" berarti tipe parameter product name adalah String dan huruf berarti parameter
        $stmt->execute();
        $this->conn->close();
    }



    // PROSES DELETE DATA DENGAN ID
    public function destroy($id)
    {
        $query = "DELETE FROM products WHERE id =?";
        $stmt = $this->conn->prepare($query);

        // huruf i berarti parameter pertama adalah integer

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $this->conn->close();
    }
}
