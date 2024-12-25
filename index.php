<?php
session_start();

$con = mysqli_connect("localhost", "root", "", "muawwidh") or die("Couldn't connect");

// Check if session variable 'valid' is set and true
if (isset($_SESSION['valid'])) {
    // Code block to fetch user information
    $id = $_SESSION['id'];
    $stmt = $con->prepare("SELECT Username, Email, Id FROM users WHERE Id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($res_Uname, $res_Email, $res_id);

    // Fetching results
    while ($stmt->fetch()) {

    }

    if (isset($res_id)) {
        $editLink = "<li><a href='edit.php?Id=$res_id'><i class='fa-solid fa-user-pen'></i></a></li>";
        $logoutLink = "<li><a href='logout.php'><i class='fas fa-sign-out'></i></a></li>";
        $welcomeUser = "<h1>Welcome <b>$res_Uname</b></h1>";
    } else {
        // Handle the case when values are not fetched
        $editLink = "";
        $logoutLink = "";
        $welcomeUser = "";
    }
    $loginLink = "";
} else {
    $editLink = "";
    $logoutLink = "";
    $welcomeUser = "";
    $loginLink = '<li><a href="user.php"><i class="fa-solid fa-circle-user"></i></a></li>';
}

?>
<!DOCTYPE html>
<html lang ="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Value Deals on E-commerce Products | FoxbyTech</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/33ddd39d23.js" crossorigin="anonymous"></script>

</head>

<body>
        <section id="header">
        <a href="index.php"> <img src="img/logo2.jpeg" alt="FoxbyTech Logo"></a>
        
        <div>
            <ul id="navbar">
                <li><a class="active" href="index.php">Home</a></li>
                <li><a href="home.php">Home & Living</a></li>
                <li><a href="lights.php">Smart Lighting</a></li>
                <li><a href="electronics.php">Electronics</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-bag-shopping"></i></a></li>
                <?php echo $editLink; ?>
                <?php echo $loginLink; ?>
                <!-- <li><a href="user.php"><i class="fa-solid fa-circle-user"></i></a></li> -->
                <a href="#" id="close"><i class="fa-solid fa-x"></i></a>
                <?php echo $logoutLink; ?>
            </ul>
        </div>
        <div id="mobile">
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <section id="hero">
       <!--  <h4>Trade-in-offer</h4> -->
       <?php echo $welcomeUser; ?>
        <h2>Super value deals</h2>
        <h1>On all products</h1>
        <p>Save more with coupons & up to 70%off</p>
        <button id="shopNoww">Shop Now</button>
    </section>

    <section id="feature" class="section-p1">
        <div class="fe-box">
            <img src = "img/feature.png" alt="Free Shipping">
            <h6>Free Shipping</h6>
        </div> 
        <div class="fe-box">
            <img src = "img/sample4.png" alt="Online Order">
            <h6>Online Order</h6>
        </div>
        <div class="fe-box">
            <img src = "img/sample2.png" alt="Promotions">
            <h6>Promotions</h6>
        </div>
        <div class="fe-box">
            <img src = "img/sample3.png" alt="Happy Sell">
            <h6>Happy Sell</h6>
        </div>
        <div class="fe-box">
            <img src = "img/sample1.jpg" alt="F24/7 Support">
            <h6>F24/7 Support</h6>
        </div>   
    </section>

    <section id="product1" class="section-p1">
        <h2>Featured Products</h2>
        <p>Summer Collection New Modern Design</p>
        <div class="pro-container" id="featured-products-container">
    </section>
    

    
    <section id="banner" class="section-m1">
        <h4><span>Live Smart</span></h4>
        <h2>Welcome to the future of shopping, where innovation meets imagination, and every interaction is an opportunity to redefine the art of commerce.</h2>
        <button class="normal" id="exploreBttn">Explore More</button>
    </section>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
    var shopNowButton = document.getElementById("shopNoww");
    var exploreButton = document.getElementById("exploreBttn");
    var featureSection = document.getElementById("product1");
    var topOfPage = document.body;

    // Scroll to the "Featured Products" section when "Shop Now" button is clicked
    shopNowButton.addEventListener("click", function() {
        featureSection.scrollIntoView({ behavior: 'smooth' });
            });

    // Scroll to the top of the page when "Explore More" button is clicked
    exploreButton.addEventListener("click", function() {
        topOfPage.scrollIntoView({ behavior: 'smooth' });
            });
    });
    
     fetch('https://gist.githubusercontent.com/muawwidh/4af2aa2cccfde507907426044e547d01/raw/4f2294ef2e63d219e34a3a048a27c62583e77442/datanav.json')
        .then(response => response.json())
        .then(data => {
            const productsContainer = document.getElementById('featured-products-container');
            data.forEach(product => {
                const productElement = document.createElement('div');
                productElement.classList.add('pro');
                productElement.innerHTML = `
                    <img src="${product.image}" alt="${product.name} - ${product.price}">
                    <div class="des">
                        <span>${product.brand}</span>
                        <h5>${product.name}</h5>
                        <h4>${product.price}</h4>
                    </div>
                    
                `;
                productsContainer.appendChild(productElement);
            });
        })
        .catch(error => console.error('Error fetching product data:', error)); 
    </script>
    

    <script src="script.js"></script>
</body>
</html>