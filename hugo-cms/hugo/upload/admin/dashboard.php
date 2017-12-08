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
      <title>Hugo CMS Dashboard</title>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  </head>
  <body>
    <?php require_once 'header1.php'; ?>

    <?php //require_once 'sidebar.php'; ?>
<div class="container" style="margin-top:100px;align-items: center;">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-widget" style="height:450px;">
      <div class="panel-title">
        My Sites <span id ="sitecount" class="label label-danger">0</span>
        <ul class="panel-tools">
          <li><a href="<?php echo $admin_ip; ?>createsite.php" class="btn btn-default"><i class="fa fa-plus-circle"></i>Create New site</a></li>
          <li><a class="icon"><i class="fa fa-refresh"></i></a></li>
        </ul>
      </div>
      <div class="panel-body table-responsive">

        <table class="table table-dic table-hover ">
          <tbody id = "sitelist">
          </tbody>
        </table>

      </div>
    </div>
  </div>

</div>
</div>


  </body>
  <script>
  $(document).ready(function(){
    var main_user={};
    main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
    main_user['token']= "<?php echo $_SESSION['token'];?>"
          // console.log(JSON.stringify( main_user , null, '\t'));
    $.ajax({
       type: 'POST',
       url: '<?php echo $backend_ip; ?>index.php/site_list',
       data: main_user,
       dataType: 'json',
       success: function (res){
           if(res){
             if(res['success'] == 1){
              //  console.log(res);
               // console.log (JSON.stringify( res , null, '\t'));
              //  debugger;

               $('#sitecount').html(res.site_count);
              var AllSite;
              for(var i=0;i<res.data.length;i++){
                AllSite += "<tr>"+
                 "<td><i class='fa fa-folder-o'></i>"+res.data[i].site_name+"</td>"+
                 "<td>"+res.data[i].created_at+"</td>"+
                 "<td class='text-r'><a data-id='"+res.data[i].site_id+"' class='btn btn-rounded btn-option1 manage_site' >Manage Site</a>&nbsp&nbsp&nbsp<a data-id='"+res.data[i].site_id+"' class='btn btn-rounded btn-danger site_id'>Remove</a></td>"+
               "</tr>";

              }
           $('#sitelist').html(AllSite);


             }else{
               alert("No site created");
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


  });

  $(document).on('click', '.site_id', function(){

    var main_user={};
    main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
    main_user['token']= "<?php echo $_SESSION['token'];?>"
    main_user['site_id']= $(this).data("id");



    $.confirm({
        title: 'Remove!',
        content: 'Remove site!',
        buttons: {
            confirm: function () {
              $.ajax({
                 type: 'POST',
                 url: '<?php echo $backend_ip; ?>index.php/site_remove',
                 data: main_user,
                 dataType: 'json',
                 success: function (res){
                     if(res){
                        console.log(res);
                       if(res['success'] == 1){
                         var loc = window.location;
                         var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                         window.location.replace(pathName + "dashboard.php");
                       }else{
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
            },
            cancel: function () {
                $.alert('Canceled!');
            },
        }
    });

  });


    $(document).on('click', '.manage_site', function(){

      var main_user={};
      main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
      main_user['token']= "<?php echo $_SESSION['token'];?>"
      main_user['site_id']= $(this).data("id");


      $.ajax({
         type: 'POST',
         url: '<?php echo $backend_ip; ?>index.php/site_editor',
         data: main_user,
         dataType: 'json',
         success: function (res){
             if(res){
               console.log(res);
               if(res['success'] == 1){

                 var form = '<form id="set_api" action="<?php echo $admin_ip; ?>sitemanager.php" method="post">' +
                 '<input type="hidden" name="site_id" value="'+main_user['site_id']+'" />' +
                 '<input type="hidden" name="site_api" value="'+res['site_api']+'" />' +
                 '<input type="hidden" name="site_count" value="'+res['site_count']+'" />' +
                 '</form>';

                 $('body').append(form);
                 $('#set_api').submit();

                //  var loc = window.location;
                //  var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                //  window.location.replace(pathName + "sitemanager.php");


               }else{
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


    });
  </script>
  <?php require_once 'script.php'; ?>
</html>
