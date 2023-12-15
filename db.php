<?php

class Database
{
    public $pdo;

    public function __construct($db = "root", $user = "root", $pwd = "", $host = "localhost:3306")
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo ("Connection failed: " . $e->getMessage());
        }
    }

    public function addUser(string $email, string $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO register (email, password) VALUES (:email, :pass)';
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $hashedPassword);

        $stmt->execute();
    }

    public function login($username, $password)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $query = $this->pdo->prepare('SELECT * FROM register WHERE email = :email');
        $query->bindParam(':email', $username);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            
            if (password_verify($password, $row['password'])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row['id'];
            } else {
                die("Wrong email or password");
            }
        } else {
            die("User not found");
        }
    }
}
?>
