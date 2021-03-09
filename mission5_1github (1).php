<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<?php
    //ＤＢ接続設定
	$dsn = 'mysql:dbname=*******;host=localhost';
	$user = '******';
	$password = '*******';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	    
    //データベース内にテーブルを作成
	$sql = "CREATE TABLE IF NOT EXISTS tbtest_51"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "password TEXT,"
	. "date TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	
    //変数指定
    $password = $_POST["password"];
    $date = date("Y年m月d日H時i分s秒");


    if (!empty($_POST["name"]) && !empty($_POST["comment"])) {
        $name=$_POST["name"];
        $comment = $_POST["comment"];
        if (!empty($_POST["editNO"])) {
            //編集機能
        //   $date = date("Y年m月d日H時i分s秒");
           $editNO=$_POST["editNO"];
           $id = $editNO; 
    	   $sql = 'UPDATE tbtest_51 SET name=:name,comment=:comment,date=:date WHERE id=:id';
    	   $stmt = $pdo->prepare($sql);
    	   $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    	   $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    	   $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    	   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	   $stmt->execute();
        } elseif ($_POST["password"] = "program") {
            //新規投稿機能 
            // $date = date("Y年m月d日H時i分s秒"); 
    	    $sql = $pdo -> prepare("INSERT INTO tbtest_51 (name, comment, date, password) 
    	                          VALUES (:name, :comment, :date, :password)");
    	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    	    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    	    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    	    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    	    $sql -> execute();
            
        }
    } elseif (!empty($_POST["delete"]) && $_POST["deletepass"] = "program") {
        $delete=$_POST["delete"];
        $deletepass=$_POST["deletepass"];
        if($delete != $id){
            $id = $delete;
            $sql = 'delete from tbtest_51 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    } elseif (!empty($_POST["edit"])) {
        $edit=$_POST["edit"];
        $editpass=$_POST["editpass"];
        //編集対象番号が空ではなかったら
        $sql = 'SELECT * FROM tbtest_51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //以下で編集したい投稿を探す。
            if(!empty($_POST["editpass"])){//編集パスが空ではない
	            if($row['id']==$edit){ //該当投稿ならば
                        $editnum=$row['id'];
                        $editname=$row['name'];
                        $editcomment=$row['comment'];
	            }
            }    
        }
        
    }


	//表示
	$sql = 'SELECT * FROM tbtest_51';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
	}

?>

   <form action=""method="post">
        <input type="text" name="name" placeholder="名前" value="<?php echo $editname;?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment;?>">
        <input type="hidden" name="editNO" placehpilder="編集番号" value="<?php echo $editnum;?>"><br>
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="submit"><br>
    <br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="deletepass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"><br>
    <br>
        <input type="text" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="hensyu" value="編集"><br>
   </form>
    
</body>
</html>