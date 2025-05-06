<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if (isset($_POST['order'])) {

    // Input Validation and Sanitization
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_INT); // Validate as integer
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Validate as email
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    // Address construction with length checks
    $flat = filter_var($_POST['flat'], FILTER_SANITIZE_STRING);
    $street = filter_var($_POST['street'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
    $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    $pin_code = filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);

    // Length restrictions.
    $maxLength = 255; // Adjust as needed
    $name = substr($name, 0, $maxLength);
    $flat = substr($flat, 0, $maxLength);
    $street = substr($street, 0, $maxLength);
    $city = substr($city, 0, $maxLength);
    $state = substr($state, 0, $maxLength);
    $country = substr($country, 0, $maxLength);
    $pin_code = substr($pin_code, 0, 20); // pin codes vary in length, adjust as needed.

    // Combine address
    $address = 'flat no. ' . $flat . ', ' . $street . ', ' . $city . ', ' . $state . ', ' . $country . ' - ' . $pin_code;
    $address = substr($address, 0, 1024); // Limit total address length.

    // Validate total_products and total_price as numbers
    $total_products = filter_var($_POST['total_products'], FILTER_VALIDATE_INT);
    $total_price = filter_var($_POST['total_price'], FILTER_VALIDATE_FLOAT);

    if ($total_products === false || $total_price === false) {
        // Handle invalid input (e.g., log error, redirect)
        echo "Invalid total products or total price.";
        exit;
    }

    // Server-Side Cart Validation and Total Calculation
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);
    $cart_items = $check_cart->fetchAll(PDO::FETCH_ASSOC);

    $calculated_total_products = 0;
    $calculated_total_price = 0;
    // Compare calculated totals with client-provided totals
    if ($calculated_total_products !== $total_products || $calculated_total_price !== $total_price) {
        // Handle discrepancy (e.g., log error, redirect, display warning)
        echo "Cart totals do not match. Please review your cart.";
        exit;
    }

    // Database Transaction
    try {
        $conn->beginTransaction();

        // Insert order details (example)
        $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

        // Clear the cart (example)
        $clear_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $clear_cart->execute([$user_id]);

        $conn->commit();
        echo "Order placed successfully!";

    } catch (PDOException $e) {
        $conn->rollBack();
        // Log the error $e->getMessage()
        echo "Error placing order. Please try again.";
    }
}

 

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Your Orders</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">Grand Total : <span>Nrs.<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>place your orders</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Tapaiko subh nam :</span>
            <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Your Number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Your Email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>kasari halnuhunx paisa? :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash On Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="paytm">eSewa</option>
               <option value="paypal">Khalti</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. Flat number" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" placeholder="Street name" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="Kathmandu" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Province:</span>
            <input type="text" name="state" placeholder="Bagmati" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Country :</span>
            <input type="text" name="country" placeholder="Nepal" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>ZIP CODE :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 56400" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
