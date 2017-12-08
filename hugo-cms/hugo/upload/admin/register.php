<?php
require_once "config.php";
 ?>
 <html lang="en">
   <head>
     <?php require_once 'header.php'; ?>
       <title>Pistalix Hugo CMS</title>
       <script type="text/javascript" src="js/jquery.min.js"></script>
       <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
       <script  type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
   </head>

 <body>

   <div class="login-form" >
     <form id="registerform" >
       <div class="top">
         <img src="img/logo.png" alt="icon" class="icon">
         <h1 >Pistalix</h1>
       </div>
       <div class="form-area">
         <div class="group">
           <input type="text" name = "firstname" id="firstname" class="form-control" placeholder="First Name">
           <i class="fa fa-user"></i>
         </div>
         <div class="group">
           <input type="text" name = "lastname" id="lastname" class="form-control" placeholder="Last Name">
           <i class="fa fa-user"></i>
         </div>
         <div class="group">
           <input type="text" name = "phone" id="phone" class="form-control" placeholder="Mo.Number">
           <i class="fa fa-phone"></i>
         </div>
         <div class="group">
           <input type="text" name = "username" id="username" class="form-control" placeholder="Email">
           <i class="fa fa-envelope"></i>
         </div>
         <div class="group">
           <input type="password" name="password" id="password" class="form-control" placeholder="Password">
           <i class="fa fa-key"></i>
         </div>
         <div class="group">
           <input type="password" name="comformpassword" id="comformpassword" class="form-control" placeholder="Re-Password">
           <i class="fa fa-key"></i>
         </div>
         <input type="submit" class="btn btn-default btn-block" value="REGISTER">
       </div>
     </form>
     <div class="footer-links row">
       <div class="col-xs-12 text-center"><a href="<?php $admin_ip ?>index.php"><i class="fa fa-external-link"></i> Alredy Login</a></div>
     </div>
   </div>

 </body>
 <script>
 $(document).ready(function(){

   $('#registerform').validate({
       rules:{
         username: {
           required: true,
           email: true

         },
        lastname:"required",
        firstname:"required",
        phone: {
            required: true,
            phoneUS: true,
            minlength:10
          },
           comformpassword: {
             required: true,
             minlength: 3,
             equalTo:'#password'
           },
           password: {
             required: true,
             minlength: 3

            }
       },
       messages: {
         username: {
           required: "<p style='color:red;'>Please enter Email...</p>",
           email: "<p style='color:red;'>Please enter valid email </p>"
         },
         lastname: "<p style='color:red;'>Please enter Last Name...</p>",
         firstname: "<p style='color:red;'>Please enter First Name...</p>",
         phone: {
           required: "<p style='color:red;'>Please enter Mo.Number...</p>",
           phoneUS: "<p style='color:red;'>Please enter valid number....</p>",
           minlength: "<p style='color:red;'>Please enter valid number...</p>"
         },
           password: {
             required: "<p style='color:red;'>Please enter Password...</p>",
             minlength: "<p style='color:red;'>Please enter atleast 3 characters long</p>"
           },
            comformpassword: {
              required: "<p style='color:red;'>Please enter Password...</p>",
              minlength: "<p style='color:red;'>Please enter atleast 3 characters long</p>",
              equalTo:"<p style='color:red;'>Please enter samePassword long</p>"
             }

         },
         submitHandler: register
   });
 function register(){
  var data = $( "#registerform" ).serializeArray();
  // console.log(JSON.stringify( data , null, '\t'));
   $.ajax({
      type: 'POST',
      url: '<?php echo $backend_ip; ?>index.php/register_user',
      data: data,
      dataType: 'json',
      beforeSend: function(){
        $("#mybutton").html("<button class='btn btn-lg btn-primary btn-block'><i class='fa fa-spinner fa-spin'></i> Loading</button>");
      },
      success: function (res){
          if(res){
            if(res['success'] == 1){
              console.log(res);
              // console.log(JSON.stringify( res , null, '\t'));
              debugger;
                alert("Success Full Regisration Complete");
              var loc = window.location;
              var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
              window.location.replace(pathName + "index.php");
            }else if (res['success'] == 2) {
                alert("Username Is Allredy Exist...");
            }  else{
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
