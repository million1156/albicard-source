<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//www.funwithalbi.xyz/style.css">
  <title>View Apps</title>
</head>
<body>
  <center>
    <button><a href='..'>Home</a></button>
    <button onClick='history.back()'>Back</button>
    <button><a href='./'>AlbiCard</a></button>
    <br/><br/>
    <div id='apps'>
      <?php
        if (isset($_GET['d']))
        {
          $id = $_GET['d'];

          $json = json_decode(file_get_contents("apps-250.json"), true);
          $file = fopen("apps-250.json", "w");
          $count = 0;
          foreach ($json as $app)
          {
            $count++;
            if (intval($app['id']) == intval($id))
            {
              $json[$count]['approved'] = true;
            }
          }
          fwrite($file, json_encode($json));
          fclose($file);
        }

          $json = json_decode(file_get_contents("apps-250.json"), true);
          $file = fopen("apps-250.json", "w");
          fwrite($file, json_encode($json));
          fclose($file);

        if (isset($_GET['a']))
        {
          $id = $_GET['a'];

          $json = json_decode(file_get_contents("apps-250.json"), true);
          $file = fopen("apps-250.json", "w");
          $count = 1;
          foreach ($json as $app)
          {
            if ($app['id'] == $id)
            {
              unset($json[$count]);
            }
            $count++;
          }
          fwrite($file, json_encode($json));
          fclose($file);
        }

        $json = json_decode(file_get_contents("apps-250.json"), true);
  
        foreach ($json as $app) {
          $d = $app['10Dollars'] ? 'yes' : 'no';
          $a = $app['approved'] ? 'yes' : 'no';
          
          echo "<div>Discord: ".$app['Discord Username']."<br/>Full Name: ".$app['Full Name']."<br/>Reason: ".$app['Reason']."<br/>10 Dollars: ".$d."<br/><br/>Approved: ".$a." <br/><form><button type='submit' name='d' value='".$app['id']."'>Approve</button><button type='submit' name='a' value='".$app['id']."'>Delete</button></form></div><br/><br/>";
        }
      ?>
    </div>
  </center>
</body>
</html>