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
       <script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>
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
       <li><a href="#"><span class="icon color7"><i class="fa fa-sitemap"></i></span>Model<span class="caret"></span></a>
         <ul id = "modle_ist">

         </ul>
       </li>
       <li><a href="<?php echo $admin_ip; ?>pages.php"><span class="icon color7"><i class="fa fa-sitemap"></i></span>Pages<span class="caret"></span></a>
         <!-- <ul id = "modle_ist">

         </ul> -->
       </li>
       <li><a href="<?php echo $admin_ip; ?>setting.php"><span class="icon color12"><i class="fa fa-cogs"></i></span>Settings</a></li>

     </ul>


     </div>
     <div class="content">
         <div class="col-lg-12">
           <div class="panel panel-widget">
             <div class="panel-title" id="page_title">
           Fieldset
           <ul class="panel-tools">
             <li><a data-toggle="modal" data-target="#myModal4" class="btn btn-default"><i class="fa fa-plus-circle"></i>Create New Filed</a></li>
           </ul>
         </div>

             <div class="panel-body">

                 <fieldset>
                   <ul class="basic-list widget-inline-list clearfix" id="filed-list">
                   </ul>

                 </fieldset>

             </div>

       </div>
     </div>
</div>

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
                <a data-toggle="modal" data-target="#myModal6"  class="item clearfix">
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

<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
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
            <div class="form-group">
              <label for="example11" class="form-label">Value</label>
              <input type="text" id ="value" name="value" class="form-control" id="example11">
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

