<!--phpを記述しています-->
<?php
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

session_start();
//XSS脆弱性対策
$name = (string)filter_input(INPUT_POST, 'name'); // $_POST['name']
$text = (string)filter_input(INPUT_POST, 'text'); // $_POST['text']
$token = (string)filter_input(INPUT_POST, 'token');
$fp = fopen('data.csv', 'a+b');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && sha1(session_id()) === $token) {
    flock($fp, LOCK_EX);
    fputcsv($fp, [$_POST['name'], $_POST['comment']]);
    rewind($fp);
}
flock($fp, LOCK_SH);
while ($row = fgetcsv($fp)) {
    $rows[] = $row;
}
flock($fp, LOCK_UN);
fclose($fp);
?>
<!--ここまでphp-->

<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <title>test</title>
  <link rel="stylesheet" type="text/css" href="test.css">
  <script src="test.js"></script>
  </head>
<body>
  <div class='update'>
    <p>小説を続けよう</p>
  </div><br>
  <img src = "pic/pic.png" id="randomimage" alt="ここに表示されます。">
  <br>
  <input type="button" class = "btn-1" value="ランダム画像表示" onclick="getImage()">
  <br>
<!--コメント欄-->
  <h1>掲示板</h1>
  <section>
      <h2>新規投稿</h2>
      <form action="" method="post">
          名前: <input type="text" name="name" value=""><br>
          本文:<textarea name="comment" cols="30" rows="3" maxlength="80" wrap="hard" placeholder="80字以内で入力してください。"></textarea>
          <input type="submit" value="投稿">
          <input type="hidden" name="token" value="<?=h(sha1(session_id())) /*2*/ ?>">
      </form>
  </section>
<!--コメント欄終わり-->

<!--コメント一覧-->
  <section class = "toukou">
      <h2>投稿一覧</h2>
      <?php if (!empty($rows)): ?>
        <ul>
          <?php foreach ($rows as $row): ?>
        <li><?=$row[1]?> (<?=$row[0]?>)</li>
      <?php endforeach; ?>
        </ul>
      <?php else: ?>
      <p>投稿はまだありません</p>
      <?php endif; ?>
  </section>



</body>
</html>
