<?php

class DB {

    public static function connect() {

        try {
            return new PDO(
                "mysql:host=localhost;dbname=survey_system;charset=utf8",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (Exception $e) {
            die("DB Connection Failed: " . $e->getMessage());
        }
    }
}