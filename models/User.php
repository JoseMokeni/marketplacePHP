<?php

namespace app\models;
use app\Database;
use PDO;

class User
{
    private ?int $id;
    private ?string $name;
    private ?string $email;
    private ?string $password;
    private ?bool $active;
//    date of birth
    private ?string $dob;
    private ?string $phone;
    public function __construct(
        int $id=0,
        string $name = "",
        string $email = "",
        string $password = "",
        bool $active = true,
        string $dob = "",
        string $phone = ""
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->active = $active;
        $this->dob = $dob;
        $this->phone = $phone;
    }

    public static function getAll() : array
    {
        $sql = "SELECT * FROM users";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOneById($id) : array
    {
        $sql = "SELECT * FROM users WHERE id = $id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOneByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create() : bool
    {
//        encrypt password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, dob, phone) VALUES ('$this->name', '$this->email', '$this->password', '$this->dob', '$this->phone')";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function update() : bool
    {
        if ($this->password != null){
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = '$this->name', password = '$this->password' WHERE id = $this->id";
        } else {
            $sql = "UPDATE users SET name = '$this->name' WHERE id = $this->id";
        }
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function delete() : bool
    {
        $sql = "DELETE FROM users WHERE id = $this->id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function deactivate() : bool
    {
        $sql = "UPDATE users SET active = 0 WHERE id = $this->id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function activate() : bool
    {
        $sql = "UPDATE users SET active = 1 WHERE id = $this->id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    public function setUserDataFromPost() : bool
    {
        $condition = isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dob']) && isset($_POST['phone']);
        if ($condition) {
            $this->name = $_POST['name'];
            $this->email = $_POST['email'];
            $this->password = $_POST['password'];
            $this->dob = $_POST['dob'];
            $this->phone = $_POST['phone'];
            return true;
        } else {
            return false;
        }
    }

    public function setUserIdFromPost()
    {
        $condition = isset($_POST['id']);
        if ($condition) {
            $this->id = $_POST['id'];
            return true;
        } else {
            return false;
        }
    }

    public static function getActiveUsers() : array
    {
        $sql = "SELECT * FROM users WHERE active = 1";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getInactiveUsers() : array
    {
        $sql = "SELECT * FROM users WHERE active = 0";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function authenticate($email, $password) : bool
    {
        $user = self::getOneByEmail($email);
        if ($user == false) {
            return false;

        } else {
            return $password === $user['password'];
//            TODO: uncomment the line below and comment the line above, restore the password_verify() function
            //return password_verify($password, $user['password']);
        }
    }

}