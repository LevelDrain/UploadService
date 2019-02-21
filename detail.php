<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="utf-8">
<title>個別ページ</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php
    require('dbconnect.php');

    $gotId = $_GET['imageid'];
    $records = $db->query('SELECT * FROM images');
    $file="";

    echo "<ul>";
    while($record=$records->fetch()){
        if($gotId == $record['id']){
            $file ="./images/".$record['userid']."/".$record['title'].".".$record['extention'];

            if(preg_match('/\.mp3$|\.m4a$/i', $file)){
                echo "<li><audio src='".$file."'loop controls></audio></li>";
            }elseif(preg_match('/\.mp4$|\.avi$/i', $file)){
                echo "<li><video src='".$file."'width=40% controls></video></li>";
            }else{
                echo "<li><img src='".$file."'width=40% ></li>";
                echo "<p>".$record['description']."</p>";
            }
        }
    }
    echo "</ul>";
?>

<br>
<a href="index.php">戻る</a>
</body>
</html>