<?php
require('../connect_db.php');
$sql = 'SELECT * FROM avito';
$result = mysqli_query($dbc, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $url = $row['url'];
        $adr = $row['adr'];
        $opis = $row['opis'];
        $tel = $row['tel'];
        $date = $row['post_date'];
        echo '<tr>'.'<td>'.'<a href="'.$url.'">'.$url.'</a>'.'</td>'.'<td>'.$adr.'</td>'.'<td>'.$opis.'</td>'.'<td>'.$tel.'</td>'.'<td>'.$date.'</td>'.'</tr>'; //<tr><td>34,5</td><td>3,5</td><td>36</td><td>23</td></tr>
    }
}
