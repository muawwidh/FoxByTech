<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                <li><a class="active" href="register.php"><i class="fa-solid fa-circle-user"></i></a></li>
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
            <?php 

session_start();
            // Connecting to the database
            $con = mysqli_connect("localhost", "root", "", "muawwidh") or die("Couldn't connect");

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Input validation for email
    $password = $_POST['password'];

    // Verifying unique email
    $verify_query = mysqli_query($con,"SELECT Email FROM users WHERE Email='$email'");
    if(mysqli_num_rows($verify_query) != 0 ){
        echo "<div class='message'>
                <h5>This email is already in use. Please choose another one.</h5>
             </div> <br>";
        echo "<a href='javascript:self.history.back()'><button class='btn-custom'>Go Back</button>";
        exit;
    } else {
        // Prepared statement for inserting user data
        $stmt = $con->prepare("INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            echo "<div class='message'>
                     <h5>Registration successful!</h5>
                  </div> <br>";
            echo "<a href='user.php'><button class='btn-custom'>Login Now</button>";
            exit;
        } else {
            echo "<div class='message'>
            <h5>Error occurred during registration. Please try again later.</h5>
              </div><br>";
        echo "<a href='user.php'><button class='btn-custom'>Go Back</button>";
        exit;
        }
    }
}
?>
                <h1 id="title">Sign Up</h1>
                <form id="signupForm" method="post" action="">
                    <div class="input-group">
                        <div class="input-field" id="firstName">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" placeholder="Username" name="username" id="username1" autocomplete="off" required>
                        </div>
                        <div class="input-field" id="mailID">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" placeholder="Email" name="email" id="email" autocomplete="off" required>
                        </div>
                        <div class="input-field" id="firstPass">
                            <i class="fa-solid fa-user"></i>
                            <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" required>
                        </div>
                        <div class="btn-field"> 
                            <button type="submit" name="submit" id="signupBtn">Sign Up</button> 
                        </div>
                        <div class="links">
                            Already a member? <a href="user.php">Sign In</a>
                        </div>
                    </div>
                </form>
            </div>
            <?php ?>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>
