<?php 
//mission_4-1掲示板 
 
 
//データベース接続 
$dsn = データベース名; 
$user = ユーザー名; 
$password = パスワード; 
$pdo = new PDO($dsn,$user,$password); 
 
//例外を教える 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
 
 
 
//データ受け取り 
$name = $_POST['name']; 
$comment = $_POST['comment']; 
$password = $_POST['password']; 
$remove_num = $_POST['remove_num']; 
$remove_flag = $_POST['remove_flag']; 
$edit_num = $_POST['edit_num']; 
$edit_flag = $_POST['edit_flag']; 
//現在の時刻の習得 
$time = date("Y/m/d H:i:s"); 
 
 
//投稿番号作成機能 テーブルの行数を数えて、＋１する 
 
//行数の取得をするsql 
$sql = "SELECT COUNT(*) FROM okabetomoya"; 
 
//テーブルの行数を取得 
$results = $pdo -> query($sql); 
 
//実行結果から行数取り出し 
$num = $results -> fetchColumn(); 
$num++; 
//$numが投稿番号となる 
if($name != '' && $comment != ''){ 
 if($password != '' && $edit_flag == ''){ 
 //テーブルにデータを入力 
 $sql = "INSERT INTO okabetomoya(num,name,comment,time,password) VALUES($num,'$name','$comment','$time','$password')"; 
 
 $rezult = $pdo -> query($sql); 
 } 
} 
?> 
 
 
<?php 
//削除機能 
if($remove_num != ''){  
//パスワード読み出し 
$sql = "SELECT * FROM okabetomoya WHERE num = $remove_num"; 
 //パスワード判定 
 try{ 
 $end = $pdo -> query($sql); 
 }catch(Exception $e){ 
 var_dump($e -> getMessage()); 
 } 
 foreach($end as $row){ 
 $pass = $row['password']; 
 } 
 
 if($pass == $password){ 
 
 
 //指定した番号の行を消すsql作成 
 $sql = "delete from okabetomoya where num = $remove_num"; 
 
 //sql実行 
 $end = $pdo -> query($sql); 
 } 
} 
 
 
 
 
//新たに書く編集機能 
 
 
//編集フラグがあるか確認 
if($edit_flag != ''){ 
  
 //名前とコメントの空白判定 
 if($name != '' && $comment != ''){ 
 
 //パスワードを判定するためにDBからパスワードを取り出す 
 //データを取得するsql文 
 $sql = "SELECT * FROM okabetomoya WHERE num = $edit_flag"; 
 
 //sql実行 
 $end = $pdo ->query($sql); 
 
  //DBのパスワードを取得 
  foreach($end as $row){ 
 
  $pass = $row['password']; 
 
  } 
 
  //入力されたパスワードとDBが同じなら編集する 
  if($pass == $password){ 
   
  //編集するsql文 
  $sql = "update okabetomoya set name='$name',comment='$comment' where num = $edit_flag"; 
  $result = $pdo -> query($sql); 
   
  } 
 
 } 
 
//フラグをオフにする 
$edit_flag = ''; 
 
} 
 
//新しくここから編集フラグを書く 
 
//編集指定番号空白判定 
if(strval($edit_num) !=''){ 
//入力フォームに名前とコメントを戻すためのsql 
$sql = "SELECT * FROM okabetomoya WHERE num = $edit_num"; 
 
//sql実行 
$end = $pdo -> query($sql); 
 
//名前とコメントを結果から読み出すためのforeach文 
foreach($end as $row){ 
 
   
 //編集データの取得  
    $edit_name = $row['name']; 
    $edit_comment = $row['comment'];  
 
 } 
 
$edit_flag = $edit_num; 
 
 
} 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
//テーブルの中身を取得 
$sql = "SELECT * FROM okabetomoya"; 
//sql実行 テーブルの中身が返ってくる 
$end = $pdo -> query($sql); 
//テーブルを表示 
foreach($end as $row){ 
//変数の内容を表示できる 
echo $row['num']; 
echo $row['name']; 
echo $row['comment']; 
echo $row['time']; 
 
//改行 
echo "<br>"; 
//線を引く 
echo "<hr>"; 
} 
 
 
 
 
?> 
 
 
<!DOCTYPE html> 
<html lang="en","ja"> 
 
<head> 
 <meta charset="UTF-8"/> 
 
<title>misson_4-1.php</title> 
</head> 
 
<body> 
掲示板 
 
<?php 
 
 
//機能を使用したときのメッセージ表示 
 
//メッセージがあれば表示 
if($message != ""){ 
echo"$message"; 
$message = ""; 
} 
 
 
//データベースのデータ表示機能 
 
//テーブルからデータを取り出すsql 
$sql = "SELECT * FROM okabetomoya id num";  
 
 
 
try{  
$results =  $pdo -> query($sql); 
 
 //繰り返し変数 
 
 foreach($results as $row){ 
 
 //投稿番号、名前、コメント、日時を表示 
 
 echo $row['id'].'：';  
      
 echo $row['name'].'：';  
      
 echo $row['time']."<br>";  
 //コメントの最後の線は実践にするためのif文 
 echo "<div class=\"comment\">".$row['comment']."</div>"; 
  
  
 $i++;
 } 
 
 
}catch(Exception $e){  
var_dump($e->getMessage());  
} 
 
?> 
 
<form action="" method="post"> 
 
<!--名前入力欄--> 
名前：<input type="text" name="name" value="<?php echo $edit_name ?>" ><br> 
コメント：<input type="text" name="comment" value="<?php echo $edit_comment ?>" ><br> 
パスワード：<input type="password" name="password"><br> 
 
消去番号：<input type="text" name="remove_num"> 
 
編集番号： 
<input type="text" name="edit_num" > 
<!--編集モードフラグ--> 
<input type="hidden" name="edit_flag"value="<?php echo $edit_flag ?>"><br> 
 
<input type="submit"value="送信"><br> 
</form> 
 
</body> 
</html>