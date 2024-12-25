<?php 
   session_start();
?>
<!DOCTYPE html>
<html lang ="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/> -->
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
    <!-- Signin Section HTML code -->
    <section id="signin">
        <div class="container">
            <div class="form-box-signin">

            <?php


// Connecting to the database
$con = mysqli_connect("localhost", "root", "", "muawwidh") or die("Couldn't connect");

if(isset($_POST['submit'])){
    // Validate and sanitize user inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if email is in valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='message'>
                <h5>Invalid email format</h5>
              </div>";
        echo "<a href='user.php'><button class='btn-custom'>Go Back</button>";
        exit;
    }

    // Prepare and execute the query using prepared statement
    $stmt = $con->prepare("SELECT * FROM users WHERE Email=? AND Password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // User authentication successful, set session variables
        $_SESSION['valid'] = $row['Email'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['id'] = $row['Id'];
        header("Location: index.php");
    } else {
        // Authentication failed, display error message
        echo "<div class='message'>
                <h5>Wrong Username or Password</h5>
              </div>";
        echo "<a href='user.php'><button class='btn-custom'>Go Back</button>";
        exit;
    }
}
?>

                <h1 id="title">Sign In</h1>
                <form id="signinForm" method="post" action="">
                    <div class="input-group">
                        <div class="input-field" id="usernameField">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" placeholder="Email" name="email" id="username" autocomplete="off" required>
                        </div>
                        <div class="input-field" id="passwordField">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" required>
                        </div>
                        <div class="btn-field"> 
                             <button type="submit" name="submit" id="signupBtn">Sign Up</button> 
                        </div>
                    </div>
                    <div class="links">
                        Don't have account? <a href="register.php">Sign Up Now</a>
                    </div>
                </form>
            </div>
            <?php ?>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
