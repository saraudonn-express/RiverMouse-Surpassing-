<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>mission_4-1</title>
</head>
<body>
<?php
$dsn='データベース名';
$user='ユーザー名';
$password= "パスワード";
$pdo=new PDO($dsn,$user,$password);

//編集の際テキストボックスに表示させるための処理
	//もし編集ボタンが押されたら
	if (isset($_POST['edit'])){
		//もし編集のテキストボックスに何か入っていたら
		if (isset($_POST["editnum1"])){
			$edit = $_POST["editnum1"];
			$editpass=$_POST["pass3"];
			
			$sql = 'SELECT * FROM m4tbl where id = :edit and pass = :pass3';
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindParam(':edit', $edit, PDO::PARAM_INT);
			$stmt -> bindParam(':pass3', $editpass, PDO::PARAM_STR);
			$stmt -> execute();
		

			if($row = $stmt -> fetch()){
				//ここの変数は自分で決めること（例:$text0など）
				//配列に入っている0～2番を変数に格納しておく
					$hennsuu0 = $row["id"];
					$hennsuu1 = $row["name"];
					$hennsuu2 = $row["comment"];
					$hennsuu3 = $row["pass"];
			}
		}
	}

?>
<form action="mission_4-1.php" method ="post">
	<!--/* valueに上で作った変数をechoでそれぞれ表示させる（phpで囲むこと）
	↑HTML上では変数を使うことができないためPHPで記述する必要があるため*/ -->
	名前<br><input type = "text" name ="text1" placeholder = "名前" value="<?php echo $hennsuu1; ?>"><br>
	コメント<br><input type = "text" name ="text2" placeholder = "コメント" value="<?php echo $hennsuu2; ?>"><br>
	<input type ="hidden" name = "editnum2" value="<?php echo $hennsuu0; ?>">
	<input type ="password" name ="pass1" placeholder="パスワード" value="<?php echo $hennsuu3; ?>">
	<input type = "submit" name = "add" value ="送信"><br><br>
	
	削除<br><input type = "text" name ="del" placeholder = "投稿番号"><br>
		<input type ="password" name="pass2" placeholder="パスワード">
		<input type = "submit" name = "delete1" value ="削除"><br>

	編集<br><input type = "text" name ="editnum1" placeholder = "投稿番号"><br>
		<input type ="password" name="pass3" placeholder="パスワード">
		<input type = "submit" name = "edit" value ="編集">
<!--/* submit(送信ボタン)にはnameで名前をつけた方がいろいろと便利 (編集だったらname="edit"みたいな感じ） */-->
	<br>
	<hr>
</form>

<?php
	//　編集
	//追加のところの送信ボタンが押されたとき
	if (isset($_POST["add"])){
		//hiddenで隠されたテキストボックスになんかしら入っているときは編集モード
		if ($_POST["editnum2"]){
			$pass =$_POST['pass1'];
			$date = date('Y/m/d H:i');
			$edit0 = $_POST["editnum2"];
			$edit1 = $_POST["text1"];
			$edit2 = $_POST["text2"];
			$sql = 'update m4tbl set name = :edit1,comment = :edit2 where id = :edit0 AND pass = :pass1';
			$stmt = $pdo -> prepare($sql);
			$stmt->bindParam(':edit0', $edit0, PDO::PARAM_STR);
			$stmt->bindParam(':edit1', $edit1, PDO::PARAM_STR);
			$stmt->bindParam(':edit2', $edit2, PDO::PARAM_STR);
			$stmt->bindParam(':pass1', $pass, PDO::PARAM_STR);		
			$stmt -> execute();

			
		//hiddenのテキストボックスに何にも入っていないときは追加する
		}else{
//　追加

			$date = date('Y/m/d H:i');
			$text1 = $_POST['text1'];
			$text2 = $_POST['text2'];
			$pass  = $_POST['pass1'];

			if($_POST[text1] != "" && $_POST[text2] != "" && $_POST[pass1] !=""){
				$sql = $pdo -> prepare("INSERT INTO m4tbl (name,comment,date,pass) VALUES(:name, :comment, :date, :pass)");
				$sql -> bindParam(':name', $text1, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $text2, PDO::PARAM_STR);
				$sql -> bindParam(':date', $date, PDO::PARAM_STR);
				$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

				$sql -> execute();

			}
		}
	}
?>

<?php	
	//　削除
	if ($_POST["delete1"]){
		if (isset($_POST["del"])){		
			$delete = $_POST["del"];
			$del = file($filename);
			$delpass=$_POST["pass2"];
			$sql = 'DELETE FROM m4tbl where id = :delete AND pass = :pass2';
			$stmt = $pdo -> prepare($sql);
			$stmt->bindParam(':delete', $delete, PDO::PARAM_STR);
			$stmt->bindParam(':pass2', $delpass, PDO::PARAM_STR);
			$stmt->execute();

		}
	}

?>	

<?php
	//	表示コード
$sql = 'SELECT id,name,comment,date FROM m4tbl order by id asc';
$results = $pdo -> query($sql);
foreach ($results as $row){
 //$rowの中にはテーブルのカラム名が入る
 echo $row['id'].',';
 echo $row['name'].',';
 echo $row['comment'].',';
 echo $row['date'].'<br>';
}

?>

</body>
</html>