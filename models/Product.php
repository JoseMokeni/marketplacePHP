<?php

namespace app\models;

use \PDO;
use app\Database;

class Product
{
    public ?int $id;
    public ?string $name;
    public ?string $description;
    public ?float $price;
    public ?string $status;
    public ?int $category_id;
    public ?int $user_id;
    public ?string $image;


    public function __construct(
        int $id=0,
        string $name = "",
        string $description = "",
        float $price = 0,
        string $status = "",
        int $category_id = 0,
        int $user_id = 0,
        string $image = ""
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->status = $status;
        $this->category_id = $category_id;
        $this->user_id = $user_id;
        $this->image = $image;
    }

    public static function getAll() : array
    {
        // todo: implement show more
//        check if a keyword is set
        $keyword = $_GET['keyword'] ?? "";
        $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%' ORDER BY dateCreated DESC LIMIT 10";
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

    public static function getAllByStatus($status) : array
    {
        $sql = "SELECT * FROM products WHERE status = '$status'";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function checkProductOwner($userId, $productId) : bool
    {
//        todo: add to database product owner
        $sql = "SELECT * FROM products WHERE id = $productId AND user_id = $userId";
        //var_dump($sql);
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public static function getAllCategoriesIdAndNames() : array
    {
        $sql = "SELECT id, name FROM categories";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllByUserId($userId) : array
    {
        $sql = "SELECT * FROM products WHERE user_id = $userId";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCategoryName($category_id)
    {
        $sql = "SELECT name FROM categories WHERE id = $category_id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC)['name'];
    }

    public function create() : bool
    {

        $sql = "INSERT INTO products (name, description, price, status, category_id, imagePath, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute([
            $this->name,
            $this->description,
            $this->price,
            $this->status,
            $this->category_id,
            $this->image,
            $this->user_id
        ]);
        return $statement->rowCount() > 0;
    }

    public function update() : bool
    {
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, status = ?, imagePath = ? WHERE id = ?";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute([
            $this->name,
            $this->description,
            $this->price,
            $this->status,
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


}