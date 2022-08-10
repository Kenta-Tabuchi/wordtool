<h3>簡易単語出現比率カウント機</h3>

入力データを1行で1項目として扱い、ある単語が何項目に出現したかをカウント、ランキングリストアップします。</br>
二つの群データを入力することで、単語の出現回数を比較、その単語が出てきた場合A群に属する確率も計算します。</br>
</br>

<form action="" method="post">
<h4>A群</h4>
<textarea name="data1" cols="50" rows="8"></textarea ></br>
<h4>B群</h4>
<textarea name="data2" cols="50" rows="8"></textarea ></br>
<input type="submit">
</form>

<?php
require_once './igo/Igo.php';  //形態素解析ツールigoのアドレス指定
$igo = new Igo("./ipadic", "UTF-8"); //辞書フォルダのアドレス指定
$count_1=0;//A群の行数
$count_2=0;//B群の行数
$word_list_1 = array();//A群を形態素解析して得たワードとその出現回数を示す連想配列
$word_list_2 = array();//B群を形態素解析して得たワードとその出現回数を示す連想配列
$word_list_last = array();//形態素解析して得たワードとそれが出たのがA群だった割合を格納する配列
$word_looked = array();//そのワードは今チェックしてる作品でもう見たのを示す配列

function word_list_in($in_data,&$word_list){
    //グローバル変数指定
    global $igo,$word_looked;

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


if(isset($_POST['data1'])){
    //*****************A群*********************
    //改行統一
    $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data1']);

    //行ごとに分割する
    $gyos=explode('</br>',$data);

    //ワードカウント
    foreach($gyos as $gyo){
        $count_1++;
        word_list_in($gyo,$word_list_1);
        //word_lookedをリセット
        $word_looked=array();
    }

    //word_listをソート
    arsort($word_list_1);

    //B群
    //改行統一
    $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data2']);

    //行ごとに分割する
    $gyos=explode('</br>',$data);

    //ワードカウント
    foreach($gyos as $gyo){
        $count_2++;
        word_list_in($gyo,$word_list_2);
        //word_lookedをリセット
        $word_looked=array();
    }

    //出現比率計算
    foreach($word_list_1 as $key => $value){
        $value2=0;
        if(isset($word_list_2[$key])){
            $value2=$word_list_2[$key];
        }
        //群A／Bがあり、条件Xを満たしている時A群である確率は
        //全体のうちA群な確率＊A群のうちXである確率/(全体のうちA群な確率＊AのうちXな確率）＋（全体のうちB群な確率＊BのうちXな確率）
        $ARitu_all=$count_1/($count_1+$count_2);
        $BRitu_all=$count_2/($count_1+$count_2);
        $ARitu=($ARitu_all*($value/$count_1))/(($ARitu_all*($value/$count_1))+($BRitu_all*($value2/$count_2)));
        $word_list_last[$key]=round(($ARitu*100),2);
    }

    //word_listをソート
    arsort($word_list_last);

    echo '<table border="1" width="100%" style="table-layout:fixed">';

    foreach($word_list_last as $key => $value){
        $explode_data=explode(',',$key);
        print '<tr>';
        print '<td width="20%">'.$explode_data[0].'</td>';
        print '<td style="word-wrap:break-word;">';    
        print $key.'</td>';
        $value1=0;
        if(isset($word_list_1[$key])){
            $value1=$word_list_1[$key];
        }
        print '<td width="5%">'.$value1.'件</td>';
        $value2=0;
        if(isset($word_list_2[$key])){
            $value2=$word_list_2[$key];
        }
        print '<td width="5%">'.$value2.'件</td>';
        print '<td width="5%">'.$value.'％ </br></td>';
        print '</tr>';
    }
    print '</table>';
    print '</br>';

}

?>
