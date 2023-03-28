<?php

namespace app;

use PDO;

class Database
{
    public static PDO $pdo;

    public static function connect()
    {
//        check if pdo is already connected
        if (isset(self::$pdo)) {
            return;
        }
        self::$pdo = new PDO('mysql:host=localhost;dbname=marketplace_php', 'root', '');
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private static function createTables()
    {
//        todo: create tables
    }


}