<h3>ランカー</h3>

行（記号）数値、という形の奴で同着が何人いるか調べる</br>

<form action="/contents/wordtool/ranker.php" method="post">
スプリッター</br>
<input type="text" name="word"></br></br>
<textarea name="data" cols="50" rows="8"><?php if(isset($_POST['data'])){echo $_POST['data'];}?></textarea ></br>
<input type="submit">
</form>

<?php
//グラフ作成用を読み込む
require_once './PHPlot/phplot/phplot.php';

$array=array();
    if(isset($_POST['data']) && isset($_POST['word']) ){
        $array=array();

        //改行統一
        $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data']);

        //行ごとに分割する
        $gyos=explode('</br>',$data);
        foreach($gyos as $gyo){
            //ワードで分割
            $gos=explode($_POST['word'],$gyo);

            //arrayの中を見る
            if (array_key_exists($gos[1],$array)){
                //初めて見た場合は出現回数を1つ増やす
                $array[$gos[1]]+=1;
            }else{
                //同じ奴がなかった場合arrayに追加
                $array[$gos[1]]=1;
            }
        }
        //キー順でソート
        krsort($array);

        echo '<table border="1" width="50%">';
        $loop=1;
        foreach($array as $key=>$value){
            echo '<tr><td width="5%">'.$loop.'位</td><td width="25%">'.$key
            .'</td><td width="10%">'.$value.'回</td><td width="10%">'.round($value/count($array),2)*100
            .'% </td>';
            printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $value / count($array) * 100);
            echo '</tr>';
            $loop++;
        }
        echo '</table>';
    }
?>
