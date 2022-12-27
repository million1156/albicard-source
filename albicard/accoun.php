<?php
session_start();

if (!isset($_SESSION['loggedin']))
{
  header("Location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//www.funwithalbi.xyz/style.css">
  <title>Manage your account</title>
</head>
<body>
  <center>
    <script>
      var smoled = true;
      function smol(uid, sid)
      {
        if (smoled == true)
        {
          document.getElementById(uid).style.display = null;
          document.getElementById(sid).innerHTML = "Show less";
        }
        else
        {
          document.getElementById(uid).style.display = "none";
          document.getElementById(sid).innerHTML = "Show more";
        }
        smoled = !smoled;
      }
    </script>
    
    <h1>Manage your account</h1>
    <button><a href='..'>Home</a></button>
    <button onClick='history.back()'>Back</button>
    <button><a href='./'>AlbiCard</a></button>
    <form method='post'>
      <button type='submit' name='logout' value='yeah'>Logout</button>
    </form>
    <?php
      if (isset($_POST['logout']))
      {
        session_destroy();
        echo "<script>location.reload();</script>";
      }
    ?>
    <br/><br/><br/>
    <?php
      function inthing($key, $val, $array, $nocase=false, $rtnum=false)
      {
        $count = 0;
        foreach ($array as $item)
        {
          if (isset($item[$key]))
          {
            if ($nocase == false)
            {
              if ($item[$key] == $val)
              {
                if ($rtnum) { return $count; };
                return $item;
              }
            }
            else
            {
              if (strtolower($item[$key]) == strtolower($val))
              {
                if ($rtnum) { return $count; };
                return $item;
              }
            }
          }
          $count++;
        }
        return false;
      }

      if (isset($_POST['to']))
      {
        $json = json_decode(file_get_contents("cards-250.json"), true);
        $to = inthing("name", $_POST['to'], $json, true)['number'];
        $from = $_SESSION['card']['number'];
        
        $amt = $_POST['amt'];
        $cvv = $_POST['cvv'];
      
        $code = 200;
        $fee = $amt * 0.10;
      
        $json = json_decode(file_get_contents("cards-250.json"), true);
        $count = 0;
        foreach ($json as $thing)
        {
          $json[$count]['number'] = str_replace(" ", "", $json[$count]['number']);
          $count++;
        }
        $it = inthing("number", str_replace(" ", "", $from), $json);
        $not = inthing("number", str_replace(" ", "", $to), $json);
      
        $fro = $it['balance'] || 0;
      
        if ($not != false && $it != false)
        {
          if (intval($amt) - $fee > 0.50)
          {
            if ($it['paused'] != true)
            {
              if ($fro >= $amt)
              {
                if ($cvv == $it['cvv'])
                {
                  if ($it['balance'] >= $amt)
                  {
                    $file = fopen("cards-250.json", "w");
                    $count = 0;
                    $things = 0;
                    $albi = inthing("id", 6, $json, false, true);
                    foreach ($json as $card)
                    {
                      if ($card['number'] == str_replace(" ", "", $to))
                      {
                        $json[$count]['balance'] = $json[$count]['balance'] + $amt - $fee;
                        array_push($json[$count]['history'], array("type" => "receive", "from" => $it['name'], "amount" => $amt - $fee));
                        $json[$albi]['balance'] = $json[$count]['balance'] + $fee;
                        array_push($json[$albi]['history'], array("type" => "receive", "from" => "Fee Collection", "amount" => $fee));
                      }
                      elseif ($card['number'] == str_replace(" ", "", $from))
                      {
                        $json[$count]['balance'] = $json[$count]['balance'] - $amt;
                        $json[$count]['balance'] = $json[$count]['balance'] - $fee;
                        array_push($json[$count]['history'], array("type" => "send", "to" => $not['name'], "amount" => $amt - $fee));
                      }
                      $count++;
                    }
                    if ($things < 2 && $things > 0)
                    {
                      $code = 201;
                    }
                    else
                    {
                      $code == 300;
                    }
                    fwrite($file, json_encode($json));
                    fclose($file);
                  }
                  else
                  {
                    $code = 2;
                  }
                }
                else
                {
                  $code = 403;
                }
              }
              else
              {
                $code = 9;
              }
            }
            else
            {
              $code = 1;
            }
          }
          else
          {
            $code = 69;
          }
      }
        else
        {
          $code = 404;
        }
        $js = array(
          "amount" => $amt,
          "fee" => $fee,
          "code" => $code,
        );
      }

      if (isset($_POST['p']))
      {
        $json = json_decode(file_get_contents("cards-250.json"), true);
        $file = fopen("cards-250.json", "w");
        $count = 0;
        foreach ($json as $card)
        {
          if (str_replace(" ", "", $card['number']) == str_replace(" ", "", $_SESSION['card']['number']))
          {
            $json[$count]['paused'] = !$json[$count]['paused'];
          }
          $count++;
        }
        fwrite($file, json_encode($json));
        fclose($file);
      }

      $json = json_decode(file_get_contents("cards-250.json"), true);
      $count = 0;
      foreach ($json as $card)
      {
        if (str_replace(" ", "", $card['number']) == str_replace(" ", "", $_SESSION['card']['number']))
        {
          $json[$count]['paused'] = !$json[$count]['paused'];
        }
        $count++;
      }
      $app = inthing("number", $_SESSION['card']['number'], $json);

      $e = explode('/', $app['expiry']);
      $expiry = "M: ".$e[0]." Y: ".$e[1];
      $ts = "";

      $owncar = 0;
      $owncard = 0;
      foreach ($json as $card)
      {
        if (str_replace(" ", "", $card['number']) == str_replace(" ", "", $_SESSION['card']['number']))
        {
          $owncard = $owncar;
        }
        $owncar++;
      }

      if (isset($_POST['kys']))
      {
        $json = json_decode(file_get_contents("cards-250.json"), true);
        $file = fopen("cards-250.json", "w");
        $count = 0;
        foreach ($json as $card)
        {
            $json[$count]['history'] = array();

          $count++;
        }
        fwrite($file, json_encode($json));
        fclose($file);
      }

      $tss = 0;
      $buttons = 0;
      $uid = 0;
      foreach (array_reverse($json[$owncard]['history']) as $trs)
      {
        if ($tss < 5)
          {
            if ($trs['type'] == "receive")
            {
              $ts .= "<div><span style='border:2px solid green;border-radius:5px;'>Received - From <b>".$trs['from']."</b> - Amount: $".$trs['amount']."</span></div><br/>";
            }
            if ($trs['type'] == "send")
            {
              $ts .= "<div><span style='border:2px solid red;border-radius:5px;'>Sent - To <b>".$trs['to']."</b> - Amount: $".$trs['amount']."</span></div><br/>";
            }
        }
        elseif ($tss > 5)
        {
          if ($buttons < 1)
          {
            $uid = rand(0, 98561);
            $ts .=  "<div id='$uid' style='display:none;'>";
          }
            if ($trs['type'] == "receive")
            {
              $ts .= "<div><span style='border:2px solid green;border-radius:5px;'>Received - From <b>".$trs['from']."</b> - Amount: $".$trs['amount']."</span></div><br/>";
            }
            if ($trs['type'] == "send")
            {
              $ts .= "<div><span style='border:2px solid red;border-radius:5px;'>Sent - To <b>".$trs['to']."</b> - Amount: $".$trs['amount']."</span></div><br/>";
            }
          $buttons++;
        }
        $tss++;
      }
      if ($buttons > 0)
      {
        $sid = rand(0, 98561);
        $ts .= "</div>";
        $ts .= "<button onclick='smol($uid, $sid)' id='$sid'>Show more</button>";
      }

      $d = $json[$owncard]['paused'] == true ? 'no' : 'yes';

      if ($ts == "")
      {
        $ts = "None<br/><br/>";
      }

      echo
      "<div>
        Number: ".$app['number']."<br/><br/>
        Balance: $".$app['balance']."<br/><br/>
        Name: ".$app['name']."<br/><br/>
        Expiry: $expiry<br/><br/>
        Paused: $d<br/><br/>
 <br/><br/>
  <form method='post'><button type='submit' name='p' value='mhm'>Pause</button></form>
<br/><br/>
Send:
<form method='post'>

<input type='text' name='to' placeholder='To (Name)'> <br/><br/>
<input type='password' pattern='\d{3}' name='cvv' placeholder='CVV (Your card)'> <br/><br/>
<input type='text' name='amt' placeholder='Amount (To send)'> <br/><br/>

<input type='submit'><br/><br/>

";
if (isset($_POST['to']))
{
  if ($js['code'] == 200)
  {
    echo "<p id='yeah' style='color:green;display:none;'>Payment Completed Successfully!</p>";
  }
  elseif($js['code'] == 2)
  {
    echo "<p id='yeah' style='color:red;display:none;'>Payment was not completed... (Not enough money)</p>";
  }
  else
  {
    echo "<p id='yeah' style='color:red;display:none;'>Payment was not completed... (Code ".$js['code'].")</p>";
  }
  echo
  "
    <img src='https://media.tenor.com/I6kN-6X7nhAAAAAi/loading-buffering.gif' id='idk' height='50' width='50' />
    <script>
      setTimeout(function(){
        document.getElementById('idk').remove();
        document.getElementById('yeah').style.display = null;
      }, 1250);
    </script>
  ";
}
echo
"

</form>
<br/>
Transactions: <br/><br/>
$ts
      </div> &nbsp; <form style='display:inline;' method='post'><button type='submit' name='kys' value='mhm'>Clear</button></form>";
    ?>
  </center>
</body>
</html>