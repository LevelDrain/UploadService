<?php
    session_start();
    require_once('uploader.php');
    require_once('fileloader.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>画像一覧を表示するページ</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <form>
        <input type="button" value="ログアウト" onClick="location.href='logout.php'"/>
    </form>
    <hr><br>

    <form action="index.php" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
        <input class="color" type="file" name="userfile[]" multiple="true">
        <br>
        <p><label for="label">画像の説明：<input type="text" name="descripsion"></label>
        <input type="submit" value="アップロード"></p>
    </form>
    
    <?php
        if(!empty($_GET)){
            /* GETメソッドを使う場合は、例外的に処理できるようにする。
               基本的にはSQLインジェクション等に対処する設定。

            if(!empty($_GET['piyo'])){
                echo('特定のGETメソッドに対する処理。');
            }else{...
            */

            echo('<p>不正なURLです。</p>');
            echo('<p>不正行為を行った場合、<br>アカウント削除の対象になります。</p>');
            die();
        }

        $sentUID = '';
        $loginName = '';
        if(!empty($_SESSION['id'] && $_SESSION['username'])){
            $sentUID = $_SESSION['id'];
            $loginName = $_SESSION['username'];
            echo('<p>'.$loginName.'さんとしてログインしています。</p>');

            //それぞれを関数にしておくと、イベントがあったときだけ処理を呼べる
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                doUpload($sentUID);
            }
            dispContents($sentUID);
        
        }else{
            header('Location: login.php');
            exit();
        }

    ?>
</body>
</html>
