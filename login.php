<?php
session_start();
require('dbconnect.php');
$message='';

if(!empty($_POST)){
    if($_POST['pw']!=='' && $_POST['mailAddr']!==''){
        $login = $db->prepare('SELECT pw FROM users WHERE mailAddr=:mail');
        $login->bindParam(':mail',$_POST['mailAddr']);
        $login->execute();
        $userpass = $login->fetch();  

        if(password_verify($_POST['pw'], $userpass['pw'])){
            echo $userpass['pw'].'ログインに成功しました。';
            $uidSearch = $db->query('SELECT `id`,`username` FROM `users` WHERE `pw`="'.$userpass['pw'].'"');
            $foundUID = $uidSearch->fetch();
            $_SESSION['id'] = $foundUID['id']; //ユーザIDの受け渡し
            $_SESSION['username'] = $foundUID['username'];
            //echo $_SESSION['id'];
            header('Location: index.php');
            exit();
        }else{
            $message= 'ログインに失敗しました。';
        }
    }else{
        $message= '必要事項を記入してください。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインページ</title>
</head>

<body>
<div id="wrap">
<h1>ログイン</h1>
<p>メールアドレスとパスワードを記入してログインしてください。</p>
<?php
if($message!=''){
    echo '<p class="message">'.$message.'</p>';
}
?>

<form action="" method="post">
    <dl>
        <dt>メールアドレス</dt>
        <dd>
            <input type="text" name="mailAddr" size="35" maxlength="255" value="<?php //echo htmlspecialchars($_POST['mailAddr']); ?>">
        </dd>
        <dt>パスワード</dt>
        <dd>
            <input type="password" name="pw" size="35" maxlength="255" value="<?php //echo htmlspecialchars($_POST['pw']); ?>">
        </dd>
        <dd>
            <input type="submit" value="ログインする" />
            <input type="button" value="新規の方はこちら →" onClick="location.href='signup.php'"/>
        </dd>
    </dl>
</form>
</div>
</body>    
</html>