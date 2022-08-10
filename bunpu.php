<h3>分布チェッカー</h3>

探すワードが入っている行を赤くして分布密度を調べます</br>

<form action="/contents/wordtool/bunpu.php" method="post">
探すワード</br>
<input type="text" name="word"></br></br>
<textarea name="data" cols="50" rows="8"><?php if(isset($_POST['data'])){echo $_POST['data'];}?></textarea ></br>
<input type="submit">
</form>

<?php
//グラフ作成用を読み込む
require_once './PHPlot/phplot/phplot.php';

$array=array();
    if(isset($_POST['data']) && isset($_POST['word']) ){

        //echo '<table>';
        //改行統一
        $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data']);

        //行ごとに分割する
        $gyos=explode('</br>',$data);

        $data = array();
        $loop=0;
        foreach($gyos as $gyo){
            $loop++;
            $buf="";
            if($loop%100==0){
                $buf=$loop;
            }
            //echo '<tr><td>'.$loop.'</td>';
            if(strpos($gyo,$_POST['word']) !== false){
                //echo '<td><span style="background-color:red;">'.$gyo.'</span></td>';
                $array[]=$loop;
                $data[]=array($buf,1);
            }else{
                //echo '<td>'.$gyo.'</td>';
                $data[]=array($buf,0);
            }
            //echo '</tr>';
        }
        //echo '</table>';

        //画像作成
        $plot = new PHPlot(1600, 1200);
        $plot->SetPlotType('bars');
        $plot -> SetShading(0);
        $plot->SetDataValues($data);
        $plot -> SetIsInline(true);
        $plot -> SetOutputFile ('./test1.png');
        $plot->DrawGraph();

        echo '<hr>';
        echo '<h4>'.$_POST['word'].'</h4>';
        echo count($array)."/".count($gyos)."</br>";
        echo "出現率：".round((count($array)/count($gyos))*100,2)
        .'％ </br>';

        echo '<img src="./test1.png?'.uniqid().'" style="width:100%">';
        echo '<hr>';

        //10%ごとに分離して出現数をカウント
        $areas=array();
        $areas[0]=0;
        $loop_count=0;
        for($i=1;$i<10;$i++){
            $loop_count+=count($gyos)/10;
            $areas[$i]=$loop_count;
        }
        $areas[10]=count($gyos);

        $areas_count=array(0,0,0,0,0,0,0,0,0,0);
        foreach($array as $x){
            for($i=0;$i<10;$i++){
                if($areas[$i]<$x && $x<=$areas[$i+1]){
                    $areas_count[$i]+=1;
                    break;
                }
            }
        }
        echo '<h4>'.$_POST['word'].'</h4>';
        echo count($array)."/".count($gyos)."</br>";
        echo "出現率：".round((count($array)/count($gyos))*100,2)
        .'％ </br>';
        echo '<table border="1" width="100%">';
        $loop=1;
        echo '<tr><th>全体割合</th><th>出現回数</th><th></th><th>出現数中の割合</th><th></th><th>台域中の出現率</th><th></th><th>全体中の出現率</th></tr>';
        foreach($areas_count as $x){
            echo '<tr><td width="10%">上位'.((($loop-1)*10)+1).'〜'.$loop*10
            .'% </td><td width="10%">'.$x.'回</td><td width="10%">'.round($x/count($array),2)*100
            .'% </td>';
            printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $x / count($array) * 100);
            echo '<td>'.round($x/(count($gyos)/10),2)*100
            .'% </td>';
            printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $x / (count($gyos)/10) * 100);
            echo '<td>'.round($x/count($gyos),2)*100
            .'% </td>';
            printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $x / count($gyos) * 100);
            echo '</tr>';
            $loop++;
        }
        echo '</table>';






        //グラフを書く
        /*
        echo '<table>';
        $before=0;
        foreach($array as $x){
            $y=$x-$before;
            echo '<tr><td>'.$x.'</td>';
            echo '<td>'.$y.'</td></tr>';
            $before=$x;
        }
        echo '</table>';    
        */
        /*
        $before=0;
        print '<table border="1" width="50%">';
        foreach($array as $x){
            $y=$x-$before;
            printf("<tr><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $y);
            print '</tr>';
            $before=$x;
        }
        print '</table>';
        */

        //echo '<img src="./test2.png">';
    }
?>
