<?php
$errors = [];
if($_POST){
    $id = null;
    $name = $_POST["name"];
    $contents = $_POST["contents"];
    if(!$name){
        $errors[] .= "名前が入力されていません";
    }
    if(!$contents){
        $errors[] .= "小説を書いてください";
    }
    if(!$errors){
        date_default_timezone_set('Asia/Tokyo');
        $created_at = date("Y-m-d H:i:s");
       //データベースに接続   
        $pdo = new PDO(
            "mysql:dbname=sample;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
        );
        //SQLを実行。
        $regist = $pdo->prepare("INSERT INTO post(id, name, contents, created_at) VALUES (:id,:name,:contents,:created_at)");
        $regist->bindParam(":id", $id);
        $regist->bindParam(":name", $name);
        $regist->bindParam(":contents", $contents);
        $regist->bindParam(":created_at", $created_at);
        $regist->execute();
    }
}

//DB接続情報を設定します。
$pdo = new PDO(
    "mysql:dbname=sample;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);

//SQLを実行。
$regist = $pdo->prepare("SELECT * FROM post order by created_at DESC limit 20");
$regist->execute();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>小説を続けよう</title>
	<link rel="stylesheet" type="text/css" href="test.css">
</head>
<body>
<h1>表示されている画像から物語を書こう</h1>
<section>
    <h2>新規投稿</h2>
    <div id="error"><?php foreach($errors as $error){echo $error.'<br>';}?></div>
    <form action="index.php" method="post">
        名前 : <input type="text" name="name" value=""><br>
        投稿内容: <input type="text" name="contents" value=""><br>
        <button type="submit">投稿</button>
    </form>
</section>


<section>
	<h2>投稿内容一覧</h2>
		<?php foreach($regist as $loop):?>
			<div>No：<?php echo $loop['id']?></div>
			<div>名前：<?php echo $loop['name']?></div>
			<div>小説内容：<?php echo $loop['contents']?></div>
			<div>投稿時間：<?php echo $loop['created_at']?></div>
			<div>------------------------------------------</div>
		<?php endforeach;?>
	
</section>
</body>
</html>