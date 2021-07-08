<?php
$errors = [];
if($_POST){
    $id = null;
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
    $contents = htmlspecialchars($_POST["contents"], ENT_QUOTES, "UTF-8");
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
            "mysql:dbname=novels;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
        );
        //SQLを実行
        $regist = $pdo->prepare("INSERT INTO post(id, name, contents, created_at) VALUES (:id,:name,:contents,:created_at)");
        $regist->bindParam(":id", $id);
        $regist->bindParam(":name", $name);
        $regist->bindParam(":contents", $contents);
        $regist->bindParam(":created_at", $created_at);
        $regist->execute();
    }
}

//DB接続情報を設定
$pdo = new PDO(
    "mysql:dbname=novels;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);

//SQLを実行
$regist = $pdo->prepare("SELECT * FROM post");
$regist->execute();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>小説を続けよう</title>
	<link rel="stylesheet" type="text/css" href="sample.css">
	<script src="sample.js"></script>
</head>
<body>
<div class = "update">
    <h1>小説を続けよう</h1>
    <h3>表示される画像から物語を続けよう</h3>
</div>
<br>
    <img src = "pic/pic.png" id="randomimage" alt="ここに表示">
<br>
    <input type="button"  value="ランダム画像表示" onclick="getImage()">
<br>
<section>
    <h2>新規投稿</h2>
    <div id="error"><?php foreach($errors as $error){echo $error.'<br>';}?></div>
    <form action="index.php" method="post">
        名前 : <input type="text" name="name" value="名無しのライター"><br>
        投稿内容: <br>
        <textarea type="text" class = "noveltext" name="contents" value=""></textarea><br>
        <button type="submit">投稿</button>
    </form>
</section>


<section>
	<h2>投稿内容一覧</h2>
		<?php foreach($regist as $loop):?>
            <div class="novel">
                <!-- <div>No：<?php echo $loop['id']?></div> -->
                <!--<div>名前：<?php echo $loop['name']?></div> -->
                <div><?php echo $loop['contents']?></div>
                <!-- <div>投稿時間：<?php echo $loop['created_at']?></div> -->
                <!-- <div>------------------------------------------</div> -->
            </div>
            <br>
		<?php endforeach;?>
	
</section>
<p>
参考<br>
PHPで掲示板サイトを作ってみよう https://took.jp/bulletin-board/#i-7<br>
いらすとや　https://www.irasutoya.com/
</p>
</body>
</html>