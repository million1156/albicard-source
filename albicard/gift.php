<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//www.funwithalbi.xyz/style.css">
  <title>bbye$</title>
  <meta property="og:type" content="website">
  <meta property="og:title" content="Someone gifted you money!">
  <meta property="og:description" content="Claim this gift and get money!">
</head>
<body>
  <center>
    <button><a href='..'>Home</a></button>
    <button onClick='history.back()'>Back</button>
    <button><a href='./'>AlbiCard</a></button>
    <br/><br/>
    <?php
      if (isset($_GET['code']))
      {
        $code = $_GET['code'];
  
        if ($code == "9D0g0xvb")
        {
          if (file_get_contents("clm.txt") == 1)
          {
            echo "<h1>Gift already claimed!</h1>";
          }
          else
          {
            if (isset($_SESSION['loggedin']))
            {
              $file = fopen("clm.txt", "w");
              fwrite($file, "1");
              fclose($file);
              $json = json_decode(file_get_contents("cards-250.json"), true);
              $file = fopen("cards-250.json", "w");
              $count = 0;
              foreach ($json as $card)
              {
                if (str_replace(" ", "", $card['number'] == $_SESSION['card']['number'])
                {
                  $json[$count]['balance'] = $json[$count]['balance'] + 100;
                }
                $count++;
              }
              fwrite($file, $json);
              fclose($file);
              echo "<h1>Successfully claimed a $100 gift!</h1>";
            }
            else
            {
              echo "<h1>You must be logged in to claim this gift!</h1>";
            }
          }
        }
        else
        {
          echo "<h1>Invalid Gift Code!</h1>";
        }
      }
    ?>
  </center>
</body>
</html>