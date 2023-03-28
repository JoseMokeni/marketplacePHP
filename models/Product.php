<?php

namespace app\models;

use \PDO;
use app\Database;

class Product
{
    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?float $price;
    private ?string $status;
    private ?int $category_id;
    private ?string $image;

    public function __construct(
        int $id=0,
        string $name = "",
        string $description = "",
        float $price = 0,
        string $status = "",
        int $category_id = 0,
        string $image = ""
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->status = $status;
        $this->category_id = $category_id;
        $this->image = $image;
    }

    public static function getAll() : array
    {
//        check if a keyword is set
        $keyword = $_GET['keyword'] ?? "";
        $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%'";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOneById($id) : array
    {
        $sql = "SELECT * FROM products WHERE id = $id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllByCategoryId($id) : array
    {
        $sql = "SELECT * FROM products WHERE category_id = $id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create() : bool
    {
        $sql = "INSERT INTO products (name, description, price, status, category_id, image) VALUES (?, ?, ?, ?, ?, ?)";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute([
            $this->name,
            $this->description,
            $this->price,
            $this->status,
            $this->category_id,
            $this->image
        ]);
        return $statement->rowCount() > 0;
    }

    public function update() : bool
    {
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, status = ?, category_id = ?, image = ? WHERE id = ?";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute([
            $this->name,
            $this->description,
            $this->price,
            $this->status,
            $this->category_id,
            $this->image,
            $this->id
        ]);
        return $statement->rowCount() > 0;
    }

    public function delete() : bool
    {
        $sql = "DELETE FROM products WHERE id = ?";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute([
            $this->id
        ]);
        return $statement->rowCount() > 0;
    }

    public function setProductDataFromPost()
    {
        $condition = isset($_POST['id']) && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['status']) && isset($_POST['category_id']);
        if (!$condition){
            return false;
        } else {
            $this->id = $_POST['id'];
            $this->name = $_POST['name'];
            $this->description = $_POST['description'];
            $this->price = $_POST['price'];
            $this->status = $_POST['status'];
            $this->category_id = $_POST['category_id'];
            $this->image = $_POST['image'] ?? "";
            return true;
        }
    }

    public function setIdFromPost()
    {
        $condition = isset($_POST['id']);
        if (!$condition){
            return false;
        } else {
            $this->id = $_POST['id'];
            return true;
        }
    }

}