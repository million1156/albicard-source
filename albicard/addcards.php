<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//funwithalbi.xyz/style.css?version=<?php echo time(); ?>">
  <title>Add cards</title>
</head>
<body>
  <center>
    <button><a href='..'>Home</a></button>
    <button onClick='history.back()'>Back</button>
    <button><a href='./'>AlbiCard</a></button>
    <br/><br/>
    <form method='post'>
      <input type='text' name='d' placeholder="number"><br/>
      <input type='text' name='c' placeholder="cvv"><br/>
      <input type='text' name='e' placeholder="expiry"><br/>
      <input type='text' name='n' placeholder="name"><br/>
      <button type='submit'>Submit</button>
    </form>
    <br/><br/>
    <div id='apps'>
      <?php
        if (isset($_POST['d']))
        {
          $d = str_replace(" ", "", $_POST['d']);
          $c = $_POST['c'];
          $e = $_POST['e'];
          $n = $_POST['n'];
          $json = json_decode(file_get_contents("cards-250.json"), true);
          $file = fopen("cards-250.json", "w");
          array_push($json,
                     array("number" => $d,
                           "balance" => array(),
                           "paused" => false,
                           "cvv" => $c,
                           "expiry" => $e,
                           "name" => $n,
                           "history" => array(),
                           "id" => end($json)['id']+1
                          ));
          fwrite($file, json_encode($json));
          fclose($file);
        }

        $json = json_decode(file_get_contents("cards-250.json"), true);
  
        foreach (array_reverse($json) as $app) {
          $d = $app['paused'] ? 'yes' : 'no';
          $e = explode('/', $app['expiry']);
          $expiry = "M: ".$e[0]." Y: ".$e[1];

          $bal = $app['balance'];
          
          echo
          "<div>
            Number: ".$app['number']."<br/>
            Balance: $$bal<br/>
            Name: ".$app['name']."<br/>
            Expiry: $expiry<br/>
            Paused: $d<br/>
            CVV: ".$app['cvv']."
          </div><br/><br/>";
        }
      ?>
    </div>
  </center>
</body>
</html>