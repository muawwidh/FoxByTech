<?php 
   session_start();

   $con = mysqli_connect("localhost", "root", "", "muawwidh") or die("Couldn't connect");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }

   if (isset($_POST['delete'])) {
    $id = $_SESSION['id'];
    $stmt = $con->prepare("DELETE FROM users WHERE Id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: logout.php");
        exit();
    } else {
        echo "Error deleting user.";
    }

    $stmt->close();

}
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Change Profile</title>
    <script src="https://kit.fontawesome.com/33ddd39d23.js" crossorigin="anonymous"></script>
</head>
<body>
                <?php 
                
                $id = $_SESSION['id'];
                $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");

                while($result = mysqli_fetch_assoc($query)){
                    $res_Uname = $result['Username'];
                    $res_Email = $result['Email'];
                    $res_id = $result['Id'];
                }
                
                ?>

<section id="header">
        <a href="index.php"> <img src="img/logo2.jpeg" alt="FoxbyTech Logo"></a>
        
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="home.php">Home & Living</a></li>
                <li><a href="lights.php">Smart Lighting</a></li>
                <li><a href="electronics.php">Electronics</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
                <li><?php echo "<a class='active' href='edit.php?Id=$res_id'><i class='fa-solid fa-user-pen'></i></a>"; ?></li>
                <li><a href="logout.php"><i class="fas fa-sign-out"></i></a></li>
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
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $id = $_SESSION['id'];

    // Parametrized UPDATE query
    $stmt = $con->prepare("UPDATE users SET Username=?, Email=? WHERE Id=?");
    $stmt->bind_param("ssi", $username, $email, $id);
    $edit_query = $stmt->execute();

    if ($edit_query) {
        echo "<div class='message'>
                <h5>Profile Updated!</h5>
              </div> <br>";
        echo '<div class="btn-field"> 
                <a href="index.php"><button type="submit" value="Update" id="updateBtn">Go Home</button>
              </div>';
    }
} else {
    $id = $_SESSION['id'];
    // Parametrized SELECT query
    $stmt = $con->prepare("SELECT Username, Email FROM users WHERE Id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($res_Uname, $res_Email);

    // Fetching results
    while ($stmt->fetch()) {
        // Your code to handle fetched results
    }
}
?>


                <h1 id="title">Change Profile</h1>
                <form id="signupForm" method="post" action="">
                    <div class="input-group">
                        <div class="input-field" id="firstName">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="Username" value="<?php echo $res_Uname; ?>" name="username" id="username1" autocomplete="off" required>
                      
                    </div>
                    <div class="input-field" id="mailID">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Email" value="<?php echo $res_Email; ?>" name="email" id="email" autocomplete="off" required>
                    </div>  
                    <div class="btn-field"> 
                        <button type="submit" name="submit" value="Update" id="updateByn">Update</button>
                    </div>
                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <div class="btn-field">
                    <button type="submit" name="delete" id="deleteBtn">Delete Account</button>
                    </form>
                    </div>
                </form>
                 <!-- Delete Account button -->
                 
        </div>
        <?php ?>
      </div>
    </section>
</body>
</html>