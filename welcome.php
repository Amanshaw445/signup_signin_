<?php
session_start();

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body style="background-image: linear-gradient(to right, rgb(147,49,186),rgb(207,50,149),rgb(237,68,117) ,rgb(244,137,85));margin:0px;">
    <nav class="navbar">
        <ul>
        <li><a href="welcome.php"><i class="fa-regular fa-user" style="color: #ffffff;">&nbsp</i><?php echo htmlspecialchars($username); ?></a></li>
        <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="welcome_container"> 
        
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>This is your personalized welcome page.</p>
        
    </div>
    
</body>
</html>
