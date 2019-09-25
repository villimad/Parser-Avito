<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AVITO-PARSING</title>
    <link rel="stylesheet" href="fi.css">
</head>
<body>
<main class="blur">

    <div>
        AVITO-TULA
    </div>

</main>

<table border="1">
    <tr>
        <th>URL</th>
        <th>Адрес</th>
        <th>Описание</th>
        <th>Телефон</th>
        <th>Дата</th>

    </tr>
    <?php include('get_db_data.php'); ?>
</table>

</body>
</html>
