<?php
    include_once("./class/session.php");
    include_once("./class/db.php");
    include_once("./utils.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php
        echo "<p>" . $session->getValue("error", true) . "</p>";

        if ($session->getValue("isConnected") === null) {
            include("./login.php");
        } else {
            include("./accueil.php");
        }
    ?>
</body>
</html>
