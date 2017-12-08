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
       <title>Hugo CMS Site</title>
       <script type="text/javascript" src="js/jquery.min.js"></script>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
         <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

   </head>
   <body>
     <?php require_once 'header1.php'; ?>

     <div class="sidebar clearfix">

     <ul class="sidebar-panel nav">
       <li class="sidetitle">MAIN</li>
       <li><a href="<?php echo $admin_ip; ?>dashboard.php"><span class="icon color5"><i class="fa fa-home"></i></span>Dashboard<span id = "site_count1" class="label label-default">0</span></a></li>
       <li><a href="<?php echo $admin_ip; ?>pages.php"><span class="icon color7"><i class="fa fa-sitemap"></i></span>Pages<span class="caret"></span></a>
         <!-- <ul id = "modle_ist">

         </ul> -->
       </li>
       <li><a href="<?php echo $admin_ip; ?>sitemanager.php"><span class="icon color12"><i class="fa fa-cogs"></i></span>Content</a></li>

     </ul>


     </div>
     <!-- <div class="content">
         <div class="col-lg-12">
           <div class="panel panel-widget">
             <div class="panel-title">
               Pages
               <ul class="panel-tools">
                 <li><a class="icon panel-body " data-toggle="modal" data-target="#myModal3" id="add_module"><i class="fa fa-arrows"></i></a></li>
                 <li><a class="icon minimise-tool"><i class="fa fa-minus"></i></a></li>
               </ul>
             </div>
             <div class="panel-body">

               <ul class="basic-list" id="modlelist1">

               </ul>

             </div>
           </div>
         </div>
   </div> -->

   <div class="content">
       <div class="col-lg-12">
         <div class="panel panel-widget">
           <div class="panel-title">
           My Pages <span id ="sitecount" class="label label-danger">0</span>
           <ul class="panel-tools">
             <li><a href="#" data-toggle="modal" data-target="#myModal3" id="add_module" class="btn btn-default"><i class="fa fa-plus-circle"></i>Create New Page</a></li>
           </ul>
         </div>
         <div class="panel-body table-responsive">

           <table class="table table-dic table-hover ">
             <tbody id = "pagelist">
             </tbody>
           </table>

         </div>
       </div>
     </div>
   </div>
   <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Page</h4>
         </div>
         <div class="modal-body">
           <div class="panel panel-default">
                 <div >
                   <form class="fieldset-form" id="creat_page">
                     <fieldset>
                       <legend>Create New Page</legend>
                       <div class="form-group">
                         <label for="example10"  class="form-label">Name</label>
                         <input type="text" id="page_name" name="page_name" class="form-control">
                       </div>
                       <br>
                       <input type="submit" class="btn btn-default btn-block" value="ADD">
                     </fieldset>
                   </form>

                 </div>

           </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
         </div>
       </div>
     </div>
   </div>
   </body>
     <?php require_once 'script.php'; ?>

     <script>
     $(document).ready(function(){

       <?php
        if(!empty($_POST['site_count']) AND !empty($_POST['site_api'])){
       ?>
       localStorage.setItem('set_api', "<?php echo$_POST['site_api']; ?>");
       localStorage.setItem('set_count', "<?php echo $_POST['site_count']; ?>");
       localStorage.setItem('site_id', "<?php echo $_POST['site_id']; ?>");
       <?php
        }
        ?>
        if(!localStorage.getItem('set_api') && !localStorage.getItem('site_id')){
           var loc = window.location;
           var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
           window.location.replace(pathName + "dashboard.php");
        }
        $('#creat_page').validate({
            rules:{
              page_name:"required"
            },
            messages: {
              page_name: "<p style='color:red;'>Please enter page name...</p>"

              },
              submitHandler: addpage
        });

      function addpage(){
      var data = $( "#creat_page" ).serializeArray();
      // console.log(data[0]['value']);
      var main_user={};
      main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
      main_user['token']= "<?php echo $_SESSION['token'];?>"
      main_user['site_id']= localStorage.getItem('site_id');
      main_user['site_api']= localStorage.getItem('set_api');
      main_user['pagename']= data[0]['value'];
      // console.log (JSON.stringify( main_user , null, '\t'));
      $.ajax({
         type: 'POST',
         url: '<?php echo $backend_ip; ?>index.php/site_addPage',
         data: main_user,
         dataType: 'json',
         success: function (res){
             if(res){

               if(res['success'] == 1){
                //  alert('login');
                 var loc = window.location;
                 var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                 window.location.replace(pathName + "pages.php");
               }else{
                 alert(res['msg']);
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
      }


      // $("#site_count1").html(localStorage.getItem('set_count'));

       var main_user={};
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
      //  console.log (JSON.stringify( main_user , null, '\t'));
        debugger;
       $.ajax({
          type: 'POST',
          url: '<?php echo $backend_ip; ?>index.php/site_pagelist',
          data: main_user,
          dataType: 'json',
          success: function (res){
              if(res){
                console.log (JSON.stringify( res , null, '\t'));
                if(res['success'] == 1){

                  var ListModle="";
                  for(var i=0;i<res.data.length;i++){
                    ListModle += "<li><a data-id='"+res.data[i].model_id+"' class='modle_list'>"+res.data[i].name+"</a></li>"
                   }
                   var AllSite="";
                   var pagecount = 0
                   for(var i=0;i<res.data.length;i++){
                     AllSite += "<tr>"+
                      "<td><i class='fa fa-folder-o'></i>"+res.data[i].pagename+"</td>"+
                      "<td>"+res.data[i].created_at+"</td>"+
                      "<td class='text-r'><a data-id='"+res.data[i].page_id+"' class='btn btn-rounded btn-option1 manage_page' >Manage Page</a>&nbsp&nbsp&nbsp<a data-id='"+res.data[i].page_id+"' class='btn btn-rounded btn-danger page_id'>Remove</a></td>"+
                    "</tr>";
                    pagecount = pagecount+1;
                   }
                  // $('#modle_ist').html(ListModle);
                  $('#pagelist').html(AllSite);
                   $('#sitecount').html(pagecount);
                }else{
                  alert(res['msg']);
                }
              }else{
                alert('somthing went wrong...');
                var loc = window.location;
                var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                window.location.replace(pathName + "dashboard.php");
              }
          },
          error: function(response, status, xhr){
              alert("Plz...Login try letter...");
          }
        });

     });
     $(document).on('click', '.page_id', function(){

       var main_user={};
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['page_id']= $(this).data("id");



       $.confirm({
           title: 'Remove!',
           content: 'Remove Page!',
           buttons: {
               confirm: function () {
                 $.ajax({
                    type: 'POST',
                    url: '<?php echo $backend_ip; ?>index.php/site_pageRemove',
                    data: main_user,
                    dataType: 'json',
                    success: function (res){
                        if(res){
                           console.log(res);
                          if(res['success'] == 1){
                            var loc = window.location;
                            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                            window.location.replace(pathName + "pages.php");
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

     $(document).on('click', '.manage_page', function(){

       var main_user={};
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['page_id']= $(this).data("id");


       $.ajax({
          type: 'POST',
          url: '<?php echo $backend_ip; ?>index.php/site_editor',
          data: main_user,
          dataType: 'json',
          success: function (res){
              if(res){
                console.log(res);
                if(res['success'] == 1){

                  var form = '<form id="set_api" action="<?php echo $admin_ip; ?>createpages.php" method="post">' +
                  '<input type="hidden" name="page_id" value="'+main_user['page_id']+'" />' +
                  '<input type="hidden" name="site_api" value="'+main_user['site_api']+'" />' +
                  '<input type="hidden" name="site_id" value="'+main_user['site_id']+'" />' +
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
 </html>
