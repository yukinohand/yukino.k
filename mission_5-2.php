
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php
//データベースの接続
	$dsn = 'データベース名';
	$user = 'ユーザ名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


//データベースにテーブルを作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest_d"
	."("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass char(25),"
	. "date char(25)"
	.");";
	$stmt = $pdo->query($sql);


//テーブル一覧を表示するコマンドを使って作成が出来たか確認する。
	$sql ="SHOW TABLES";
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";

//テーブルの中身を確認するコマンドを使って、意図した内容のテーブルが作成されているか確認する。
	$sql ="SHOW CREATE TABLE tbtest_d";
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";

//作成したテーブルに、insertを行ってデータを入力する。
if(!empty($_POST["name"])){
if(!empty($_POST["comment"])){
if(!empty($_POST["pass"])){//パス１空でない
	if(!empty($_POST["hidden"])){//隠れフォーム空でないときつまり編集投稿
		$id=$_POST["hidden"];
		$name=$_POST["name"];
		$comment=$_POST["comment"];
		$pass=$_POST["pass"];
		$date=date("Y/m/d H:i:s");
		$sql_1="update tbtest_d set name=:name, comment=:comment, date=:date, pass=:pass where id=:id";//編集したい箇所
		$stmt = $pdo->prepare($sql_1);
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);//””の間はテーブル名,の後ろはそこに入れたい関数
		$stmt->bindParam(":name", $name, PDO::PARAM_STR);
		$stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
		$stmt->bindParam(":date", $date, PDO::PARAM_STR);
		$stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
		$stmt->execute();
	}else{//隠れフォームが空の時つまり新規投稿
		$name =$_POST["name"];
		$comment =$_POST["comment"];
		$pass=$_POST["pass"]; //好きな名前、好きな言葉は自分で決めること
		$date=date("Y/m/d H:i:s");
		$sql = $pdo -> prepare("INSERT INTO tbtest_d (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
		$sql -> bindParam(":name", $name, PDO::PARAM_STR);
		$sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
		$sql -> bindParam(":date", $date, PDO::PARAM_STR);
		$sql -> bindParam(":pass", $pass, PDO::PARAM_STR);
		$sql -> execute();
	}
}
}
}

if(!empty($_POST["rewrite"])){//編集対象の取得
	if(!empty($_POST["pass_3"])){//パス１空でない
	$pass_3=$_POST["pass_3"];
	$rewrite=$_POST["rewrite"];
		$sql= "SELECT * FROM tbtest_d where id=$rewrite";
		$stmt= $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $row){	
		$password_3=$row["pass"];
			if($pass_3==$password_3){
			$ediname=$row["name"];
			$edicomment=$row["comment"];
			$edipass=$row["pass"];
			}
		}
	}
}

	
//入力したデータをdeleteによって削除する。削除できているかはselectで確認すること。
if(!empty($_POST["delete"])){

	if(!empty($_POST["pass_2"])){//パス１空でない
	$pass_2=$_POST["pass_2"];
	$id=$_POST["delete"];
	$sql= "SELECT * FROM tbtest_d where id=$id";
	$stmt= $pdo->query($sql);
	$results = $stmt->fetchAll();
		foreach($results as $row){	
		$password_2=$row["pass"];
			if($pass_2==$password_2){
			$sql = 'delete from tbtest_d where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			}
		}
	}
}
	

?>

<body>

	<form action="mission_5-1.time.php" method="POST" >
	名前：<input type="text" name="name" 
	value="<?php if(isset($ediname)){echo "$ediname";} ?>"
	size="30">

	<br>
	コメント：<input type="text" name="comment" 
	value="<?php if(isset($edicomment)){echo "$edicomment";} ?>"
	size="30">

	<input hidden type="text" name="hidden" 
	value="<?php if(isset($rewrite)){echo "$rewrite";} ?>"
	size="30">


	<br>パスワード：<input type="text" name="pass" 
	value="<?php if(isset($edipass)){echo "$edipass";} ?>"
	size="30">
	
	<input type="submit"  value="送信" > 
	</form>

	<form action="mission_5-1.time.php" method="POST" >
	削除番号：<input type="text" name="delete" size="30">
	<br>パスワード：<input type="text" name="pass_2" size="30">
	<input type="submit"  value="削除" > 
	</form>
	
	<form action="mission_5-1.time.php" method="POST" >
	編集番号：<input type="text" name="rewrite" size="30">
	<br>パスワード：<input type="text" name="pass_3" size="30">
	<input type="submit"  value="編集" >
	</form>
</body>


<?php

//入力したデータをselectによって表示する
	$sql = "SELECT * FROM tbtest_d";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].',';
		echo $row['pass'].'<br>';
	echo "<hr>";
	}
?>
</html>