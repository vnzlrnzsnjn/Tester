<?php
require 'config.php';

$name = $_POST['name'];
$number = $_POST['countryCode'] . $_POST['contactNum'];
$email = $_POST['email'];
$message = $_POST['message'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $checkemail = $conn->prepare('SELECT email FROM subscribers WHERE email = :email');
    $checkemail->bindParam(':email', $email);
    $checkemail->execute();

    $checkcontact = $conn->prepare('SELECT number FROM subscribers WHERE number = :number');
    $checkcontact->bindParam(':number', $number);
    $checkcontact->execute();

    if ($checkemail->rowCount() > 0) {
        echo "<SCRIPT type='text/javascript'> //not showing me this
    alert('Email Already Subscribed');
    window.location.replace(\"index.html\");
    </SCRIPT>";
    } elseif ($checkcontact->rowCount() > 0) {
        echo "<SCRIPT type='text/javascript'> //not showing me this
    alert('Contact Number Already Used');
    window.location.replace(\"index.html\");
    </SCRIPT>";
    } else {
        $subscribe = $conn->prepare('INSERT INTO subscribers (name, number, email, message) 
    VALUES (:name, :number, :email, :message)');
        $subscribe->bindParam(':name', $name);
        $subscribe->bindParam(':number', $number);
        $subscribe->bindParam(':email', $email);
        $subscribe->bindParam(':message', $message);

        //execute
        $subscribe->execute();

        echo "<SCRIPT type='text/javascript'> //not showing me this
    alert('Thank you for subscribing');
    window.location.replace(\"index.html\");
    </SCRIPT>";
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
$conn = null;
