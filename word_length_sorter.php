<h3>簡易ソーター</h3>

区切り文字で区切った左側の文字数長でソートし出力します。</br>

<form action="/contents/wordtool/word_length_sorter.php" method="post">
区切り</br>
<input type="text" id="kurigi" name="kugiri"></br></br>
<textarea name="data" cols="50" rows="8"></textarea ></br>
<input type="submit">
</form>

<?php
    if(isset($_POST['data'])){
        $mix=array();

        //改行統一
        $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data']);

        //行ごとに分割する
        $gyos=explode('</br>',$data);

        foreach($gyos as $gyo){
            $gos=explode($_POST['kugiri'],$gyo);

            $left=$gos[0];
            $right="";
            if (isset($gos[1])){
                $right=$gos[1];
            }

            $mix[]=array('left'=>$left,'right'=>$right,'left_length'=>mb_strlen($left));

        }

        //ソート
        $ids = array_column($mix, 'left_length');
        array_multisort($ids, SORT_ASC, $mix);


        echo '<table border="1" width="100%" style="table-layout:fixed">';
        foreach($mix as $x){
            print '<tr>';
            print '<td>'.$x['left'].','.$x['right'].'</br></td></tr>';
        }
        print '</table>';
    }

?>
