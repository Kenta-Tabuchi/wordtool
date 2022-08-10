<h3>簡易平均比較機</h3>

入力データを1行で1項目として扱い、文字数長の分布や平均値を算出します。</br>
二つの群データを入力することで、文字数長の分布を比較、A群に属する確率も計算します。</br>
</br>

<form action="" method="post">
<h4>A群</h4>
<textarea name="data1" cols="50" rows="8"></textarea ></br>
<h4>B群</h4>
<textarea name="data2" cols="50" rows="8"></textarea ></br>
<input type="submit">
</form>

<?php
//****************変数定義****************

$count_title_1 =0; //文字数合計
$count_title_word_1 =0; //単語数合計
$max_title_1 = 0; //文字数の最大
$max_title_word_1 = 0; //単語数の最大
$count_num_1 = 0; //行数

$count_title_2 =0; //文字数合計
$count_title_word_2 =0; //単語数合計
$max_title_2 = 0; //文字数の最大
$max_title_word_2 = 0; //単語数の最大
$count_num_2 = 0; //行数

$array_ct_1 =array(0,0,0,0,0,0,0,0,0,0,0);//文字数の出現回数
$array_ctw_1 =array(0,0,0,0,0,0,0,0,0,0,0);//単語数の出現回数

$array_ct_2 =array(0,0,0,0,0,0,0,0,0,0,0);//文字数の出現回数
$array_ctw_2 =array(0,0,0,0,0,0,0,0,0,0,0);//単語数の出現回数

require_once '../igo/Igo.php';  //形態素解析ツールigoのアドレス指定
$igo = new Igo("../ipadic", "UTF-8"); //辞書フォルダのアドレス指定

function average_cal($in_data,&$count_num,&$count_title,&$count_title_word,&$max_title,&$max_title_word,&$array_ct,&$array_ctw){
    //グローバル変数指定
    global $igo;

    //改行統一
    $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$in_data);

    //行ごとに分割する
    $gyos=explode('</br>',$data);
    foreach($gyos as $gyo){
        //行数増やす
        $count_num++;
        //単語数
        //$result = $igo->wakati($gyo);

        //最大値
        if($max_title < mb_strlen($gyo)){
            $max_title=mb_strlen($gyo);
        }
        //if($max_title_word<count($result)){
        //    $max_title_word=count($result);
        //}

        //平均カウント
        $count_title+=mb_strlen($gyo);
        //$count_title_word+=count($result);

        //出現値
        $tl=mb_strlen($gyo);
        if($tl<10){ //タイトル文字数
            $array_ct[0]+=1;
        }else if(10<=$tl&&$tl<20){
            $array_ct[1]+=1;
        }else if(20<=$tl&&$tl<30){
            $array_ct[2]+=1;
        }else if(30<=$tl&&$tl<40){
            $array_ct[3]+=1;
        }else if(40<=$tl&&$tl<50){
            $array_ct[4]+=1;
        }else if(50<=$tl&&$tl<60){
            $array_ct[5]+=1;
        }else if(60<=$tl&&$tl<70){
            $array_ct[6]+=1;
        }else if(70<=$tl&&$tl<80){
            $array_ct[7]+=1;
        }else if(80<=$tl&&$tl<90){
            $array_ct[8]+=1;
        }else if(90<=$tl&&$tl<100){
            $array_ct[9]+=1;
        }else if(100<=$tl){
            $array_ct[10]+=1;
        }

        /*
        $tlw=count($result);
        if($tlw<5){ //タイトル単語数
            $array_ctw[0]+=1;
        }else if(5<=$tlw&&$tlw<10){
            $array_ctw[1]+=1;
        }else if(10<=$tlw&&$tlw<15){
            $array_ctw[2]+=1;
        }else if(15<=$tlw&&$tlw<20){
            $array_ctw[3]+=1;
        }else if(20<=$tlw&&$tlw<25){
            $array_ctw[4]+=1;
        }else if(25<=$tlw&&$tlw<30){
            $array_ctw[5]+=1;
        }else if(30<=$tlw&&$tlw<35){
            $array_ctw[6]+=1;
        }else if(35<=$tlw&&$tlw<40){
            $array_ctw[7]+=1;
        }else if(40<=$tlw&&$tlw<45){
            $array_ctw[8]+=1;
        }else if(45<=$tlw&&$tlw<50){
            $array_ctw[9]+=1;
        }else if(50<=$tlw){
            $array_ctw[10]+=1;
        }
        */
    }
}

