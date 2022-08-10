<h3>簡易スプリッター</h3>

<form action="/contents/wordtool/explode.php" method="post">
区切り</br>
<input type="text" id="kurigi" name="kugiri"></br></br>
<textarea name="data" cols="50" rows="8"></textarea ></br>
<input type="submit">
</form>

<?php
    if(isset($_POST['data'])){
        $left=array();
        $right=array();

        //改行統一
        $data=str_replace(array("\r\n", "\r", "\n"),"</br>",$_POST['data']);

        //行ごとに分割する
        $gyos=explode('</br>',$data);

        foreach($gyos as $gyo){
            $gos=explode($_POST['kugiri'],$gyo);
            $left[]=$gos[0];
            if (isset($gos[1])){
                $right[]=$gos[1];
            }else{
                $right[]="";
            }
        }
        echo '<h4>左</h4>';
        echo '<table border="1" width="100%" style="table-layout:fixed">';
        foreach($left as $x){
            print '<tr>';
            print '<td>'.$x.'</br></td></tr>';
        }
        print '</table>';
        print '</br></br></br>';
        echo '<h4>右</h4>';
        echo '<table border="1" width="100%" style="table-layout:fixed">';
        foreach($right as $x){
            print '<tr>';
            print '<td>'.$x.'</br></td></tr>';
        }
        print '</table>';
        print '</br>';
    }

?>
