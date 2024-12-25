<?php 
   session_start();

   $con = mysqli_connect("localhost", "root", "", "muawwidh") or die("Couldn't connect");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>


<!DOCTYPE html>
<html lang ="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
    <title>Electronics</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
        <h2>#stayhome</h2>
        <p>Order Online</p>
    </section>

    <section id="cart" class="section-p1">
    <div id="subtotal">
    <form method="post" id="search-form">
        <div id="search-container">
            <input type="text" placeholder="Search Products" name="search" id="search-input">
            <button class="normal" name="submit" id="search-btn">Search</button>
        </div>
        <button class="normal" id="get-location-btn">Get Current Location</button>
    </form>
</div>

    <div class="section-p1">
    <table width="100%">
            <?php 
            if(isset($_POST['submit'])){
    $search = $_POST['search'];

    $sql = "SELECT * FROM products WHERE id LIKE ? or name LIKE ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($con, $sql);
    $search_term = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "is", $search, $search_term);
    mysqli_stmt_execute($stmt);
    $result1 = mysqli_stmt_get_result($stmt);
    
    // Check if the query was successful
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
       while( $row=mysqli_fetch_assoc($result1)){
        echo'<tbody>
        <tr>
        <td>'.$row['id'].'</td>
        <td>'.$row['name'].'</td>
        <td><img src="' . $row['image'] . '"></td>
        <td>'.$row['price'].'</td>
        <td>'.$row['category'].'</td>
        </tr>
        </tbody>';
       }
    }else{
        echo '<h2>Data not Found</h2>';
    }
    } else {
        // Handle the case where the query fails
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
        </div>
        </table>
    </div>
    </section>
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
                        // Show the address fields container
                        $('#address-fields').show();
                        
                        // Fill in the address fields with the retrieved location components
                        $('input[name=country]').val(data.address.country);
                        $('input[name=state]').val(data.address.state);
/*                         $('input[name=city]').val(data.address.city); */
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