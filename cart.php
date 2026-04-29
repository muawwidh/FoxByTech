<?php 
   session_start();

   require_once "config/db.php";
   if(!isset($_SESSION['valid']) || !isset($_SESSION['id'])){
    header("Location: user.php");
    exit();
   }

   if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_action'])) {
    if ($_POST['cart_action'] === 'update' && isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            $product_id = (int) $product_id;
            $quantity = (int) $quantity;

            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    if ($_POST['cart_action'] === 'remove' && isset($_POST['product_id'])) {
        $product_id = (int) $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    }

    if ($_POST['cart_action'] === 'clear') {
        $_SESSION['cart'] = [];
    }

    header("Location: cart.php");
    exit();
   }

   $cart_items = [];
   $cart_total = 0;

   foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_id = (int) $product_id;
    $quantity = (int) $quantity;

    $stmt = $con->prepare("SELECT id, name, price, image, category FROM products WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $product = $result->fetch_assoc();
        $product['quantity'] = $quantity;
        $product['line_total'] = (float) $product['price'] * $quantity;
        $cart_total += $product['line_total'];
        $cart_items[] = $product;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
   }
?>


<!DOCTYPE html>
<html lang ="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/33ddd39d23.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php 
    $id = $_SESSION['id'];

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
                <li><a href="electronics.php">Electronics</a></li>
                <li><a class="active" href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
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
        <h2>#cart</h2>
        <p>Your selected products</p>
    </section>

    <section id="cart" class="section-p1">
        <?php if (isset($_GET['added'])) { ?>
            <div class="message"><h5>Product added to cart.</h5></div><br>
        <?php } ?>

        <?php if (count($cart_items) > 0) { ?>
            <form method="post" action="cart.php">
                <input type="hidden" name="cart_action" value="update">
                <table width="100%">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"></td>
                                <td>AED <?php echo number_format((float) $item['price'], 2); ?></td>
                                <td><input type="number" name="quantity[<?php echo (int) $item['id']; ?>]" value="<?php echo (int) $item['quantity']; ?>" min="0"></td>
                                <td>AED <?php echo number_format((float) $item['line_total'], 2); ?></td>
                                <td>
                                    <button class="normal" type="submit" form="remove-<?php echo (int) $item['id']; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div id="subtotal">
                    <h3>Cart Total: AED <?php echo number_format($cart_total, 2); ?></h3>
                    <button class="normal" type="submit">Update Cart</button>
                    <button class="normal" type="submit" form="clear-cart">Clear Cart</button>
                </div>
            </form>

            <?php foreach ($cart_items as $item) { ?>
                <form method="post" action="cart.php" id="remove-<?php echo (int) $item['id']; ?>">
                    <input type="hidden" name="cart_action" value="remove">
                    <input type="hidden" name="product_id" value="<?php echo (int) $item['id']; ?>">
                </form>
            <?php } ?>
            <form method="post" action="cart.php" id="clear-cart">
                <input type="hidden" name="cart_action" value="clear">
            </form>
        <?php } else { ?>
            <h2>Your cart is empty.</h2>
            <a href="home.php"><button class="normal">Continue Shopping</button></a>
        <?php } ?>
    </section>

    <section id="cart" class="section-p1">
        <div id="subtotal">
            <form method="post" id="search-form">
                <div id="search-container">
                    <input type="text" placeholder="Search Products" name="search" id="search-input">
                    <button class="normal" name="submit" id="search-btn">Search</button>
                </div>
                <button class="normal" type="button" id="get-location-btn">Get Current Location</button>
            </form>
        </div>

        <div class="section-p1">
            <table width="100%">
                <?php 
                if(isset($_POST['submit'])){
                    $search = $_POST['search'];

                    $sql = "SELECT * FROM products WHERE id LIKE ? or name LIKE ?";
                    $stmt = mysqli_prepare($con, $sql);
                    $search_term = "%" . $search . "%";
                    mysqli_stmt_bind_param($stmt, "is", $search, $search_term);
                    mysqli_stmt_execute($stmt);
                    $result1 = mysqli_stmt_get_result($stmt);
                    
                    if($result1){
                        if(mysqli_num_rows($result1)>0){
                            echo '<thead>
                            <tr>
                            <th>Id-No</th>
                            <th>Product</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>Category</th>
                            </tr>
                            </thead>
                            ';
                            while($row=mysqli_fetch_assoc($result1)){
                                echo'<tbody>
                                <tr>
                                <td>'.$row['id'].'</td>
                                <td>'.htmlspecialchars($row['name']).'</td>
                                <td><img src="' . htmlspecialchars($row['image']) . '"></td>
                                <td>'.$row['price'].'</td>
                                <td>'.htmlspecialchars($row['category']).'</td>
                                </tr>
                                </tbody>';
                            }
                        }else{
                            echo '<h2>Data not Found</h2>';
                        }
                    } else {
                        echo "Query failed: " . mysqli_error($con);
                    }
                }
                ?>
                <div class="location-container">
                    <div id="address-fields" style="display: none;">
                        <label for="country">Country:</label>
                        <input type="text" id="country" name="country" placeholder="Country">
                
                        <label for="state">State:</label>
                        <input type="text" id="state" name="state" placeholder="State">
                        
                        <label for="county">County:</label>
                        <input type="text" id="county" name="county" placeholder="County">
                        
                        <label for="street">Street:</label>
                        <input type="text" id="street" name="street" placeholder="Street">
                        
                        <label for="zip">Zip Code:</label>
                        <input type="text" id="zip" name="zip" placeholder="Zip Code">
                    </div>
                </div>
            </table>
        </div>
    </section>

    <script>
$(document).ready(function() {
    $(document).on('click', '#get-location-btn', function(event) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        $('#address-fields').show();
                        $('input[name=country]').val(data.address.country);
                        $('input[name=state]').val(data.address.state);
                        $('input[name=zip]').val(data.address.postcode);
                        $('input[name=county]').val(data.address.county);
                        $('input[name=street]').val(data.address.road);
                    })
                    .catch(error => console.log(error));
            });
        } else {
            console.log('Geolocation is not supported by this browser.');
        }
    });
});
    </script>
    <script src="script.js"></script>
</body>
</html>