if(isset($_POST['data1'])){

    average_cal($_POST['data1'],$count_num_1,$count_title_1,$count_title_word_1,$max_title_1,$max_title_word_1,$array_ct_1,$array_ctw_1);
    average_cal($_POST['data2'],$count_num_2,$count_title_2,$count_title_word_2,$max_title_2,$max_title_word_2,$array_ct_2,$array_ctw_2);

    print '<h4>A群</h4>';
    print '<table border="1" style="table-layout:fixed">';
    print '<tr><th>行数</th><th>最大文字数</th><th>最大単語数</th>
    <th>平均文字数</th><th>平均単語数</th></tr>';
    print '<tr>';
    //連載作品数
    print '<td>'.$count_num_1.'</td>';
    //最大文字数
    print '<td>'.$max_title_1.'文字'.'</td>';
    //平均単語数
    print '<td>'.$max_title_word_1.'単語'.'</td>';
    //平均文字数
    print '<td>'.round($count_title_1 / $count_num_1 , 2).'文字'.'</td>';
    //平均単語数
    print '<td>'.round($count_title_word_1 / $count_num_1 , 2).'単語'.'</td>';
    print '</tr>';
    print '</table>';
    print '</br></br></br>';

    print '<h4>B群</h4>';
    print '<table border="1" style="table-layout:fixed">';
    print '<tr><th>行数</th><th>最大文字数</th><th>最大単語数</th>
    <th>平均文字数</th><th>平均単語数</th></tr>';
    print '<tr>';
    //連載作品数
    print '<td>'.$count_num_2.'</td>';
    //最大文字数
    print '<td>'.$max_title_2.'文字'.'</td>';
    //平均単語数
    print '<td>'.$max_title_word_2.'単語'.'</td>';
    //平均文字数
    print '<td>'.round($count_title_2 / $count_num_2 , 2).'文字'.'</td>';
    //平均単語数
    print '<td>'.round($count_title_word_2 / $count_num_2 , 2).'単語'.'</td>';
    print '</tr>';
    print '</table>';
    print '</br></br></br>';
    
    print '<details>
    <summary style="font-size:24px; background-color:white; border:solid 1px; margin-bottom:1em;">';
    print '＊＊＊＊文字数表＊＊＊＊';
    print '</summary>';
    $array_ct_label=array("10未満",
    "10以上",
    "20以上",
    "30以上",
    "40以上",
    "50以上",
    "60以上",
    "70以上",
    "80以上",
    "90以上",
    "100以上");
    print '<table border="1" width="90%">';
    print '<tr><td>文字数</td><td>A群</td><td>B群</td><td>A群の内割合</td><td></td><td>B群の内割合</td><td></td><td>A群率</td><td></td></tr>';
    for($x=0;$x<=10;$x++){
        print '<tr><td width="20%" style="word-wrap:break-word;">'.$array_ct_label[$x].'</td>';
        print '<td width="5%">'.$array_ct_1[$x].'</td>';
        print '<td width="5%">'.$array_ct_2[$x].'</td>';
        print '<td width="10%">'.round(($array_ct_1[$x]/ $count_num_1 * 100),2)."％ </td>";
        printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $array_ct_1[$x] / $count_num_1 * 100);
        print '<td width="10%">'.round(($array_ct_2[$x]/ $count_num_2 * 100),2)."％ </td>";
        printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $array_ct_2[$x] / $count_num_2 * 100);
        if(($array_ct_1[$x]+$array_ct_2[$x])>0){
            //群A／Bがあり、条件Xを満たしている時A群である確率は
            //A群のうちXな確率＊全体のうちA群な確率/(全体のうちA群な確率＊AのうちXな確率）＋（全体のうちB群な確率＊BのうちXな確率）
            $count_all=$count_num_1+$count_num_2;//OK
            $ARitu_all=$count_num_1/$count_all;//OK
            $BRitu_all=$count_num_2/$count_all;//OK
            $XRitu_1=$array_ct_1[$x]/ $count_num_1;//OK
            $XRitu_2=$array_ct_2[$x]/ $count_num_2;//OK
            $ARitu=($ARitu_all*$XRitu_1)/(($ARitu_all*$XRitu_1)+($BRitu_all*$XRitu_2));

            print '<td width="10%">'.round(($ARitu * 100),2)."% </td>";
            printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", ($ARitu * 100));
        }else{
            print '<td>0% </td>';
            print '<td></td>';
        }
        print '</tr>';
    }
    print '</table>';
    print '</details>';
    print '</br>';
    
    /*
    print '<details>
    <summary style="font-size:24px; background-color:white; border:solid 1px; margin-bottom:1em;">';
    print '＊＊＊＊単語長表＊＊＊＊';
    print '</summary>';
    $array_ctw_label=array("5単語未満",
    "5単語以上",
    "10単語以上",
    "15単語以上",
    "20単語以上",
    "25単語以上",
    "30単語以上",
    "35単語以上",
    "40単語以上",
    "45単語以上",
    "50単語以上");
    print '<table border="1" width="90%">';
    print '<tr><td>単語数</td><td>A群</td><td>B群</td><td>A群の内割合</td><td></td><td>B群の内割合</td><td></td><td>A群率</td></tr>';
    for($x=0;$x<=10;$x++){
        print '<tr><td width="20%" style="word-wrap:break-word;">'.$array_ctw_label[$x].'</td>';
        print '<td width="5%">'.$array_ctw_1[$x].'</td>';
        print '<td width="5%">'.$array_ctw_2[$x].'</td>';
        print '<td width="10%">'.round(($array_ctw_1[$x]/ $count_num_1 * 100),2)."％ </td>";
        printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $array_ctw_1[$x] / $count_num_1 * 100);
        print '<td width="10%">'.round(($array_ctw_2[$x]/ $count_num_2 * 100),2)."％ </td>";
        printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $array_ctw_2[$x] / $count_num_2 * 100);
        if(($array_ctw_1[$x]+$array_ctw_2[$x])>0){
            $count_all=$count_num_1+$count_num_2;
            $ARitu_all=$count_num_1/$count_all;
            $BRitu_all=$count_num_2/$count_all;
            $XRitu_1=$array_ctw_1[$x]/ $count_num_1;
            $XRitu_2=$array_ctw_2[$x]/ $count_num_2;
            $ARitu=($ARitu_all*$XRitu_1)/(($ARitu_all*$XRitu_1)+($BRitu_all*$XRitu_2));

            print '<td width="10%">'.round(($ARitu * 100),2)."％ </td>";
            printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", ($ARitu * 100));
        }else{
            print '<td width="10%">0% </td>';
            print '<td></td>';
        }
        //printf("<td><hr size=\"10\" color=\"#cc6633\" align=\"left\" width=\"%d%%\"></td>", $array_ctw[$x] / $count_num * 100);
        print '</tr>';
    }
    print '</table>';
    print '</details>';
    print '</br>';
    */
}

?>
