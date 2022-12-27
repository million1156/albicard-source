<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//funwithalbi.xyz/style.css?version=<?php echo time(); ?>">
  <title>Apply for ALBICARD</title>
  <meta content="Most secure way to pay. AlbiCard." property="og:description" />
  <meta content="https://www.funwithalbi.xyz/albicard" property="og:url" />
  <meta content="https://www.funwithalbi.xyz/cdn/albicard.png" property="og:image" />
  <meta content="#43B581" data-react-helmet="true" name="theme-color" />
</head>
<body>
  <center>
    <h1>Apply for AlbiCard</h1><br/>
    <button><a href='..'>Home</a></button>
    <button onClick='history.back()'>Back</button>
    <br/><br/>
    <img src="//www.funwithalbi.xyz/cdn/albicard.png" height="148" width="219"></img>
    <?php
      if (isset($_GET['code']))
      {
        if($_GET['code'] == "GIMMEDOLA10")
        {
          echo "<h2>Get a free $5 when signing up! (ENDED)</h2>";
        } 
        if($_GET['code'] == "NONBEAMING5")
        {
          echo "<h2>Get a free $200 when signing up!</h2>";
        } 
      } 

      if (isset($_POST['dc']))
      {
        $dc = $_POST['dc'];
        $fn = $_POST['fn'];
        $rw = $_POST['rw'];

        function dostuff($dcc, $fnn, $rww) {
          try
          {
            $json = json_decode(file_get_contents("apps-250.json"), true);
            $file = fopen("apps-250.json", "w");
            array_push($json, array("Discord Username" => $dcc, "Full Name" => $fnn, "Reason" => $rww, "10Dollars" => $_GET['code'] == "GIMMEDOLA10", "approved" => false, "id" => end($json)['id']+1));
            fwrite($file, json_encode($json));
            fclose($file);
            return true;
          }
          catch (Exception $e)
          {
            return false;
          }
        }
        
        if (dostuff($dc, $fn, $rw))
        {
          echo "<p style='color:green;'>Thanks for applying! Your application might take 24 hours to be approved/declined so please be patient! You'll receive a DM about the status of your application.</p>";
        }
        else
        {
          echo "<p style='color:red;'>An error occurred!</p>";
        }
      }
    ?>
    
    <form method='post'>
      Discord Username and tag: <br/><br/>
      <input type='text' name='dc' pattern="(.*)#(\d){4}" /> <br/><br/>
      Full Name: <br/><br/>
      <input type='text' name='fn'/> <br/><br/>
      Why do you want an AlbiCard? <br/><br/>
      <input type='text' name='rw' />
      <br/><br/>
      <input type='submit'>
    </form>
    <br/><br/><br/>
    <?php if (!isset($_SESSION['loggedin'])) { ?>
    <form method='post'>
      Login using card number and CVV: <br/><br/>
      <input type='text' placeholder="card number" pattern="(\d{4}\s?){4}" name='cn' /> &nbsp; <input type='password' placeholder='CVV' name='cvv' pattern="\d{3}" > &nbsp; <input type='submit' name='sub' value='Login'>
    </form> 
    <?php } else { ?>
    <a href='//funwithalbi.xyz/albicard/account.php'>Manage Card</a>          
    <?php
      }
      if (isset($_POST['sub']))
      {
        $cn = $_POST['cn'];
        $cvv = $_POST['cvv'];

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
        
        function magic($cnn, $cvvv) {
          try
          {
            $cards = json_decode(file_get_contents("cards-250.json"), true);
            $cnn = str_replace(" ", "", $cnn);
            $ct = 0;
            foreach ($cards as $idk)
            {
              $cards[$ct]['number'] = str_replace(" ", "", $cards[$ct]['number']);
              $ct++;
            }
            
            if (inthing("number", $cnn, $cards) != false)
            {
              $item = inthing("number", $cnn, $cards);
              if ($item['cvv'] == $cvvv)
              {
                $person = $item['name'];

                $_SESSION['loggedin'] = true;
                $_SESSION['card']['number'] = $cnn;
                $_SESSION['card']['cvv'] = $cvvv;
                
                echo "<p style='color:green;'>Successfully logged in as $person!</p><br/><br/>
                <p style='color:green;'>Manage this card <a href='//funwithalbi.xyz/albicard/account.php'>here</a></p>";
              }
              else
              {
                echo "<p style='color:red;'>Invalid CVV!</p>";
              }
            }
            else
            {
              echo "<p style='color:red;'>Invalid Card Number!</p>";
            }
            return true;
          }
          catch (Exception $e)
          {
            return false;
          }
        }

        if (magic($cn, $cvv))
        {
          echo "<p style='color:green;'>We ran our <i>magic</i> with no errors!</p>";
        }
        else
        {
          echo "<p style='color:red;'>An error occured!</p>";
        }
      }
    ?>
  </center>
</body>
</html>