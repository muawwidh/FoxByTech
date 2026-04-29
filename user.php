<?php
session_start();
require_once "config/db.php";

$error = "";

if (isset($_SESSION['valid']) && isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $con->prepare("SELECT Id, Username, Email, Password FROM users WHERE Email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['Password'])) {
                $_SESSION['valid'] = true;
                $_SESSION['username'] = $user['Username'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['id'] = (int) $user['Id'];

                header("Location: index.php");
                exit();
            }
        }

        $error = "Incorrect email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/33ddd39d23.js" crossorigin="anonymous"></script>
</head>
<body>
    <section id="header">
        <a href="index.php"> <img src="img/logo2.jpeg" alt="FoxbyTech Logo"></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="home.php">Home & Living</a></li>
                <li><a href="lights.php">Smart Lighting</a></li>
                <li><a href="electronics.php">Electronics</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
                <li><a class="active" href="user.php"><i class="fa-solid fa-circle-user"></i></a></li>
                <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>
    <section id="signup">
        <div class="container">
            <div class="form-box-signup">
                <h1 id="title">Sign In</h1>
                <?php if ($error !== "") { ?>
                    <div class="message"><h5><?php echo htmlspecialchars($error); ?></h5></div><br>
                <?php } ?>
                <form id="signupForm" method="post" action="">
                    <div class="input-group">
                        <div class="input-field" id="mailID">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" placeholder="Email" name="email" id="email" autocomplete="off" required>
                        </div>
                        <div class="input-field" id="firstPass">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" required>
                        </div>
                        <div class="btn-field">
                            <button type="submit" name="submit" id="signinBtn">Sign In</button>
                        </div>
                        <div class="links">
                            Not a member? <a href="register.php">Sign Up</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>
