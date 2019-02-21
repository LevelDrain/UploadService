<?php
session_start();
require('dbconnect.php');

if(!empty($_POST)){
    if($_POST['username']===''||$_POST['pw']===''||$_POST['mailAddr']===''){
        echo 'ニックネーム、パスワード、メールアドレスは必須項目です';
    }else{
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE mailAddr=?');
        $member->execute(array($_POST['mailAddr']));
        $record = $member->fetch();
        $thisUserID = '';

        if($record['cnt'] > 0){
            echo '既に登録されているメールアドレスです';
        }else{
            $thisUserID = bin2hex(random_bytes(3));
            $_SESSION['join'] = $_POST;

            $statement = $db->prepare('INSERT INTO `users` SET id=?, username=?,
            pw=?, mailAddr=?');//プレースホルダ

            $statement->execute(array(
            $thisUserID,
            $_SESSION['join']['username'],
            password_hash($_SESSION['join']['pw'], PASSWORD_BCRYPT),
            $_SESSION['join']['mailAddr']
            ));
            unset($_SESSION['join']);

            //IDからユーザの個別ディレクトリを作る
            $userFileID = 0;
            $searchIDs = $db->query('SELECT * FROM users');

            while($searched = $searchIDs->fetch()){
                if($thisUserID != $searched['id']){
                    $userFileID = $thisUserID;
                }
            }
            //echo($userFileID);
            $directory_path = 'images/'.$userFileID;
            
            if(file_exists($directory_path)){
                //存在したときの処理
                //echo($directory_path);
                echo '<p>作成しようとしたディレクトリは既に存在します</p>';
            }else{
                if(mkdir($directory_path, 0777)){
                    //作成に成功した時の処理
                    //echo '<p>ディレクトリ'.$userFileID.'を作成しました。</p>'; //←デバッグ用。セキュリティに問題あり。
                    echo '<p>個別ページを作成しました。</p>';
                }else{
                    //作成に失敗した時の処理
                    echo '<p>作成に失敗しました</p>';
                }
            }

            echo '登録が完了しました。';
            echo '<form action="login.php"><input type="submit" value="戻る"></form>';
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>登録ページ</title>
</head>

<body>
<h1>新規登録画面</h1>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム</dt>
		<dd>
        	<input type="text" name="username" size="35" maxlength="255" value="">
		</dd>
		<dt>パスワード</dt>
		<dd>
        	<input type="password" name="pw" size="10" maxlength="20" value="">
        </dd>
		<dt>メールアドレス</dt>
		<dd>
        	<input type="text" name="mailAddr" size="35" maxlength="255" value="">
        </dd>
        <dd>
            <input type="button" value="← 戻る" onClick="location.href='login.php'"/>
            <input type="submit" value="登録する" />
        </dd>
	</dl>
	<div></div>
</form>

</body>    
</html>