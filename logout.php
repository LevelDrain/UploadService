<?php
session_start();
$message = 'ログアウトしますか？';
$inputType = 'submit';

if(!empty($_POST['logout'])){
  // セッションを破棄
  $_SESSION = array();
  session_destroy();
  $message = 'ログアウトしました。';
  $inputType = 'hidden';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>ログアウト</title>
</head>

<body>
<?php echo('<p>'.$message.'</p>'); ?>

<form action="" enctype="multipart/form-data" method="POST">
  <input type="button" value="戻る" onClick="location.href='index.php'"/>
  <input type="<?php echo($inputType); ?>" name="logout" value="ログアウト">
</form>

</body>
</html>