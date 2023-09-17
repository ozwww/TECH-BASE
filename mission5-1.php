<?php
error_reporting(E_ALL & ~E_NOTICE);
 // DB接続設定
    $dsn = 'mysql:dbname=XXXdb;host=localhost';
    $user = 'XXXUSER';
    $password = "XXXPASSWORD";
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 
//テーブルドロップ
  // $sql = 'DROP TABLE tech_base';
  // $stmt = $pdo->query($sql);

//テーブルの作成 
      $sql = "CREATE TABLE IF NOT EXISTS tech_base"
    ." (id INT AUTO_INCREMENT PRIMARY KEY, name char(32),comment TEXT, date char(32),pass char(32));";
    $stmt = $pdo->query($sql);

//データ(レコード)の登録 
       $name = $_POST["name"];
       $com=$_POST["com"];
       $date = new DateTime();
       $date = $date->format('Y-m-d H:i:s');
       $checkedinum=$_POST["checkedinum"];
        $pass=$_POST["pass"];
     if( !empty( $name)&& !empty($com)&&empty($checkedinum)&&!empty($pass)){
    $sql = "INSERT INTO tech_base (name, comment, date, pass) VALUES (:name, :comment,:date,:pass)";
    $stmt = $pdo->prepare($sql); 
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $com, PDO::PARAM_STR);
      $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->execute();
        }
 
//データレコードの内容編集 選択機能　
  $edit=$_POST["edit"];
  $edipass=$_POST["edipass"];
 if(!empty($edit)&&!empty($edipass)){
     $sql = 'SELECT * FROM tech_base';
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
      foreach ($results as $row){
          if($row['id'] == $edit&&$row['pass']==$edipass){
        $ediname = $row['name'];
        $edicom = $row['comment'];
        $newpass=$row['pass'];
        $edinum = $row['id'];     
        }
      }
 }
//内容上書き機能
       $checkedinum=$_POST["checkedinum"];
        if(!empty($checkedinum)&&!empty($name)&&!empty($com)&&!empty($pass)){
         
    $sql = 'UPDATE tech_base SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
    $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $com, PDO::PARAM_STR);
      $stmt->bindParam(':id',$checkedinum, PDO::PARAM_INT);
      $stmt->bindParam(':pass',$pass, PDO::PARAM_INT);
      $stmt->execute();
        }

//データレコードの削除
      $delete=$_POST["delete"];
      $delpass=$_POST["delpass"];
      if(!empty($delete)&&!empty($delpass)){
          $sql = 'SELECT * FROM tech_base';
     $stmt = $pdo->query($sql);
     $results = $stmt->fetchAll();
      foreach ($results as $row){
          if($row['id'] == $delete&&$row['pass']==$delpass){
    $sql2 = 'delete from tech_base where id=:id';
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id',$delete, PDO::PARAM_INT);
    $stmt2->execute();
    }
      }
      }
     
//データレコードの抽出・表示 
    $sql = 'SELECT * FROM tech_base';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].'<br>';
       echo $row['date'].'<br>';
    echo "<hr>";
    }

//テーブルドロップ
if(isset($_POST["reset"])){
    $sql = 'DROP TABLE tech_base';
   $stmt = $pdo->query($sql);
}
?>
    
 <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
 
    <form action="" method="post">
    <button type="submit" name="reset" >リセット</button> <?php echo "テーブルレコードをすべて削除";?><br><br>
        <input type="text" name="name"placeholder="名前"value=<?php echo $ediname;?>>
        <input type="text" name="com"placeholder="コメント"value=<?php echo $edicom;?>>
        <input type="password" name="pass"placeholder="パスワードを設定"value=<?php echo $newpass;?>>
        <input type="submit" name="submit"><br>
     <form action="" method="post">
         <input type="number" name="delete"placeholder="削除番号">
         <input type="password" name="delpass"placeholder="パスワード">
         <input type="submit" name="submit"value="削除"><br>
     <form action="" method="post">
         <input type="text" name="edit"placeholder="編集番号">
         <input type="password" name="edipass"placeholder="パスワード">
         <input type="submit" name="submit"value="編集"><br>
        <input type="hidden" name="checkedinum" value="<?php echo $edinum;?>">
       
    </form>
</body>
</html>