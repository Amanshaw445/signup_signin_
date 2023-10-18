<?php
require_once "config.php";

// Initialize variables
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username cannot be blank";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Something went wrong... cannot execute the query!";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must be at least 8 characters long";
    } else {
        $password = trim($_POST["password"]);
    }

    // Confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords do not match";
        }
    }

    // If there are no errors, insert the user into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the login page after successful registration
                header("location: login.php");
                exit;
            } else {
                echo "Something went wrong... cannot execute the query!";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
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
            <!-- <li><a href="register.php">register</a></li> -->
            <li><a href="login.php">login</a></li>
        </ul>
    </nav>
    <div class="login-container">
        <h2>Register here</h2>
        <form action="" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <span class="error"><?php echo $username_err; ?></span>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <span class="error"><?php echo $password_err; ?></span>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="error"><?php echo $confirm_password_err; ?></span>

            <button type="submit" class="login-button">Sign up</button>
            <li class="already">
                <p>Have an account already?</p>
                <a href="login.php">Log in</a>
            </li>
        </form>
    </div>
</body>
</html>
