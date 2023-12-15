<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to the login page if not logged in
    header("location: login.php");
    exit();
}

// Include the database class
include('db.php');
$conn = new Database();

// Get the user details based on the session ID
$userID = $_SESSION["id"];
$query = $conn->pdo->prepare('SELECT username FROM register WHERE id = :id');
$query->bindParam(':id', $userID);
$query->execute();

// Check if the query was successful
if ($query) {
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // The user is logged in, display the secure content
    $welcomeMessage = isset($user['username']) ? "Welcome, " . htmlspecialchars($user['username']) . "!" : "Welcome!";
} else {
    // Handle the case where the query fails
    $welcomeMessage = "Welcome!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 50vh;
            margin: 0;
        }
        h1 {
            color: green;
        }
    </style>
</head>
<body>
    <h1><?php echo $welcomeMessage; ?></h1>
    <p>This is a secure area. Only logged-in users can access this page.</p>
    
    <form action="logout.php" method="post">
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</body>
</html>
