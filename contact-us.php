<?php include('include/header.php'); ?>

<div class="jumbotron">
  <h1 class="text-center mt-5">Contact us</h1>
</div>

<div class="col-md-8 container mt-3 mb-3">
  <div class="col-md-12 row border rounded mb-4 m-0 p-4">
    <div class="col-md-6">
      <h3>Our Office</h3>
      <hr>
      <p>Ahmedabad, India</p>
    </div>
    <div class="col-md-6">
      <form action="contact-us.php" method="POST" class=" p-3">
        <?php
            if (isset($messages)) {

              echo "<div class='alert bg-danger' role='alert'>
                                <span class='text-white text-center'> $messages</span>
                                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                  </button>
                              </div>";
            }

        ?>
        <div class="form-group">
          <input type="text" name="fullname" placeholder="Full Name" class="form-control hover-effect p-2 px-2" required>
        </div>

        <div class="form-group">
          <input type="text" name="email" placeholder="Email" class="form-control hover-effect p-2" required>
        </div>
        <div class="form-group">
          <input type="text" name="phone_number" placeholder="Phone Number" class="form-control hover-effect p-2" required pattern="[0-9]{5}[0-9]{5}">
        </div>
        <div class="form-group">
          <input type="text" name="address" placeholder="Address" class="form-control hover-effect p-2" required>
        </div>
        

        <div class="form-group">
          <textarea name="message" class="form-control hover-effect" rows="5" cols="20" placeholder="Message" required></textarea>
        </div>

        <div class="form-group text-center mt-4">
          <input type="submit" name="message_submit" class="btn btn-primary hover-effect col-md-12" value="Send">
        </div>

      </form>
    </div>
  </div>

  <iframe class="border rounded m-0 p-4" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235014.29918684467!2d72.41493071500537!3d23.020158085148303!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1702057797166!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" aria-hidden="false" referrerpolicy="no-referrer-when-downgrade" tabindex="0"></iframe>

</div>
<?php
if (isset($_POST['message_submit'])) {

  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $phone_number = $_POST['phone_number'];
  $message = $_POST['message'];

  if (!empty($fullname) or !empty($email) or !empty($address) or !empty($phone_number) or !empty($message)) {
      $cust_query = $query = "INSERT INTO contact_us (fullname, email, address, phone_number, message) VALUES('$fullname','$email','$address','$phone_number','$message')";
      if (mysqli_query($con, $cust_query)) {
        $messages = "Your data submited Successfully!";
      }
      else {
        $messages = "Your data doesn't submited";
      }
  } else {
    $messages = "All (*) Fields Required";
  }
}

?>

<?php
 include('include/footer.php'); 
?>