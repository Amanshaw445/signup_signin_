<?php
session_start();

if (isset($_SESSION["username"])) {
    header("location: welcome.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['username']))) {
        $err = "Username cannot be blank";
    } elseif (empty(trim($_POST['password']))) {
        $err = "Please enter both username and password";
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

    if (empty($err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            $param_username = $username;
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;
                            header("location: welcome.php");
                            exit;
                        } else {
                            $err = "Invalid password";
                        }
                    }
                } else {
                    $err = "Username not found";
                }
            } else {
                $err = "Something went wrong... cannot execute the query!";
            }

            mysqli_stmt_close($stmt);
        } else {
            $err = "Something went wrong... cannot prepare the statement!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body style="background-image: linear-gradient(to right, rgb(147,49,186),rgb(207,50,149),rgb(237,68,117) ,rgb(244,137,85));margin:0px;">
    <nav class="navbar">
        <ul>
            <li><a href="register.php">Register</a></li>
            <!-- <li><a href="login.php"></a></li> -->
        </ul>
    </nav>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <span class="error"><?php echo $err; ?></span>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="login-button">Log in</button>
            <li class="already">
                <p>don't have an account?</p>
                <a href="register.php">Sign up</a>
            </li>
        </form>
    </div>
</body>
</html>