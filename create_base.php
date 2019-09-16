<?php

require ('.\connect_db.php');
$sql = 'CREATE TABLE IF NOT EXISTS AVITO ('.
    'post_id INT UNSIGNED NOT NULL AUTO_INCREMENT,'.
    'url VARCHAR(200) NOT NULL,'.
    'adr VARCHAR(200) NOT NULL,'.
    'opis VARCHAR(10000) NOT NULL,'.
    'tel VARCHAR(1000) NOT NULL,'.
    'post_date DATETIME NOT NULL,'.
    'PRIMARY KEY (post_id))';
if(mysqli_query($dbc, $sql) === TRUE)
{
    echo 'Таблица "AVITO" успешно создана';
}else{
    echo 'ошибка создания таблицы: '.mysqli_error($dbc);
}
mysqli_error($dbc);