<?php
require_once "config.php";

if(isset($_SESSION['user_id']) AND isset($_SESSION['token'])){
header("Location:dashboard.php");
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once 'header.php'; ?>
      <title>Pistalix Hugo CMS</title>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
  </head>
<!-- style="background:url('img/background_login.png'); background-repeat:no-repeat;background-size:contain;background-position:center;" -->
<body >

  <div class="login-form" >
    <form id="loginform" >
      <div class="top">
        <img src="img/logo.png" alt="icon" class="icon">
        <h1 >Pistalix</h1>
      </div>
      <div class="form-area">
        <div class="group">
          <input type="text" name = "username" id="username"class="form-control" placeholder="Username">
          <i class="fa fa-user"></i>
        </div>
        <div class="group">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          <i class="fa fa-key"></i>
        </div>
        <input type="submit" class="btn btn-default btn-block" value="LOGIN">
      </div>
    </form>
    <div class="footer-links row">
      <div class="col-xs-6"><a href="<?php $admin_ip ?>register.php"><i class="fa fa-external-link"></i> Register Now</a></div>
      <div class="col-xs-6 text-right"><a href="#"><i class="fa fa-lock"></i> Forgot password</a></div>
    </div>
  </div>

</body>

<script>
$(document).ready(function(){

  $('#loginform').validate({
      rules:{
        username: {
          required: true,
          email: true

        },
        password:"required"
      },
      messages: {
        username: {
          required: "<p style='color:red;'>Please enter Username...</p>",
          email: "<p style='color:red;'>Please enter valid email </p>"
        },
        password: "<p style='color:red;'>Please enter password...</p>"

        },
        submitHandler: login
  });
function login(){
  // e.preventDefault();
  // alert("das");
 var data = $( "#loginform" ).serializeArray();
 console.log(data);
  $.ajax({
     type: 'POST',
     url: '<?php echo $backend_ip; ?>index.php/admin_login',
     data: data,
     dataType: 'json',
     beforeSend: function(){
       $("#mybutton").html("<button class='btn btn-lg btn-primary btn-block'><i class='fa fa-spinner fa-spin'></i> Loading</button>");
     },
     success: function (res){
         if(res){
            // console.log(res);
           if(res['success'] == 1){
            //  alert('login');
             console.log(res);
            //  console.log(JSON.stringify( res , null, '\t'));
             debugger;
             $.post("<?php echo $admin_ip; ?>session_config.php", res);
             var loc = window.location;
             var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
             window.location.replace(pathName + "dashboard.php");
           }else{
             alert("Username or Password Invalid...");
           }
         }else{
           alert("Plz...Login try letter...");
         }
     },
     complete: function(){
       $("#mybutton").html("");
       $("#mybutton").html("<input type='submit' value='Login' id='load' class='btn btn-lg btn-primary btn-block' />");
     },
     error: function(response, status, xhr){
         alert("Plz...Login try letter...");
     }
   });
 event.preventDefault();
}
});
</script>
</html>
