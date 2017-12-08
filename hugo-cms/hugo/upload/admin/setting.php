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
       <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

   </head>
   <body>
     <?php require_once 'header1.php'; ?>

     <div class="sidebar clearfix">

     <ul class="sidebar-panel nav">
       <li class="sidetitle">MAIN</li>
       <li><a href="<?php echo $admin_ip; ?>dashboard.php"><span class="icon color5"><i class="fa fa-home"></i></span>Dashboard<span id = "site_count1" class="label label-default">0</span></a></li>
       <li><a data-toggle="modal" data-target="#myModalCreate4" ><span class="icon color5"><i class="fa fa-plus"></i></span>Create Model</a></li>
       <li><a href="#"><span class="icon color7"><i class="fa fa-sitemap"></i></span>Model<span class="caret"></span></a>
         <ul id = "modle_ist">

         </ul>
       </li>
       <!-- <li><a href="<?php echo $admin_ip; ?>pages.php"><span class="icon color7"><i class="fa fa-sitemap"></i></span>Pages<span class="caret"></span></a> -->
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
       <div class="col-lg-8">
         <div class="panel panel-widget">
           <div class="panel-title" id="module_title">
        Select Model
       </div>

           <div class="panel-body">

               <fieldset>
                 <ul class="mailbox-inbox" id="filed_list_model">

                 </ul>

               </fieldset>

           </div>

     </div>
   </div>
