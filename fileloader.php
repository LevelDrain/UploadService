<?php
    function dispContents($sentUID){
        require('dbconnect.php');

        $files = [];
        $records = $db->query('SELECT * FROM images');

        while($record=$records->fetch()){
            if($record['userid']==$sentUID){
                $files[$record['id']] ="./images/".$record['userid']."/".$record['title'].".".$record['extention'];
                //print($files[$record['id']]."\n");
            }
        }

        echo "<article class='editor'>";
        echo "<h1>いろいろなコンテンツを表示するページのサンプル</h1>";
        echo "<ul>";
            foreach($files as $id => $file){
                if(preg_match('/\.mp3$|\.m4a$/i', $file)){
                    echo "<li><audio src='".$file."'loop controls></audio></li>";
                }elseif(preg_match('/\.mp4$|\.avi$/i', $file)){
                    echo "<li><video src='".$file."'width=40% controls></video></li>";
                }else{
                    echo "<li><a href='detail.php?imageid=".$id."'><img src='".$file."'width=40% ></li>";
                }
            }
        echo "</ul>";
        echo "</article>";
    }
?>