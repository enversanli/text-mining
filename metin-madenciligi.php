<?php
function curl($url, $post)
{
    $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; tr; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6';

    $timeout = 1;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    if (!empty($post)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

?>
<html>
<head>
    <title>Metin Madenciliği | Enver ŞANLI</title>
    <style>
        .wrapper {
            width: 1200px;
            margin: 0 auto;
            padding: 0 5px;
        }

        h1 {
            text-align: center;
        }

        .results li {
            display: inline-block;
            width: calc(100% / 3 - 42px);
            padding: 10px;
            margin: 10px;
            line-height: 30px;
            font-size: 1.3em;
            border: 1px solid #888;
            float: left;
        }

        .clear {
            clear: both;
            width: 100%;
            height: 1px;
            line-height: 1px;
            font-size: 1px
        }

        .grams {
            width: 100%;
        }

        .gram-col {
            width: calc(100% - 4 - 12px);
            margin: 10px 5px;
            display: inline-block;
            border: 1px solid #888888;
        }

        .gram-buttons {
            width: 100%;
        }

        .gram-button {
            width: calc(100% / 3 - 20px);
            margin: 10px;
            line-height: 30px;
            border: 1px solid #888888;
            background-color: green;
            color: #fff;
            float: left;
            transition: 0.2s all ease-in-out;
        }

        .gram-button:hover {
            border-color: green;
            cursor: pointer;
            background-color: #00d600;
        }

        #gram-1, #gram-2, #gram-3 {
            display: none;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <h1>Enver ŞANLI - N-Gram Çıkarımı</h1>

    <h3>Bu Metin, <a href="http://www.metinmadenciligi.com/">metinmadenciligi.com</a> adresinden Çekilmektedir.</h3>
    <ul class="applied">
        <li>Metin sayfa her yenilendiğinde Bir Siteden Çekilmekte (CURL ile) : <a
                    href="http://www.metinmadenciligi.com/">metinmadenciligi.com</a>
        </li>
        <li>Metin sayfadaki P elementlerinden ayıklandı</li>
        <li>Metin tamamen küçük harfe çevrildi</li>
        <li>Mevcut Noktalama işaretleri kaldırıldı</li>
        <li>Kelime Kelime Ayırıldı</li>
        <li>Her Kelimenin Metin içinde kaç kez geçtiği tarandı</li>
        <li>Kelime sayısına göre sıralanarak tablo halinde sıralandı</li>
        <li>N gram çıkarımı için uygun hale getirildi</li>
        <li>1-Gram Unigram Çıkarımı</li>
        <li>2-Gram Bigram Çıkarımı</li>
        <li>3-Gram Trigram Çıkarımı</li>
        <li>Butonlara tıklandığı zaman her birinin değerlerine ayrı ayrı ulaşılabilmektedir...</li>
    </ul>
    <?php
    function remove_empty($array)
    {
        return array_filter($array, '_remove_empty_internal');
    }

    function _remove_empty_internal($value)
    {
        return !empty($value) || $value === 0;
    }

    preg_match_all('@<p>(.*?)</p>@si', curl("http://www.metinmadenciligi.com/", ""), $result);
    $kelime_sayisi = 0;
    $paragraf = "";
    foreach ($result as $item) {

        for ($i = 0; $i < count($item); $i++) {
            $paragraf .= strip_tags($item[$i]);
        }
    }
    $paragraf = strtolower($paragraf);
    $paragraf = str_replace(',', '', $paragraf);;
    $paragraf = str_replace('.', '', $paragraf);;
    $paragraf = str_replace('(', '', $paragraf);;
    $paragraf = str_replace(')', '', $paragraf);;
    $paragraf = str_replace(';', '', $paragraf);;
    $paragraf = str_replace(':', '', $paragraf);;
    $paragraf = str_replace('/', ' ', $paragraf);;
    echo $paragraf;
    $new = explode(" ", strip_tags($paragraf));
    $new = remove_empty($new);
    //        print_r($new);
    $kelime_sayaci = array();
    $syc = 0;
    foreach ($new as $item) {
        if (!empty($item) || $item !== null || $item !== "" | $item !== " ") {
            echo "<br>" . "KELİME = " . $item;
            $syc++;
        }
        $kelime_sayaci[$item] = 0;
        foreach ($new as $itm) {
            if ($itm == $item) {
                $kelime_sayaci[$item]++;
            }
        }
    }
    echo "SAYC : " . $syc;
    //    print_r($kelime_sayaci);
    $keys = array_keys($kelime_sayaci);
    $values = array_values($kelime_sayaci);
    ?>

    <div class="results">
        <ul>
            <?php
            $tutucu = 0;
            for ($i = 0; $i < count($keys); $i++) {
                for ($x = 0; $x < count($keys); $x++) {
                    if ($values[$x] < $values[$i]) {
                        $tutucu = $keys[$x];
                        $keys[$x] = $keys[$i];
                        $keys[$i] = $tutucu;
                        $tutucu = $values[$x];
                        $values[$x] = $values[$i];
                        $values[$i] = $tutucu;
                    }
                }
            }

            for ($i = 0; $i < count($keys); $i++) {
                ?>

                <li><b style="color: red"><?=$keys[$i]?></b> = <b><?=$values[$i]?> Adet</b></li>

                <?php
            }
            ?>
        </ul>
        <?php
        $gram_1 = array();
        $gram_2 = array();
        $gram_3 = array();
        $new_parag = trim(preg_replace('/\s\s+/', ' ', $paragraf));
        $new_parag = trim(preg_replace('/\s+/', ' ', $new_parag));
        $new_parag = str_replace(" ", "", $paragraf);
        $new_parag = str_replace("ç", "c", $new_parag);
        $new_parag = str_replace("ğ", "g", $new_parag);
        $new_parag = str_replace("ö", "o", $new_parag);
        $new_parag = str_replace("ü", "u", $new_parag);
        $new_parag = str_replace("ş", "s", $new_parag);
        $bom = pack('H*', 'EFBBBF');
        $new_parag = $text = preg_replace("/^$bom/", '', $new_parag);
        //        echo "NEW PARAG : " . $new_parag[3];

        ?>
        <div class="gram-buttons">
            <button class="gram-button" id="display-gram-1">1-Gram</button>
            <button class="gram-button" id="display-gram-2">2-Gram</button>
            <button class="gram-button" id="display-gram-3">3-Gram</button>
            <button class="clear"></button>
        </div>
        <ul class="grams" id="gram-1">
            <?php
            for ($i = 0; $i < strlen($new_parag); $i++) {
                ?>

                <li class="gram-col">
                    <?=$new_parag[$i];?>
                </li>
                <?php
            }
            ?>
        </ul>
        <ul class="grams" id="gram-2">
            <?php
            for ($i = 0; $i < strlen($new_parag); $i++) {
                ?>

                <li class="gram-col">
                    <?=$new_parag[$i] . $new_parag[$i + 1];?>
                </li>
                <?php
            }
            ?>
        </ul>
        <ul class="grams" id="gram-3">
            <?php
            for ($i = 0; $i < strlen($new_parag); $i++) {
                ?>
                <li class="gram-col">
                    <?=$new_parag[$i] . $new_parag[$i + 1] . $new_parag[$i + 2];?>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>

<script src="https://sivasweb.net/assests/jquery-3.4.1.min.js"></script>
<script>
    $('#display-gram-1').click(function () {
        $('#gram-1').show();
        $('#gram-2').hide();
        $('#gram-3').hide();
    });
    $('#display-gram-2').click(function () {
        $('#gram-1').hide();
        $('#gram-2').show();
        $('#gram-3').hide();
    });
    $('#display-gram-3').click(function () {
        $('#gram-1').hide();
        $('#gram-2').hide();
        $('#gram-3').show();
    });
</script>
</body>
</html>
