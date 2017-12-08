<?php
require_once "config.php";
if(empty($_SESSION['user_id']) AND empty($_SESSION['token'])){
    header("Location:index.php");
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'header.php'; ?>
      <title>Create Site</title>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
      <script  type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
  </head>
  <body>
    <?php require_once 'header1.php'; ?>
<div class="container" style="margin-top:100px;align-items: center;">
    <div class="row">


      <div class="col-md-12">
        <div class="panel panel-default">

          <div class="panel-title">
            Create Site
          </div>

              <div class="panel-body">
                <form id="createsiteform">
                  <input type="hidden" name = "id" id="id" class="form-control form-control-line" value="<?php echo $_SESSION['user_id']; ?>">
                  <input type="hidden" name = "token" id="token" class="form-control form-control-line" value="<?php echo $_SESSION['token']; ?>">
                  <div class="form-group">
                    <label for="example3" class="form-label">Site Name</label>
                    <input type="text" name = "sitename" id="sitename" class="form-control form-control-line">
                  </div>
                  <div class="form-group">
                    <label for="example4" class="form-label">Clint Name </label>
                    <input type="text" name = "clintname" id="clintname" class="form-control form-control-line">
                  </div>
                  <div class="form-group">
                    <label for="example5"  class="form-label">Ph. Number</label>
                    <input type="text"  name = "number" id="number" class="form-control form-control-line">
                  </div>
                  <div class="form-group">
                    <label for="example5"  class="form-label">Email</label>
                    <input type="text" name = "email" id="email" class="form-control form-control-line">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control form-control-line" name = "description" id="description" rows="3"></textarea>
                  </div>
                  <input type="submit" class="btn btn-default" value="Create" />
                </form>

              </div>

        </div>
      </div>

      <div class="footer-links row">
        <div class="col-xs-6 text-right"><a href="<?php $admin_ip ?>dashboard.php"><i class="fa fa-lock"></i> Go To Dashboard</a></div>
      </div>

    </div>
</div>


  </body>
  <script>
  $(document).ready(function(){
    $('#createsiteform').validate({
      rules:{
          email: {
              required: true,
              email: true
          },
          sitename:"required",
          clintname:"required",
          number: {
              required: true,
              phoneUS: true,
              minlength:10
          },
          description: "required"
        },
        messages: {
          email: {
              required: "<p style='color:red;'>Please enter Email...</p>",
              email: "<p style='color:red;'>Please enter valid email </p>"
          },
          sitename: "<p style='color:red;'>Please enter Site Name...</p>",
          clintname: "<p style='color:red;'>Please enter Clint Name...</p>",
          number: {
              required: "<p style='color:red;'>Please enter Mo.Number...</p>",
              phoneUS: "<p style='color:red;'>Please enter valid number....</p>",
              minlength: "<p style='color:red;'>Please enter valid number...</p>"
          },
          description: "<p style='color:red;'>Please enter description...</p>"
        },
        submitHandler: create
    });
    function create(){
      var data = $( "#createsiteform" ).serializeArray();
      console.log(JSON.stringify( data , null, '\t'));
       $.ajax({
          type: 'POST',
          url: '<?php echo $backend_ip; ?>index.php/site_generate',
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
                    alert(res['msg']);
                }  else{
                  alert(res['msg']);
                }
              }else{
                alert(res['msg']);
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
  <?php require_once 'script.php'; ?>
</html>
