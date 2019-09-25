<?php

class GetDB
{
    private $a;
    public function apruv($url)
    {
        require('connect_db.php');
        $sql = 'SELECT * FROM avito';
        $result = mysqli_query($dbc, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $url_in_db = $row['url'];

                if ($url == $url_in_db)
                {
                    $this->a = 1;
                    echo 'Совпадение'."\n";
                    echo $url."\n";
                }
            }
        }
    }

    public function get_in_db($url_page, $adr, $opis, $tel)
    {
        $b = $this->a;
        if ($b != 1)
        {
            require ('connect_db.php');
            $sql = "INSERT INTO AVITO
            (url, adr, opis, tel, post_date)
            values 
            ('$url_page', '$adr', '$opis', '$tel', NOW())";
            mysqli_query($dbc, $sql);
        }
    }
}

$url = 'https://www.avito.ru/tula/kvartiry?p=1';
echo $url;
$doc = new DOMDocument();
$doc->formatOutput = false;
sleep(10);
$html = file_get_contents($url);
@$doc->loadHTML($html);
$domxpath = new DOMXPath($doc);
$tagName = 'a';
$attrName ='class';
$attrValue ='pagination-page';
$filtered = $domxpath->query("//$tagName" . '[@' . $attrName . "='$attrValue']");

$arr_page = [];
$arr_opis = [];
// Get url pages
foreach ($filtered as $key => $value)
{

    $page = $value->getAttribute('href');

    $arr_page[] = $page;

}
unset($doc);
unset($domxpath);
// Get last page
$last_page = array_pop($arr_page);
$last_page = explode('=',$last_page);
$last_page = array_pop($last_page);
$last_page = intval($last_page);
echo '<p>'.$last_page.'</p>';
$arr_page = [];
$arr_number = [];
$arr_price = [];
for($i=1;$i < ($last_page+1); $i++)
{
    echo '<p>'.$i.'</p>';
    sleep(10);
    $url = 'https://www.avito.ru/tula/kvartiry?p='.strval($i);
    $html = file_get_contents($url);
    $doc = new DOMDocument();
    $doc->formatOutput = false;
    @$doc->loadHTML($html);
    $domxpath = new DOMXPath($doc);
    $tagName = 'a';
    $attrName ='class';
    $attrValue ='item-description-title-link';
    $filtered = $domxpath->query("//$tagName" . '[@' . $attrName . "='$attrValue']");
    foreach ($filtered as $key => $value)
    {

        $page = $value->getAttribute('href');

        echo $page."\n";

        $arr_page[] = $page;

    }

    foreach ($arr_page as $key => $value)
    {
        sleep(10);
        // Забираем ссылку (запомнить в БД для последующих сравнений)
        $url_page = 'https://m.avito.ru'.$value;
        $html_page = file_get_contents($url_page);
        echo $url_page."\n";
        $doc_p = new DOMDocument();
        $doc_p->formatOutput = false;
        @$doc_p->loadHTML($html_page);
        /*
        $domxpath_p = new DOMXPath($doc_p);
        $tagName_p = 'a';
        $attrName_p ='class';
        $attrValue_p ='BPWk2';
        $filtered_p = $domxpath_p->query("//$tagName" . '[@' . $attrName . "='$attrValue']");
        foreach ($filtered_p as $key_p=>$value_p)
        {
            echo 'Номер'."\n";
            $number=$value_p->getAttribute('href');
            echo $number."\n";
        }
        */
        //Get telephone number (from mobile version avito.ru)
        $ahreftags = $doc_p->getElementsByTagName('a');

        foreach ($ahreftags as $tag) {
            $number = $tag->getAttribute('href');
            $fin = stristr($number,'tel:+');
            if ($fin!=null)
            {
                $number= explode(':',$number);
                $tel = array_pop($number);
                $arr_number[]=$tel;
                echo $tel."\n";
            }

        }
        //Берём описания квартир
        $domxpath_p = new DOMXPath($doc_p);
        $tagName_p = 'meta';
        $attrName_p ='name';
        $attrValue_p ='description';
        $filtered_p = $domxpath_p->query("//$tagName_p" . '[@' . $attrName_p . "='$attrValue_p']");
        $filt = [];
        foreach ($filtered_p as $tag)
        {

            $opis = $tag->getAttribute('content');
            $opis = utf8_decode($opis);
            $filt[]=$opis;
        }

        echo "Описание \n";
        $arr_opis[] = $opis;
        $tagName_p = 'span';
        $attrName_p ='data-marker';
        $attrValue_p ='delivery/location';
        $filtered_p = $domxpath_p->query("//$tagName_p" . '[@' . $attrName_p . "='$attrValue_p']");
        foreach ($filtered_p as $key_p=>$value_p)
        {
            echo 'Адрес'."\n";
            $number=$value_p->nodeValue;
            $number = utf8_decode($number);
            echo$number."\n";
        }

        $k=new GetDB();
        $k->apruv($url_page);
        $k->get_in_db($url_page,$number,$opis,$tel);
        unset($k);
    }
}
