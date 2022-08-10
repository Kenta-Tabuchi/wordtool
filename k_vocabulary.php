<h3>使用されている単語数カウント機</h3>

形態素解析を行い使われている単語数を計算します。</br></br>

<form action="" method="post">
<textarea name="data" cols="50" rows="8"></textarea ></br>
<input type="submit" value="計算">
</form>

<?php
require_once './igo/Igo.php';  //形態素解析ツールigoのアドレス指定
$igo = new Igo("./ipadic", "UTF-8"); //辞書フォルダのアドレス指定
$count=0;
$word_list = array();//形態素解析して得たワードとその出現回数を示す連想配列
$word_looked = array();//形態素解析して得たワードでそれをもう見たことを確認するための配列
$word_count = 0;

function word_list_in($in_data){
    //グローバル変数指定
    global $igo,$word_list,$word_looked,$word_count;

    //echo $in_data;

    $result = $igo->parse($in_data);

    $word_count+=count($result);

    foreach($result as $key=>$value){
        $go=$value->surface.",".$value->feature;

        //word_listの中を見る
        if (array_key_exists($go,$word_list)){
            //word_listの中に今チェックしている奴と同じ奴があった場合
            if (in_array($go,$word_looked, true)){
                //もう見た場合はスルー
                continue;
            }else{
                //初めて見た場合は出現回数を1つ増やす
                $word_list[$go]+=1;
            }
        }else{
            //同じ奴がなかった場合word_listに追加
            $word_list[$go]=1;
            $word_looked[]=$go;
        }
    }        
}


if(isset($_POST['data'])){
    //echo $_POST['data'];
    //改行統一
    //$data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data']);

    //行ごとに分割する
    //$gyos=explode('</br>',$data);

    //ワードカウント
    //foreach($gyos as $gyo){
    //    word_list_in($gyo);
    //}

    word_list_in($_POST['data']);

    //var_dump($word_list);

    echo "文字数：".mb_strlen($_POST['data'])."</br>";
    echo "単語総数：".$word_count."</br>";
    echo "単語種類数：".count($word_list)."</br>";
    echo "単語総数/文字数：".$word_count/mb_strlen($_POST['data'])."</br>";
    echo "単語種類数/文字数：".count($word_list)/mb_strlen($_POST['data'])."</br>";
    echo "単語種類数/単語総数：".count($word_list)/$word_count."</br>";

}

?>
