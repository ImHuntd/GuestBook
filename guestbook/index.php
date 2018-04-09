<?php

  session_start();
  include "inc/database-connection.php";
  include "securimage/securimage.php";


  $securimage = new Securimage();

  //var_dump($_SESSION);
  //var_dump($_POST);

  //die();

  $firstName = "";
  $insertion = "";
  $lastName = "";
  $emailAdress = "";
  $websiteAdress = "";
  $message = "";
  $captcha = "";


  $_SESSION['myMessage'] = "";

  $errors = array();

  if(isset($_POST['firstName'])) {
    $firstName = $_POST['firstName'];

    if($firstName == "")
    {
      $errors[] = "vergeet je voornaam niet";
    }
  }
  if(isset($_POST['insertion'])) {
    $insertion = $_POST['insertion'];
  }
  if(isset($_POST['lastName'])) {
    $lastName = $_POST['lastName'];
    if($lastName == "")
    {
      $errors[] = "vergeet je achternaam niet";
    }
  }
  if(isset($_POST['emailAdress'])) {
    $emailAdress = $_POST['emailAdress'];
  }
  if(isset($_POST['websiteAdress'])) {
    $websiteAdress = $_POST['websiteAdress'];
  }
  if(isset($_POST['message'])) {
    $message = $_POST['message'];
  }

  if (isset($_POST['submit']))
  {

    if ($securimage->check($_POST['captcha_code']) == false) {
  $errors[] = "wrong captcha code";
}

    if(count($errors) == 0)
    {
      //  if ($captcha == "smwm") {
        //  die("you are here : on line" . __LINE__);
          $sql = "INSERT INTO guestbook (FirstName, Insertion, LastName, EmailAdress, WebsiteAdress, Message)
          VALUES ('$firstName', '$insertion', '$lastName', '$emailAdress', '$websiteAdress', '$message')";

           if (mysqli_query($conn, $sql)) {
                  $_SESSION['myMessage'] = "Thank you for posting";
                  header("location: index.php");
          } else {
                  $_SESSION['myMessage'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
                  header("location: index.php");
                }
              }
    }
    //  }
 ?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Guestbook">
  <meta name="keywords" content="HTML,CSS,Guestbook,JavaScript">
  <meta name="author" content="Thomas Godding">
  <meta name="copyright" content="Tgod">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guestbook</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
  .errorList
  {
    background-color: red;
    color:white;
  }
  </style>
  <?php
      include("inc/header.php");
  ?>
    <div class="guestbook">
      <?php
echo $_SESSION['myMessage'];


if(count($errors) > 0)
{
  echo "<ul class='errorList'>";
  foreach($errors as $x)
  {
    echo "<li>";
    echo $x;
    echo "</li>";
  }
  echo "</ul>";
}
       ?>
      <form id="contact" method="POST">
      <table>
        <tr>
          <td class="table-data">First Name:</td>
          <td><input type="text" name="firstName" /></td>
        </tr>
        <tr>
          <td>Insertion:</td>
          <td><input type="text" name="insertion" /></td>
        </tr>
        <tr>
          <td>Last Name:</td>
          <td><input type="text" name="lastName" /></td>
        </tr>
        <tr>
          <td>E-mail Adress:</td>
          <td><input type="email" name="emailAdress" /></td>
        </tr>
        <tr>
          <td>Website Adress:</td>
          <td><input type="url" name="websiteAdress" /></td>
        </tr>
      <tr>
        <td>Message:</td>
        <td><textarea name="message" ></textarea></td>
      </tr>
      <tr>
        <td>Captcha:</td>
        <td>
          <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
          <input type="text" name="captcha_code" size="10" maxlength="6" />
          <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[Change Image]</a>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <button name="submit" type="submit">Submit</button>
          <button name="reset" type="reset">Reset</button>
        </td>
      </tr>
    </table>
      </form>
    </div>

       <div class="review">

   <?php
   $sql5 = "select * from guestbook";
   $reviewRatings = mysqli_query($conn, $sql5);

$reviews = array();
while($row5 = mysqli_fetch_assoc($reviewRatings))
{
  $reviews[] = $row5;
}

foreach($reviews as $review)
{
  echo "<div class='reviewDiv'>"; echo "<br>";
    echo "Message: ";
  echo $review['message']; echo "<br>";
    echo "Email adress: ";
    echo $review['emailAdress']; echo "<br>";
    echo "Website adress: ";
    echo $review['websiteAdress']; echo "<br>";
  echo "</div>";
}

$review = "";
?>
</div>
  <?php
    include "inc/footer.php";
  ?>
</body>
</html>
