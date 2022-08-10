<h3>簡易形態素解析機</h3>

簡易的な形態素解析機です。</br></br>

<form action="" method="get">
<textarea name="data" cols="50" rows="8"></textarea ></br>
<input type="submit">
</form>

<?php
require_once './igo/Igo.php';  //形態素解析ツールigoのアドレス指定
$igo = new Igo("./ipadic", "UTF-8"); //辞書フォルダのアドレス指定
$count=0;

if(isset($_GET['data'])){
    $result = $igo->parse($_GET['data']);
    foreach($result as $key=>$value){
        print 'surface='.$value->surface.'</br>';
        print 'feature='.$value->feature.'</br>';
        $count++;
    }
    echo "</br>".$count."形態素</br>";
}

?>
