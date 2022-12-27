<?php
function inthing($key, $val, $array)
{
  foreach ($array as $item)
  {
    if (isset($item[$key]))
    {
      if ($item[$key] == $val)
      {
        return $item;
      }
    }
  }
  return false;
}

function ranstr($length = 14) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

if (isset($_GET['cvv']) & isset($_GET['to']) & isset($_GET['from']) & isset($_GET['amt']))
{
  $json = json_decode(file_get_contents("cards-250.json"), true);
  $to = $_GET['to'];
  $from = $_GET['from'];
  
  $amt = intval($_GET['amt']);
  $cvv = $_GET['cvv'];

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
              if ($it['number'] == $not['number'])
              {
                $code=543;
              }
              else
              {
                $file = fopen("cards-250.json", "w");
                $count = 0;
                $things = 0;
                $albi = inthing("id", 6, $json, false, true);
                foreach ($json as $card)
                {
                  if ($card['number'] == str_replace(" ", "", $to))
                  {
                    $json[$count]['balance'] = $json[$count]['balance'] + $amt;
                    $json[$albi]['balance'] = $json[$albi]['balance'] + $fee;
                    array_push($json[$count]['history'], array("type" => "receive", "from" => $it['name'], "amount" => $amt, "AlbID" => ranstr(),
                                                               "time" => time()));
                  }
                  elseif ($card['number'] == str_replace(" ", "", $from))
                  {
                    $json[$count]['balance'] = $json[$count]['balance'] - ($amt + $fee);
                    $json[$albi]['balance'] = $json[$albi]['balance'] + $fee;
                    array_push($json[$albi]['history'], array("type" => "receive", "from" => "Fee Collection", "amount" => $fee, "AlbID" => ranstr(),
                                                        "time" => time()));
                    array_push($json[$count]['history'], array("type" => "send", "to" => $not['name'], "amount" => $amt, "AlbID" => ranstr(),
                                                              "time" => time()));
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
  echo json_encode($js);
}
else
{
  $js = array(
    "code" => 400,
    "message" => "Please provide all the URL parameters."
  );
  echo json_encode($js);
}