<?php
$dbc = mysqli_connect('127.0.0.1','root','1234', 'avito')
    or die(mysqli_connect_error());

mysqli_set_charset($dbc,'utf-8');
?>