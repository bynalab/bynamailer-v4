
<?php
    session_start();

    if(!isset($_SESSION['login'])) {

        echo "<center>Password </br> <form action='' method='post'> <input type='password' name='bpassword' required/> <button type='submit' name='submit'>Submit</button> </form></center>";

    if(isset($_POST['bpassword'])) {
        if($_POST['bpassword'] == date("mdY")){
            $_SESSION['login'] = true;
            header("Location: index.php");
        } else {
            echo "<center style='color:red'>Incorrect Password</center>";
        }
    }

    }

    if(isset($_GET['a']) and $_GET['a'] == 'logout'){
        session_destroy();
        header("Location: index.php");
    }

    if(isset($_SESSION['login'])) {
       
            
?>

<!doctype html>
<html>
<head>
    
<meta charset="utf-8">
<title>Byna Sender</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/cssINDEX.css">
<link rel="stylesheet" type="text/css" href="css/hacker.css">
<link rel="shortcut icon" href="bynalogo.ico" type="image/x-icon" />
</head>

<body>
    
    <canvas id="canvas" style="position:fixed; top:0;left:0; z-index:-1;"></canvas>

  <center style="font-size:20px"> <a href="?a=logout">Logout</a> </center>  

  <div id="header"><h1 id="inv">BYNA SENDER <a href="https://icq.im/bynalab" target="_blank">(ICQ)</a></h1></div>
   
<center>
    <div id="accordion">
        <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
            <button style="font-size:20px" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                View Keywords
            </button>
            </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                Reciever Email: {email} <br/>
                Receiver Email (Base64): {base64email} <br/>
                Receiver Username: {mename} <br/>
                Email Domain: {domain} <br/>
                Host: {frmsite} <br/>
                Link: {link} <br/>
                Current Time: {time} <br/>
                Today's Date: {date} <br/>
                Random: {random} <br/>
                Md5: {md5} <br/>
            </div>
        </div>
        </div>
    </div>
</center>


    <div class="">
    <div class="container-fluid">
<div id="container">
<div id="form">
    <form name="form" method="post" action="sendmail.php" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-4 text-center">
            <div class="form-group">
                <label>Subject</label>
                <input class="form-control" value="Hello {mename}" required="" name="subject" placeholder="Hello {mename}">
            </div>
        </div>
        <div class="col-lg-4 text-center">
            <div class="form-group">
                <label>Sender Email</label>
                <input class="form-control" placeholder="postmaster@gmail.com" value="postmaster@gmail.com" name="bynapostmaster" required>
            </div>
        </div>
        
        <div class="col-lg-4 text-center">
            <div class="form-group">
                <label>Sender Name</label>
                <input class="form-control" placeholder="Byna Sender" value="Byna Sender" name="sender" required>
            </div>
        </div>
    </div>
    <br/>
     <div class="row">
        <div class="col-lg-6 text-center">
            <div class="form-group">
                <label for="recipient">Recipients:</label>
                <textarea class="form-control" name="recipient" rows="15" id="recipient" placeholder="Insert Recipients. Example: postmaster@gmail.com, emails are in 'New line' format" required>postmaster@gmail.com</textarea>
            </div>
        </div>
        <div class="col-lg-6 text-center">
            <div class="form-group">
                <label for="message">Letter:</label>
        <textarea style="font-family: Arial, Helvetica, sans-serif;" class="form-control" rows="15" name="message" id="message" placeholder="Your message goes here. HTML is allowed" required>Your letter here. HTML is allowed</textarea>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-lg-6 text-center">
        <div class="form-group">
            <label for="message">Links:</label>
            <textarea style="font-family: Arial, Helvetica, sans-serif;" class="form-control" rows="15" name="link" id="link">Paste your links here. New Line</textarea>
        </div>
    </div>

    <br/><br/>
        <div class="col-lg-6 text-center">
            <input type="file" name="bynaAttach"/><br/>
        </div>
    </div>

    <span id="smtp-warning" style="margin-left: 40%;">Leave blank if you don't have smtp</span>
<br/><br/>
    <div id="smtp">
        <div class="col-lg-3 text-center">
            <div class="form-group">
                <label>SMTP Server</label>
                <input class="form-control" value="" placeholder="mail.bynaserver.com" name="smtpserver">
            </div>
        </div>
            
        <div class="col-lg-3 text-center">
            <div class="form-group">
                <label>SMTP User</label>
                <input class="form-control" value="" name="smtpuser" placeholder="byna@bynalab.com">
            </div>
        </div>
            
        <div class="col-lg-3 text-center">
            <div class="form-group">
                <label>SMTP Password</label>
                <input class="form-control" value="" name="smtppass" type="password" placeholder="password">
            </div>
        </div>

        <div class="col-lg-1 text-center">
            <div class="form-group">
                <label>Delay</label>
                <script>
                    document.write("<select class='form-control' name='delay'>");
                    for (var i = 4; i <= 100; i++) {
                        document.write("<option value = " + i + ">" + i + " secs </option>");
                    } 
                    document.write("</select>");
                </script>
            </div>
        </div>
            
        </div>
    
            <div class="col-lg-12 text-center">
            <button type="submit" name="submit" class="btn btn-success btn-lg"><span id="fire">Start Firing</span></button>
            </div><br/>
        </form>
    </div>
</div>
    </div>

<div id="footer">
    @users Risk!... Bynalab will not be held responsible for any technical threat caused using this sender
</div>
    
    
        
   </div>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/customjs.js" type="text/javascript"></script>

    <script>


    </script>

</body>
</html>

<?php } ?>
