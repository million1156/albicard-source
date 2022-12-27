<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//funwithalbi.xyz/style.css">
  <title>Guess the number!</title>
  <meta content="A fun game to guess the number!" property="og:description" />
  <meta content="https://funwithalbi.xyz/guess-the-number" property="og:url" />
  <meta content="https://funwithalbi.xyz/cdn/albicard-gdn.png" property="og:image" />
  <meta content="#43B581" data-react-helmet="true" name="theme-color" />
</head>
<body>
  <center>
    <h1>Guess the number!</h1><br/>
    <button><a href='//funwithalbi.xyz/'>Home</a></button>
    <button onClick='history.back()'>Back</button>
    <br/><br/>
    <div id='idk'>
      Your guess is: ...
    </div>
  <br/><br/>
    <input id='guess' name='guess' placeholder='Number between 0-10'>
    <script>
      function get() {
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
          if (this.readyState==4 && this.status==200)
          {
            console.log(this.responseText+" | "+document.getElementById('guess').value);
            if (this.responseText == document.getElementById('guess').value)
            {
              document.getElementById("idk").innerHTML= 'Your guess is: <p style="color:green;display:inline;">correct!</p>';
            }
            else
            {
              document.getElementById("idk").innerHTML= 'Your guess is: <p style="color:red;display:inline;">wrong!</p> (the number was: '+this.responseText+')';
            }
          }
        }
        xmlhttp.open("GET", "https://funwithalbi.xyz/guess-the-number/generator.php", true);
        xmlhttp.send();
      }
    </script>
    <br/> <br/> <button onClick='get()'>Guess</button>
  </center>
</body>
</html>