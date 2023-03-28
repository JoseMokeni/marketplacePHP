<?php

namespace app\models;
use app\Database;
use PDO;

class Admin
{
    private ?int $id;
    private ?string $login;
    private ?string $password;

    public function __construct(int $id=0, string $login = "", string $password = "")
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    public function update() : bool
    {
        $sql = "UPDATE admins SET password = :password WHERE id = :id";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->bindValue(':id', $this->id);
        $statement->bindValue(':password', $this->password);
        return $statement->execute();
    }

    public function setPasswordFromPost() : void
    {
        $this->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    public static function authenticate(string $login, string $password) : bool
    {
        $sql = "SELECT * FROM admins WHERE login = :login";
        Database::connect();
        $statement = Database::$pdo->prepare($sql);
        $statement->bindValue(':login', $login);
        $statement->execute();
        $admin = $statement->fetch(PDO::FETCH_ASSOC);
        // print the admin array to console
        echo "<script>console.log('admin: " . json_encode($admin) . "')</script>";
        if ($admin) {
            //print the verification result to console
            return $password === $admin['password'];
//            TODO: uncomment the line below and comment the line above, restore the password_verify() function
            //return password_verify($password, $admin['password']);
        } else {
            return false;
        }
    }
}