</div>

   <!-- <div class="content">
       <div class="col-lg-12">
         <div class="panel panel-widget">
           <div class="panel-title">
           My Pages <span id ="sitecount" class="label label-danger">0</span>
         </div>
         <div class="panel-body table-responsive">

           <table class="table table-dic table-hover ">
             <tbody id = "model pages">
             </tbody>
           </table>

         </div>
       </div>
     </div>

   </div> -->
   <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Filed</h4>
         </div>
         <div class="modal-body">
           <div class="panel panel-default">
             <div class="panel-body">

             <ul class="mailbox-inbox">

                 <li>
                   <a  data-toggle="modal" data-target="#myModal5"  class="item clearfix">
                     <i class="fa fa-file-text fa-5x"></i>
                     <!-- <img src="img/profileimg.png" alt="img" class="img"> -->
                     <span class="from">Add Text View</span>
                   </a>
                 </li>
                 <li>
                   <a data-toggle="modal" data-target="#myModalimage"  class="item clearfix">
                     <i class="fa fa-file-image-o fa-5x"></i>
                     <!-- <img src="img/profileimg.png" alt="img" class="img"> -->
                     <span class="from">Add Image View</span>
                   </a>
                 </li>
                 <li>
                   <a data-toggle="modal" data-target="#myModal7"  class="item clearfix">
                     <i class="fa fa-code fa-5x"></i>
                     <!-- <img src="img/profileimg.png" alt="img" class="img"> -->
                     <span class="from">SEO</span>
                   </a>
                 </li>
             </ul>

             </div>
           </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
         </div>
       </div>
     </div>
   </div>
   <!-- <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Text Filed</h4>
         </div>
         <div class="modal-body">
           <form class="fieldset-form" id="filed-form">
             <fieldset>
               <legend>Create</legend>
               <div class="form-group">
                 <label for="example10" class="form-label">Name</label>
                 <input type="text" id ="filed-form" name="name" class="form-control" id="example10">
                 <input type="hidden" id="type" name="type" value="text">
               </div>
               <div class="form-group">
                 <label for="example11" class="form-label">Lable</label>
                 <input type="text" id ="lable" name="lable" class="form-control" id="example11">
               </div>
                 <input type="submit" class="btn btn-default btn-block" value="Add">
               <a class="btn btn-default addtextfiled">Add</a>
             </fieldset>
           </form>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
         </div>
       </div>
     </div>
   </div> -->

   <div class="modal fade" id="myModalimage" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Image Filed</h4>
         </div>
         <div class="modal-body">
           <div role="tabpanel">
             <ul class="nav nav-tabs nav-justified" role="tablist">
               <li role="presentation" class="active"><a href="#imagesatting" aria-controls="imagesatting" role="tab" data-toggle="tab">Satting</a></li>
               <li role="presentation"><a href="#image_validation" aria-controls="image_validation" role="tab" data-toggle="tab">Validation</a></li>
             </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="imagesatting">
           <form class="fieldset-form" id="imageview_filed" >
             <fieldset>
               <legend>Create</legend>
               <div class="form-group">
                 <label for="example10" class="form-label">Name</label>
                 <input type="text" id ="name" name="name" class="form-control" id="example10">
                 <input type="hidden" id="type_image" name="type" value="image">
               </div>
               <div class="form-group">
                 <label for="example11" class="form-label">Lable</label>
                 <input type="text" id ="lable" name="lable" class="form-control" id="example11">
               </div>
               <input type="submit" class="btn btn-default btn-block" value="Add">
               <!-- <div class="form-group">
                 <label for="example11" class="form-label">Value</label>
                 <input type="text" id ="value" name="value" class="form-control" id="example11" placeholder="Link image">
               </div> -->
               <!-- <a class="btn btn-default addtimagefiled">Add</a> -->
             </fieldset>

           </div>
           <div role="tabpanel" class="tab-pane" id="image_validation">
             <div class="checkbox checkbox-primary">
                 <input id="required_image" name="required" type="checkbox" >
                 <label for="required_image">
                     Required
                 </label>
             </div>
          </div>
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

   <div class="modal fade" id="myModal7" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Seo Filed</h4>
         </div>
         <div class="modal-body">
           <div role="tabpanel">
             <ul class="nav nav-tabs nav-justified" role="tablist">
               <li role="presentation" class="active"><a href="#seosatting" aria-controls="imagesatting" role="tab" data-toggle="tab">Satting</a></li>
               <li role="presentation"><a href="#seo_validation" aria-controls="image_validation" role="tab" data-toggle="tab">Validation</a></li>
             </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="seosatting">
           <form class="fieldset-form" id="seo_filed" >
             <fieldset>
               <legend>Create</legend>
               <div class="form-group">
                 <label for="example10" class="form-label">Name</label>
                 <input type="text" id ="name" name="name" class="form-control" id="example10">
                 <input type="hidden" id="type_seo" name="type" value="seo">
               </div>
               <div class="form-group">
                 <label for="example11" class="form-label">Lable</label>
                 <input type="text" id ="lable" name="lable" class="form-control" id="example11">
               </div>
               <input type="submit" class="btn btn-default btn-block" value="Add">

             </fieldset>

           </div>
           <div role="tabpanel" class="tab-pane" id="seo_validation">
             <div class="checkbox checkbox-primary">
                 <input id="required_seo" name="required" type="checkbox" >
                 <label for="required_seo">
                     Required
                 </label>
             </div>
          </div>
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
   <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Filed</h4>
         </div>
            <div class="modal-body">
         <div role="tabpanel">

           <!-- Nav tabs -->
           <ul class="nav nav-tabs nav-justified" role="tablist">
             <li role="presentation" class="active"><a href="#home4" aria-controls="home4" role="tab" data-toggle="tab">Satting</a></li>
             <li role="presentation"><a href="#profile4" aria-controls="profile4" role="tab" data-toggle="tab">Validation</a></li>
             <li role="presentation"><a href="#messages4" aria-controls="messages4" role="tab" data-toggle="tab">Messages</a></li>
           </ul>

           <!-- Tab panes -->
           <div class="tab-content">
             <div role="tabpanel" class="tab-pane active" id="home4">
               <form class="fieldset-form" id="filed-form">
                 <fieldset>
                   <legend>Create</legend>
                   <div class="form-group">
                     <label for="example10" class="form-label">Name</label>
                     <input type="text" id ="name" name="name" class="form-control" id="example10">
                     <input type="hidden" id="type_text" name="type" value="text">
                   </div>
                   <div class="form-group">
                     <label for="example11" class="form-label">Lable</label>
                     <input type="text" id ="lable" name="lable" class="form-control" id="example11">
                   </div>
                     <input type="submit" class="btn btn-default btn-block" value="Add">
                   <!-- <a class="btn btn-default addtextfiled">Add</a> -->
                 </fieldset>

             </div>
             <div role="tabpanel" class="tab-pane" id="profile4">
               <div class="checkbox checkbox-primary">
                   <input id="required_text" name="required" type="checkbox" >
                   <label for="required_text">
                       Required
                   </label>
               </div>
               <div class="checkbox checkbox-primary">
               <input id="unique_text" name="unique_field" type="checkbox" >
               <label for="unique_text">
                   Unique field
               </label>
             </div>
             <div class="checkbox checkbox-primary">
             <input id="match_text" name="match_spec_pattern" type="checkbox"  >
             <label for="match_text">
                Match field
             </label>
            </div>
            <div class="form-group" id="match_text_div" >
              <select disabled="disabled" name="match_spec_value" id="match_validater_text"  data-style="btn-option2">
                  <option value="url">URL</option>
                  <option value="email">Email</option>
              </select>
            </div>
            <div class="checkbox checkbox-primary">
            <input id="match_fix_value_text" name="specified_value" type="checkbox"  >
            <label for="match_fix_value_text">
               Match Fiexd value
            </label>
           </div>
             </div>
             <div role="tabpanel" class="tab-pane" id="messages4">
               <p>Duis ac enim diam</p>
             </div>

             </form>
           </div>

         </div>
       </div>
       </div>
    </div>
  </div>
   <div class="modal fade" id="myModalCreate4" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Filed</h4>
         </div>
         <div class="modal-body">
           <form class="fieldset-form" id="Add_module">
             <fieldset>
               <legend>Create New Model</legend>
               <div class="form-group">
                 <label for="example10" class="form-label">Name</label>
                 <input type="text" id ="filed-form" name="name" class="form-control" id="example10">
               </div>
                 <input type="submit" class="btn btn-default btn-block" value="Add">
               <!-- <a class="btn btn-default addtextfiled">Add</a> -->
             </fieldset>
           </form>
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
      $("#site_count1").html(localStorage.getItem('set_count'));

       var main_user={};
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
      //  console.log (JSON.stringify( res , null, '\t'));
        // debugger;
       $.ajax({
          type: 'POST',
          url: '<?php echo $backend_ip; ?>index.php/site_editor_model_list',
          data: main_user,
          dataType: 'json',
          success: function (res){
              if(res){
                // console.log (JSON.stringify( res , null, '\t'));
                if(res['success'] == 1){

                  var ListModle="";
                  for(var i=0;i<res.data.length;i++){
                    ListModle += "<li><a data-id='"+res.data[i].model_id+"' data-name='"+res.data[i].name+"' class='modle_list_bar'>"+res.data[i].name+"</a></li>"
                   }
                   var AllSite;
                   var pagecount = 0
                   for(var i=0;i<res.data.length;i++){
                     AllSite += "<tr>"+
                      "<td><i class='fa fa-folder-o'></i>"+res.data[i].name+"</td>"+
                      "<td class='text-r'><a data-id='"+res.data[i].model_id+"' class='btn btn-rounded btn-option1 manage_site' >Manage Page</a>&nbsp&nbsp&nbsp<a data-id='"+res.data[i].model_id+"' class='btn btn-rounded btn-danger site_id'>Remove</a></td>"+
                    "</tr>";
                    pagecount = pagecount+1;
                   }
                  $('#modle_ist').html(ListModle);
                  // $('#pagelist').html(AllSite);
                  //  $('#sitecount').html(pagecount);
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
        $('#Add_module').validate({
            rules:{
              name:"required"
            },
            messages: {
              name: "<p style='color:red;'>Please enter name...</p>"
            },
              submitHandler: addmodule
        });
        function addmodule(){
          var data = $( "#Add_module" ).serializeArray();
          main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
          main_user['token']= "<?php echo $_SESSION['token'];?>"
          main_user['site_id']= localStorage.getItem('site_id');
          main_user['site_api']= localStorage.getItem('set_api');
          main_user['name']= data[0]['value'];
          console.log (JSON.stringify( main_user , null, '\t'));
          $.ajax({
             type: 'POST',
             url: '<?php echo $backend_ip; ?>index.php/create_model',
             data: main_user,
             dataType: 'json',
             success: function (res){
                 if(res){
                   // console.log (JSON.stringify( res , null, '\t'));
                   if(res['success'] == 1){
                     var loc = window.location;
                     var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                     window.location.replace(pathName + "setting.php");

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

        }

        $('#filed-form').validate({
            rules:{
              lable: {
                required: true,

              },
              name:"required"
            },
            messages: {
              lable: {
                required: "<p style='color:red;'>Please enter lable...</p>"
              },
              name: "<p style='color:red;'>Please enter name...</p>"

            },
              submitHandler: addtext

        });

     function addtext(){
       var main_user={};
       var data = $( "#filed-form" ).serializeArray();
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['model_id']= $("#model_id").data("id");
       main_user['field']= data
      //  console.log (JSON.stringify( main_user  , null, '\t'));
         // alert("sdas");
       $.ajax({
          type: 'POST',
          url: '<?php echo $backend_ip; ?>index.php/create_field',
          data: main_user,
          dataType: 'json',
          success: function (res){
              if(res){
               //  console.log(res);
                if(res['success'] == 1){
                  list_filed($("#model_id").data("id"))
                  $("#filed-form :input").each(function(){
                    $(this).val('');
                    });
                  $
                    // alert('add success fully')
                      $(".close").click()
                      $(".btn-block").val("Add")
                      $("#type_text").val("text")

                  // var loc = window.location;
                  // var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                  // window.location.replace(pathName + "setting.php");
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


     }
     $('#imageview_filed').validate({
         rules:{
           lable: {
             required: true,

           },
           name:"required"
         },
         messages: {
           lable: {
             required: "<p style='color:red;'>Please enter lable...</p>"
           },
           name: "<p style='color:red;'>Please enter name...</p>"

         },
           submitHandler: addimage

     });
     function addimage(){
       var main_user={};
       var data = $( "#imageview_filed" ).serializeArray();
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['model_id']= $("#model_id").data("id");
       main_user['field']= data

         // alert("sdas");
        //  console.log (JSON.stringify( main_user  , null, '\t'));
       $.ajax({
          type: 'POST',
          url: '<?php echo $backend_ip; ?>index.php/create_field',
          data: main_user,
          dataType: 'json',
          success: function (res){
              if(res){
               //  console.log(res);
                if(res['success'] == 1){
                  list_filed($("#model_id").data("id"))
                  $("#imageview_filed :input").each(function(){
                    $(this).val('');
                    });
                $(".close").click()
                $(".btn-block").val("Add")
                $("#type_image").val("image")
                // alert('add success fully')
                  // var loc = window.location;
                  // var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                  // window.location.replace(pathName + "setting.php");
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


     }

     $('#seo_filed').validate({
         rules:{
           lable: {
             required: true,

           },
           name:"required"
         },
         messages: {
           lable: {
             required: "<p style='color:red;'>Please enter lable...</p>"
           },
           name: "<p style='color:red;'>Please enter name...</p>"

         },
           submitHandler: seofiled

     });
     function seofiled(){
       var main_user={};
       var data = $( "#seo_filed" ).serializeArray();
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['model_id']= $("#model_id").data("id");
       main_user['field']= data

        // console.log (JSON.stringify( main_user  , null, '\t'));
        $.ajax({
           type: 'POST',
           url: '<?php echo $backend_ip; ?>index.php/create_field',
           data: main_user,
           dataType: 'json',
           success: function (res){
               if(res){
                //  console.log(res);
                 if(res['success'] == 1){
                   list_filed($("#model_id").data("id"))
                   $("#seo_filed :input").each(function(){
                     $(this).val('');
                     });
                     $(".close").click()
                     $(".btn-block").val("Add")
                     $("#type_seo").val("image")
                 // alert('add success fully')
                   // var loc = window.location;
                   // var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                   // window.location.replace(pathName + "setting.php");
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


     }

     });
     $('#match_text').change(function(){
         if(this.checked){
          $('#match_validater_text').removeAttr('disabled');

         }else{
          //  $('#match_text_div').css("display","none")
          $('#match_validater_text').attr('disabled', 'true');
         }
     });
    function list_filed(model_id){

      var main_user={};
      main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
      main_user['token']= "<?php echo $_SESSION['token'];?>"
      main_user['site_id']= localStorage.getItem('site_id');
      main_user['site_api']= localStorage.getItem('set_api');
      main_user['model_id']= model_id;
      $.ajax({
         type: 'POST',
         url: '<?php echo $backend_ip; ?>index.php/get_model_fields',
         data: main_user,
         dataType: 'json',
         success: function (res){
             if(res){

              //  console.log(res);
               if(res['success'] == 1){
                //  console.log (JSON.stringify( res  , null, '\t'));
                 var AllFiled = " "
                 for(var i=0;i<res.fields.length;i++){

                  //  AllFiled +="<li class='col-3 '><span>"+res.data.fields[i].field_name+"</span>Name</li>"+
                  //              "<li class='col-3'><span>"+res.data.fields[i].field_type+"</span>Filed</li>"+
                  //              "<li class='col-3 color7'><span>"+res.data.fields[i].field_label+"</span>Lebel</li>"+
                  //              "<li class='col-3 color7'><span>"+res.data.fields[i].field_data+"</span>Data</li>"
                  AllFiled +='<li>'+
                                '<a href="#" class="item clearfix">'+
                                  '<i class="fa fa-dedent fa-3x img"></i>'+
                                  '<span class="from">'+res.fields[i].name+'</span>'+
                                  res.fields[i].field_type+
                                  '<span class="date">22 May</span>'+
                                '</a>'+
                              '</li>'
                 }
              $('#filed_list_model').html(AllFiled);
               }else{
                 alert(res['msg']);
               }
             }else{
               alert(res['msg']);
             }
         },
         error: function(response, status, xhr){
             alert("Plz...Login try letter...");
         }
       });

    }
     $(document).on('click', '.modle_list_bar', function(){
       var main_user={};
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['model_id']= $(this).data("id");
      //  alert($(this).data("name"));
      var module='<ul class="panel-tools">'+
                 '<li><a data-id="'+$(this).data("id")+'" data-toggle="modal" data-target="#myModal4" class="btn btn-default" id="model_id"><i class="fa fa-plus-circle"></i>Create New Filed</a></li>'+
               '</ul>'
      $('#module_title').html($(this).data("name")+'->Fileds'+module);
      $.ajax({
         type: 'POST',
         url: '<?php echo $backend_ip; ?>index.php/get_model_fields',
         data: main_user,
         dataType: 'json',
         success: function (res){
             if(res){

              //  console.log(res);
               if(res['success'] == 1){
                //  console.log (JSON.stringify( res  , null, '\t'));
                 var AllFiled = " "
                 for(var i=0;i<res.fields.length;i++){

                  //  AllFiled +="<li class='col-3 '><span>"+res.data.fields[i].field_name+"</span>Name</li>"+
                  //              "<li class='col-3'><span>"+res.data.fields[i].field_type+"</span>Filed</li>"+
                  //              "<li class='col-3 color7'><span>"+res.data.fields[i].field_label+"</span>Lebel</li>"+
                  //              "<li class='col-3 color7'><span>"+res.data.fields[i].field_data+"</span>Data</li>"
                  AllFiled +='<li>'+
                                '<a href="#" class="item clearfix">'+
                                  '<i class="fa fa-dedent fa-3x img"></i>'+
                                  '<span class="from">'+res.fields[i].name+'</span>'+
                                  res.fields[i].field_type+
                                  '<span class="date">22 May</span>'+
                                '</a>'+
                              '</li>'
                 }
              $('#filed_list_model').html(AllFiled);
               }else{
                 alert(res['msg']);
               }
             }else{
               alert(res['msg']);
             }
         },
         error: function(response, status, xhr){
             alert("Plz...Login try letter...");
         }
       });
     });
     </script>
 </html>
