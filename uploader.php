<?php
function doUpload($sentUID){
    if(isset($_FILES['userfile'])){
        require('dbconnect.php');

        $count = 0;
        $fileCounter = 0;
        $filename='';

        for($i=0, $size = count($_FILES['userfile']['error']); $i < $size; ++$i){
            if($_FILES['userfile']['error'][$i] == UPLOAD_ERR_OK){
                //$userid='test';
                $userid = $sentUID; //固有のユーザID
                $uploadDir='./images/'.$userid.'/';
                $uploadPath=$uploadDir.$_FILES['userfile']['name'][$i];
                $fileCounter = $i + 1;
                
                //書き込み用変数
                $filenames=pathinfo($uploadPath);
                $id=bin2hex(random_bytes(3));
                //echo $id;
                $filename=$filenames["filename"];
                $descripsion=$_POST["descripsion"];
                $extname=$filenames["extension"];
                //echo $uploadPath;

                //データベースへの書き込み
                $count = $db->exec('INSERT INTO images SET id="'.$id.'", title="'.$filename.'",
                description="'.$descripsion.'",userid="'.$userid.'",extention="'.$extname.'"');

                //妥当性チェックと保管まで
                foreach ($_FILES["userfile"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES["userfile"]["tmp_name"][$key];
                        // basename() でひとまずファイルシステムトラバーサル攻撃は防げる
                        // ファイル名についてのその他のバリデーションも……
                        $name = basename($_FILES["userfile"]["name"][$key]);
                        move_uploaded_file($tmp_name, "$uploadDir/$name");
                    }
                }
                
            }else{
                //エラーが発生している
                if($_FILES['userfile']['error'] == UPLOAD_ERR_INI_SIZE){
                    echo "<div style='color:white;'>アップロードされたファイルが大きすぎます。</div>";
                }else if($_FILES['userfile']['error'] == UPLOAD_ERR_FORM_SIZE){
                    echo "<div style='color:white;'>アップロード上限は 10MB です。</div>";
                }else if($_FILES['userfile']['error'] == UPLOAD_ERR_NO_FILE){
                    echo "<div style='color:white;'>アップロードに失敗しました。</div>";  
                }else{
                    echo "<div style='color:white;'>原因不明のエラーが発生しています(ERR=".print_r($_FILES['userfile']['error'],true).")</div>";
                }
            }
        }
        
        if($count == 0){
            echo '<p>'.$filename.'はすでに登録されています。</p>';    
        }else{
            echo '<p>'.$fileCounter.'件のデータを挿入しました</p>';
        }
        
    }else{
        echo "ファイルがありません。";
    }
}
?>