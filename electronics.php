<?php 
   session_start();

   require_once "config/db.php";
   if(!isset($_SESSION['valid']) || !isset($_SESSION['id'])){
    header("Location: user.php");
    exit();
   }
   $id = $_SESSION['id'];

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_action']) && $_POST['cart_action'] === 'add') {
    $product_id = (int) $_POST['product_id'];
    $quantity = max(1, (int) $_POST['quantity']);

    $stmt = $con->prepare("SELECT id FROM products WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result && $product_result->num_rows === 1) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 0;
        }

        $_SESSION['cart'][$product_id] += $quantity;
    }

    header("Location: cart.php?added=1");
    exit();
   }
   $products_query = mysqli_query($con, "SELECT * FROM products WHERE category = 'electronics'");
?>

<!DOCTYPE html>
<html lang ="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
    <title>Electronics</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="shop.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/> -->
    <script src="https://kit.fontawesome.com/33ddd39d23.js" crossorigin="anonymous"></script>
</head>

<body>
            <?php 
            
                $stmt = $con->prepare("SELECT * FROM users WHERE Id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $query = $stmt->get_result();

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
                <li><a class="active" href="electronics.php">Electronics</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
                <li><?php echo "<a href='edit.php?Id=$res_id'><i class='fa-solid fa-user-pen'></i></a>"; ?></li>
                <li><a href="logout.php"><i class="fas fa-sign-out"></i></a></li>
                <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <section id="page-header">
        <h2>#gamersetup</h2>
        <p>Order Online!</p>
  
    </section>

    <?php 
        // Check if there are products
        if(mysqli_num_rows($products_query) > 0) {
            // Loop through each product
            while($product = mysqli_fetch_assoc($products_query)) {
    ?>
            <section id="prodetails" class="section-p1">
                <div class="single-pro-image">
                    <img src="<?php echo $product['image']; ?>" width="100%" id="MainImg<?php echo $product['id']; ?>" alt="Main Product Image">

                    <div class="small-img-group">
                        <?php
                        // Display additional images
                        $additional_images = explode(",", $product['additional_images']);
                        foreach ($additional_images as $image) {
                            echo "<div class='small-img-col'>";
                            echo "<img src='$image' width='100%' class='small-img' alt='Additional Product Image'>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="single-pro-details">
                    <h2><?php echo $product['name']; ?></h2>
                    <h4>AED <?php echo $product['price']; ?></h4>
                    <form method="post" action="">
                        <input type="hidden" name="cart_action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" class="normal">Add to Cart</button>
                    </form>
                    <h4>Product Details</h4>
                    <span><?php echo $product['description']; ?></span>
                </div>
            </section>
    <?php
            }
        }
    ?>
 


    </div>
</section>


   <script>
   var productImages = document.querySelectorAll('.single-pro-image img');

productImages.forEach(function(image) {
    var smallImages = image.parentElement.querySelectorAll('.small-img');

    smallImages.forEach(function(smallImage) {
        smallImage.addEventListener('click', function() {
            image.src = smallImage.src;
        });
    });
});

</script>

    <script src="script.js"></script>
</body>
</html>