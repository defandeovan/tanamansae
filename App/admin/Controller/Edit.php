<?php
include("db.php");

class ProductController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllProducts()
    {
        $sql = "SELECT id, name FROM products";
        return $this->conn->query($sql);
    }

    public function getProductById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProduct($id, $name, $price, $description)
    {
        $stmt = $this->conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $name, $price, $description, $id);
        if ($stmt->execute()) {
            
            return "Produk berhasil diperbarui!";
            
        } else {
            error_log("Update Error: " . $stmt->error); // Log error
            return false;
        }
    }

}