<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Image Filed</h4>
      </div>
      <div class="modal-body">
        <form class="fieldset-form" id="imageview_filed" >
          <fieldset>
            <legend>Create</legend>
            <div class="form-group">
              <label for="example10" class="form-label">Name</label>
              <input type="text" id ="name" name="name" class="form-control" id="example10">
              <input type="hidden" id="type" name="type" value="image">
            </div>
            <div class="form-group">
              <label for="example11" class="form-label">Lable</label>
              <input type="text" id ="lable" name="lable" class="form-control" id="example11">
            </div>
            <!-- <div class="form-group">
              <label for="example11" class="form-label">Value</label>
              <input type="text" id ="value" name="value" class="form-control" id="example11" placeholder="Link image">
            </div> -->
            <div class="form-group">
              <label for="example11" class="form-label">Image</label>
              <input type="file" id="imageview" class="imageview" class="filestyle" data-buttonBefore="true">
            </div>

            <!-- <a class="btn btn-default addtimagefiled">Add</a> -->
          </fieldset>
        </form>
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
        <form class="fieldset-form" id="seo_filed" >
          <fieldset>
            <legend>Create</legend>
            <div class="form-group">
              <label for="example10" class="form-label">Name</label>
              <input type="text" id ="name" name="name" class="form-control" id="example10">
              <input type="hidden" id="type" name="type" value="seo">
            </div>
            <div class="form-group">
              <label for="example11" class="form-label">Title</label>
              <input type="text" id ="title" name="title" class="form-control" id="example11">
            </div>
            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea class="form-control" id ="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
              <label for="example11" class="form-label">Seo Image</label>
              <input type="file" id="seoimage" class="seoimage" class="filestyle" data-buttonBefore="true">
            </div>
            <input type="submit" class="btn btn-default btn-block" value="Add">
            <!-- <a class="btn btn-default addtseofiled">Add</a> -->
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
      if(!empty($_POST['page_id']) AND !empty($_POST['site_api']) AND !empty($_POST['site_id'])){
     ?>
     localStorage.setItem('set_api', "<?php echo$_POST['site_api']; ?>");
     localStorage.setItem('set_id', "<?php echo $_POST['site_id']; ?>");
     localStorage.setItem('page_id', "<?php echo $_POST['page_id']; ?>");
     <?php
      }
      ?>
      if(!localStorage.getItem('set_api') && !localStorage.getItem('site_id')){
         var loc = window.location;
         var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
         window.location.replace(pathName + "dashboard.php");
      }
  $(":file").filestyle({buttonBefore: true});
  $("#site_count1").html(localStorage.getItem('set_count'));
  var main_user={};
  main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
  main_user['token']= "<?php echo $_SESSION['token'];?>"
  main_user['site_id']= localStorage.getItem('site_id');
  main_user['site_api']= localStorage.getItem('set_api');
  main_user['page_id']= localStorage.getItem('page_id');
  $.ajax({
     type: 'POST',
     url: '<?php echo $backend_ip; ?>index.php/site_pageFieldList',
     data: main_user,
     dataType: 'json',
     success: function (res){
         if(res){
           console.log(res);
           if(res['success'] == 1){
            var AllSite="";
            var AllFiled ="";
            for(var i=0;i<res.data.length;i++){
              AllSite += "<li>"+res.data[i].field_name+" <span class='right label label-default'>"+res.data[i].field_type+"</span></li>";

              AllFiled +="<li class='col-3 '><span>"+res.data[i].field_name+"</span>Name</li>"+
                          "<li class='col-3'><span>"+res.data[i].field_type+"</span>Filed</li>"+
                          "<li class='col-3 color7'><span>"+res.data[i].field_label+"</span>Lebel</li>"+
                          "<li class='col-3 color7'><span>"+res.data[i].field_data+"</span>Data</li>"
            }
         $('#filed-list').html(AllFiled);


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
   $('#filed-form').validate({
       rules:{
         lable: {
           required: true,

         },
         name:"required",
         value:"required"
       },
       messages: {
         lable: {
           required: "<p style='color:red;'>Please enter lable...</p>"
         },
         name: "<p style='color:red;'>Please enter name...</p>",
         value: "<p style='color:red;'>Please enter value...</p>"

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
  main_user['page_id']= localStorage.getItem('page_id');
  main_user['name']= data[0]['value'];
  main_user['label']= data[2]['value'];
  main_user['value']= data[3]['value'];
  main_user['type']= data[1]['value'];
    // console.log (JSON.stringify( main_user , null, '\t'));
    // alert("sdas");
  $.ajax({
     type: 'POST',
     url: '<?php echo $backend_ip; ?>index.php/site_pageAddFieldData',
     data: main_user,
     dataType: 'json',
     success: function (res){
         if(res){
          //  console.log(res);
           if(res['success'] == 1){

             var loc = window.location;
             var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
             window.location.replace(pathName + "createpages.php");
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
      name:"required",
      value:"required"
    },
    messages: {
      lable: {
        required: "<p style='color:red;'>Please enter lable...</p>"
      },
      name: "<p style='color:red;'>Please enter name...</p>",
      value: "<p style='color:red;'>Please enter value...</p>"

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
  main_user['page_id']= localStorage.getItem('page_id');
  main_user['name']= data[0]['value'];
  main_user['label']= data[2]['value'];
  main_user['value']= data[3]['value'];
  main_user['type']= data[1]['value'];
    console.log (JSON.stringify( main_user , null, '\t'));
    alert("sdas");
  $.ajax({
     type: 'POST',
     url: '<?php echo $backend_ip; ?>index.php/site_pageAddFieldData',
     data: main_user,
     dataType: 'json',
     success: function (res){
         if(res){
          //  console.log(res);
           if(res['success'] == 1){

             var loc = window.location;
             var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
             window.location.replace(pathName + "createpages.php");
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
      name: {
        required: true,

      },
      title:"required",
      description:"required"
    },
    messages: {
      name: {
        required: "<p style='color:red;'>Please enter Name...</p>"
      },
      title: "<p style='color:red;'>Please enter Title...</p>",
      description: "<p style='color:red;'>Please enter Description...</p>"

    },
      submitHandler: addseo

});
function addseo(){
  var main_user={};
  var data = $( "#seo_filed" ).serializeArray();
  main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
  main_user['token']= "<?php echo $_SESSION['token'];?>"
  main_user['site_id']= localStorage.getItem('site_id');
  main_user['site_api']= localStorage.getItem('set_api');
  main_user['page_id']= localStorage.getItem('page_id');
  main_user['name']= data[0]['value'];
  main_user['title']= data[2]['value'];
  main_user['description']= data[3]['value'];
  main_user['type']= data[1]['value'];
    console.log (JSON.stringify( main_user , null, '\t'));
    // alert("sdas");
  $.ajax({
     type: 'POST',
     url: '<?php echo $backend_ip; ?>index.php/site_pageAddFieldData',
     data: main_user,
     dataType: 'json',
     success: function (res){
         if(res){
          //  console.log(res);
           if(res['success'] == 1){

             var loc = window.location;
             var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
             window.location.replace(pathName + "createpages.php");
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
$('#seoimage').on('change', function(){
            var myfile = null;
            // $("#imagestatus").html("<i style='color:green;' class='fa fa-spinner fa-spin '></i>");
            setTimeout(function(){
              var form = $('#seo_filed')[0];
              var datafile = new FormData(form);
              alert('ok')
              //$("#ad_file").val("")
              // $.ajax({
              //     type: "POST",
              //     enctype: 'multipart/form-data',
              //     url: "<?php echo $backend_ip; ?>index.php/advertisement_add",
              //     data: datafile,
              //     processData: false,
              //     async: false,
              //     contentType: false,
              //     cache: false,
              //     timeout: 600000,
              //     success: function (res){
              //       if(res['success'] == 0){
              //         var loc = window.location;
              //         var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
              //         window.location.replace(pathName + "logout.php");
              //       }
              //       if(res['success'] == 2){
              //         alert("please select valid file...");
              //         $("#ad_file").val("");
              //         $("#imagestatus").html("");
              //       }
              //       if(res['success'] == 1){
              //         //console.log(JSON.stringify(res));
              //         $("#imagestatus").html("<i style='color:green;' class='fa fa-check' aria-hidden='true'></i>");
              //         myfile = res['image_name'];
              //         $("#hiddenfilename").val(myfile);
              //         $("#hiddenfiletype").val(res['type_ogg']);
              //
              //         // if( res['type'] == "image"){
              //         //   $("#imagestatus").html("");
              //         //   var myimage = "<img src='<?php echo $backend_ip; ?>demo_upload/"+res['image_name']+"' height='100px' width='150px'>";
              //         //   $("#imagestatus").html(myimage);
              //         // }else if (res['type'] == "audio") {
              //         //   $("#imagestatus").html("");
              //         //   var myaudio = "<audio controls><source src='<?php echo $backend_ip; ?>demo_upload/"+res['image_name']+"' type='"+res['type_ogg']+"'><source src='<?php echo $backend_ip; ?>demo_upload/"+res['image_ogg']+".ogg' type='"+res['type']+"/ogg'><p>Your browser does not support the audio tag</p></audio>"
              //         //   $("#imagestatus").html(myaudio);
              //         // }else{
              //         //   $("#imagestatus").html("");
              //         //   var myvideo = "<video width='150px' height='100px' controls><source src='<?php echo $backend_ip; ?>demo_upload/"+res['image_name']+"' type='"+res['type_ogg']+"'><source src='<?php echo $backend_ip; ?>demo_upload/"+res['image_ogg']+".ogg' type='"+res['type']+"/ogg'><p>Your browser does not support the video tag</p></video>"
              //         //   $("#imagestatus").html(myvideo);
              //         // }
              //
              //       }
              //     },
              //     error: function (e) {
              //         console.log("ERROR : ", e);
              //     }
              // });
            }, 200);

          });
   </script>

</html>
