<h3>簡易単語出現数カウント機</h3>

入力データを1行で1項目として扱い、ある単語が何項目に出現したかをカウント、ランキングリストアップします。</br>
</br>

<form action="" method="post">
<textarea name="data" cols="50" rows="8"></textarea ></br>
<input type="submit">
</form>

<?php
require_once './igo/Igo.php';  //形態素解析ツールigoのアドレス指定
$igo = new Igo("./ipadic", "UTF-8"); //辞書フォルダのアドレス指定
$count=0;
$word_list = array();//形態素解析して得たワードとその出現回数を示す連想配列
$word_looked = array();//そのワードは今チェックしてる作品でもう見たのを示す配列

function word_list_in($in_data){
    //グローバル変数指定
    global $igo,$word_list,$word_freq,$word_looked,$word_flag,$word_keyin;

    $result = $igo->parse($in_data);

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

        //デバッグ用：形態素解析画面出力

        //print 'surface='.$value->surface.'</br>';
        //print 'feature='.$value->feature.'</br>';
        //print 'start='.$value->start.'</br>';

    }        
}


if(isset($_POST['data'])){
    //改行統一
    $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data']);

    //行ごとに分割する
    $gyos=explode('</br>',$data);

    //ワードカウント
    foreach($gyos as $gyo){
        word_list_in($gyo);
        //word_lookedをリセット
        $word_looked=array();
    }

    //word_listをソート
    arsort($word_list);

    echo '<table border="1" width="100%" style="table-layout:fixed">';

    foreach($word_list as $key => $value){
        $explode_data=explode(',',$key);
        print '<tr>';
        print '<td width="20%">'.$explode_data[0].'</td>';
        print '<td style="word-wrap:break-word;">';        
        print $key.'</br></td>';
        print '<td width="5%">'.$value.'</td></tr>';
    }
    print '</table>';
    print '</br>';

}

?>
