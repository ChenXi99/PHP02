<?php
// funcs.phpを読み込む
require_once('funcs_kadai.php');

//1.  DB接続します
try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','root');
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//２．SQL文を用意(データ取得：SELECT)
$stmt = $pdo->prepare("SELECT * FROM beer_table ORDER BY `beer_table`.`rate` DESC");

//3. 実行
$status = $stmt->execute();

//4．関数定義＆データ表示
$one = 0;
$two = 0;
$three = 0;
$four = 0;
$five = 0;

if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    // .= とは、一つずつ足すという意味（not 上書き）
    $name = h($result['name']);
    $rate = h($result['rate']);
    $memo = h($result['memo']);
    $url = h($result['url']);
    $date = h($result['date']);

    $data .= "<tr><td>";
    $data .= "<a href=".$url.">$name</a>";
    $data .= "</td><td>";
    $data .= $rate;
    $data .= "</td><td>";
    $data .= $memo;
    $data .= "</td><td>";
    $data .= $date;
    $data .= "</td></tr>";

    if($rate == "1"){
      $one ++;
    }if($rate == "2"){
        $two ++;
    }if($rate == "3"){
      $three ++;
    }if($rate == "4"){
      $four ++;
    }if($rate == "5"){
      $five ++;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Database</title>
<link rel="stylesheet" href="./css/database.css"> 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/css/theme.default.min.css">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav>
    <div>
      <a class="navbar-brand" href="../17_chen_mapAPI/17_chen_map.html">🍺 My CraftBeer Map 🍺</a>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div class="wrap">
    <div class="wrap1">
      <table border='1' id="list">
        <tr><td class="t">Name</td><td class="t">Rate</td><td class="t">Memo</td><td class="t">Date</td></tr>
        <?= $data ?>
      </table>
    </div>
    <div class="wrap2" style="width:400px; ">
        <canvas id="myChart" height="300px"></canvas>
    </div>
</div>

<!-- Main[End] -->

<script>
  //phpからjavascriptに変数を渡す
  let one = <?= h($one) ?>;
  let two = <?= h($two) ?>;
  let three = <?= h($three) ?>;
  let four = <?= h($four) ?>;
  let five = <?= h($five) ?>;
</script>

<script>
        const labels = [
            '1',
            '2',
            '3',
            '4',
            '5',
        ];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Rate',
                data: [one, two, three, four, five],
                backgroundColor: '#FFFF99',
                borderColor: 'gold',
                borderWidth: 3
            }]
        };
        
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };
        // </block:config>
        module.exports = {
            actions:[],
            config: config,
        };
    </script>
    <script>
      // === include 'setup' then 'config' above ===
          var myChart_A = new Chart(
              document.getElementById('myChart'),
              config
          );
    </script>

<!-- <script>
    $(function() {
    $('#list').tablesorter();
});
</script> -->

</body>
</html>