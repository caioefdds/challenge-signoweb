<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title>Poll System</title>
<?php
    $__config = parse_ini_file("../config.ini", true);

    include($__config['GERAL']['path_view']."classes/class.session.php");
    include($__config['GERAL']['path_view']."classes/class.query.php");

    $__session = new Session($__config);
    $__query = new Query();
?>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>