function getImage(){
  //画像ファイル名を配列で保持
  var arr = ['pic/pic1.png', 'pic/pic2.png', 'pic/pic3.png'];

  //ランダムで画像ファイルを取得して表示する
  var obj = document.getElementById("randomimage");
  var a = Math.floor(Math.random() * arr.length);
  obj.src = arr[a];
}
