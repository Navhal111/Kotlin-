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
       <!-- <script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script> -->
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
       <li><a href="#"><span class="icon color7"><i class="fa fa-edit"></i></span>Content<span class="caret"></span></a>
         <ul id = "content_ist">

         </ul>
       </li>
       <li><a href="<?php echo $admin_ip; ?>pages.php"><span class="icon color7"><i class="fa fa-sitemap"></i></span>Pages</a>
         <!-- <ul id = "modle_ist">

         </ul> -->
       </li>
       <li><a href="<?php echo $admin_ip; ?>setting.php"><span class="icon color12"><i class="fa fa-cogs"></i></span>Settings</a></li>

     </ul>


     </div>
     <div class="content">
         <div class="col-lg-6">
           <div class="panel panel-widget">
             <div class="panel-title" id="content_title">
          Select Content
         </div>

             <div class="panel-body">

                 <fieldset>
                   <ul class="mailbox-inbox" id="page_list_content">

                   </ul>

                 </fieldset>

             </div>

       </div>
     </div>
  </div>
  <div class="content">
      <div class="col-lg-6">
        <div class="panel panel-widget">
          <div class="panel-title" id="page_title">
       Select page
      </div>

          <div class="panel-body">

            <form class="fieldset-form" id="edit_page" enctype="multipart/form-data">
                <div id="page_edit_form">


                <div>

            </form>

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
   <div class="modal fade" id="myModalCreate4" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Add Page</h4>
         </div>
         <div class="modal-body">
           <form class="fieldset-form" id="add_page" enctype="multipart/form-data">
             <fieldset>
               <div id="main_page">


               <div>

            </fieldset>

           </form>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
         </div>
       </div>
     </div>
   </div>

   <!-- <div class="modal fade" id="1page_edit_model" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Edit Pages</h4>
         </div>
         <div class="modal-body">
           <div class="panel panel-default">
             <div class="panel-body">
               <form class="fieldset-form" id="edit_page" enctype="multipart/form-data">
                   <div id="1page_edit_form">


                   <div>

               </form>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div> -->
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


        // $('.page_image').change(function() {
        //     alert('ok')
        //     var filename = $('#image_file').val();
        //     $('#select_file').html(filename);
        // });

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
                    ListModle += "<li><a data-id='"+res.data[i].model_id+"' data-name='"+res.data[i].name+"' class='content_list_bar'>"+res.data[i].name+"</a></li>"
                   }
                   var AllSite;
                  //  var pagecount = 0
                  //  for(var i=0;i<res.data.length;i++){
                  //    AllSite += "<tr>"+
                  //     "<td><i class='fa fa-folder-o'></i>"+res.data[i].name+"</td>"+
                  //     "<td class='text-r'><a data-id='"+res.data[i].model_id+"' class='btn btn-rounded btn-option1 manage_site' >Manage Page</a>&nbsp&nbsp&nbsp<a data-id='"+res.data[i].model_id+"' class='btn btn-rounded btn-danger site_id'>Remove</a></td>"+
                  //   "</tr>";
                  //   pagecount = pagecount+1;
                  //  }
                  $('#content_ist').html(ListModle);
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

     });

     $(document).on('click', '.content_list_bar', function(){
       var main_user={};
       main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
       main_user['token']= "<?php echo $_SESSION['token'];?>"
       main_user['site_id']= localStorage.getItem('site_id');
       main_user['site_api']= localStorage.getItem('set_api');
       main_user['model_id']= $(this).data("id");
      //  alert($(this).data("name"));
      var content='<ul class="panel-tools">'+
                 '<li><a data-id="'+$(this).data("id")+'" data-toggle="modal" data-target="#myModalCreate4" class="btn btn-default" id="model_id"><i class="fa fa-plus-circle"></i>Create New Page</a></li>'+
               '</ul>'
      $('#content_title').html($(this).data("name")+'->Pages'+content);
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
                 list_page_model(main_user['model_id']);
                 var AllFiled = " "


                    for(var i=0;i<res.fields.length;i++){
                    if(res.fields[i].field_type=='text'){
                      var requid =" "
                      var match = "text"
                      if(res.fields[i].validator.required=="on"){
                        requid = "required"
                      }
                      if(res.fields[i].validator.match_spec_pattern=="email"){
                        match = "email"
                      }
                      AllFiled +='<div class="form-group">'+
                                  '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                  ' <input type="'+match+'" id ="filed-form" name="'+res.fields[i].label+'" class="form-control" id="example10" '+requid+'>'+
                                  '</div>'


                    }
                    if(res.fields[i].field_type=='seo'){
                      var requid =" "
                      if(res.fields[i].validator.required=="on"){
                        requid = "required"
                      }
                      AllFiled +='<fieldset>'+
                                 '<legend>Seo</legend>'+
                                 '<div class="form-group">'+
                                  '<label for="example10" class="form-label">Name</label>'+
                                  ' <input type="text" id ="filed-form" name="seo_name" class="form-control" id="example10" '+requid+'>'+
                                  '</div>'+
                                 '<div class="form-group">'+
                                 '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                 ' <textarea class="form-control" name="'+res.fields[i].label+'" rows="3" id="textarea1" placeholder="Type your description..." '+requid+'></textarea>'+
                                 '<div>'+
                                 '</fieldset>'

                    }
                    if(res.fields[i].field_type=='image'){
                      var requid =" "
                      if(res.fields[i].validator.required=="on"){
                        requid = "required"
                      }
                      AllFiled +='<div class="form-group">'+
                                '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                  '<input type="file" name = "'+res.fields[i].label+'" class="page_image" value=" ">'+
                                  '<input type="hidden" name = "'+res.fields[i].label+'_name" id="page_image_name_'+res.fields[i].label+'" value="">'+
                                  '<br>'+
                                  '<img src=" " id="image_set_content_'+res.fields[i].label+'" alt="Select _image" class="img" height="50px" width="50px">'+
                                  '</div>'


                    }

                    }


                  AllFiled+=
                            '<input type="hidden" name="lable_name" id="lable_name" value="">'+
                            '<br><input type="submit" class="btn btn-default btn-block" value="Add">'

              $('#main_page').html(AllFiled);
              $('#page_edit_form').html(' ');
              $('#page_title').text('Select Page');

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

     $( "#edit_page" ).submit(function(e) {
         e.preventDefault();
        var main_user ={};
        var data = $( "#edit_page" ).serializeArray();
        // var form = $('#add_page')[0]; // You need to use standard javascript object here
        // var formData = new FormData(form);
        main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
        main_user['token']= "<?php echo $_SESSION['token'];?>"
        main_user['site_id']= localStorage.getItem('site_id');
        main_user['site_api']= localStorage.getItem('set_api');
        main_user['model_id']= $('#model_id').data("id");
        var indexed_array = {};
        $.map(data, function(n, i){
          indexed_array[n['name']] = n['value'];
        });
        main_user['data']=indexed_array;
        console.log (JSON.stringify( main_user , null, '\t'));
        // $.ajax({
        //    type: 'POST',
        //    url: '<?php echo $backend_ip; ?>index.php/site_editor_model_record_edit_submit',
        //    data: main_user,
        //    dataType: 'json',
        //    success: function (res){
        //        if(res){
        //          // console.log (JSON.stringify( res , null, '\t'));
        //          if(res['success'] == 1){
        //             page_model_edit(indexed_array.record_data_id);
        //
        //              alert('update success fully')
        //           //  var ListModle="";
        //           //  for(var i=0;i<res.data.length;i++){
        //           //    ListModle += "<li><a data-id='"+res.data[i].model_id+"' data-name='"+res.data[i].name+"' class='content_list_bar'>"+res.data[i].name+"</a></li>"
        //           //   }
        //           //   var AllSite;
        //
        //          }else{
        //            alert(res['msg']);
        //          }
        //        }else{
        //          alert('somthing went wrong...');
        //          var loc = window.location;
        //          var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
        //          window.location.replace(pathName + "dashboard.php");
        //        }
        //    },
        //    error: function(response, status, xhr){
        //        alert("Plz...Login try letter...");
        //    }
        //  });

      });

     $( "#add_page" ).submit(function(e) {
      //  alert('ok')
        var main_user ={};
        var data = $( "#add_page" ).serializeArray();
        main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
        main_user['token']= "<?php echo $_SESSION['token'];?>"
        main_user['site_id']= localStorage.getItem('site_id');
        main_user['site_api']= localStorage.getItem('set_api');
        main_user['model_id']= $('#model_id').data("id");
        var indexed_array = {};
         e.preventDefault();
        $.map(data, function(n, i){
          indexed_array[n['name']] = n['value'];
        });
        main_user['data']=indexed_array;
        console.log (JSON.stringify( indexed_array , null, '\t'));

        $.ajax({
           type: 'POST',
           url: '<?php echo $backend_ip; ?>index.php/site_editor_create_record_add',
           data: main_user,
           dataType: 'json',
           success: function (res){
               if(res){
                 // console.log (JSON.stringify( res , null, '\t'));
                 if(res['success'] == 1){
                   alert('Add page successfully')
                   list_page_model(main_user['model_id']);

                   $(".close").click()
                   $("#add_page :input").each(function(){
                     $(this).val('');
                     });
                  //  $
                     // alert('add success fully')
                  //  var ListModle="";
                  //  for(var i=0;i<res.data.length;i++){
                  //    ListModle += "<li><a data-id='"+res.data[i].model_id+"' data-name='"+res.data[i].name+"' class='content_list_bar'>"+res.data[i].name+"</a></li>"
                  //   }
                  //   var AllSite;

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

      function list_page_model(model_id) {
        var main_user = {}
        main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
        main_user['token']= "<?php echo $_SESSION['token'];?>"
        main_user['site_id']= localStorage.getItem('site_id');
        main_user['site_api']= localStorage.getItem('set_api');
        main_user['model_id']= model_id;
        $.ajax({
           type: 'POST',
           url: '<?php echo $backend_ip; ?>index.php/site_editor_model_record_list',
           data: main_user,
           dataType: 'json',
           success: function (res){
               if(res){
                 if(res['success'] == 1){
                  //  console.log (JSON.stringify( res , null, '\t'));

                   var AllFiled=" "
                   var title = "No Title"
                   for(var i=0;i<res.recordlist.length;i++ ){
                     if(res.recordlist[i].data.title){
                        title =res.recordlist[i].data.title
                     }
                     AllFiled +='<li>'+
                                   '<a href="#" data-toggle="modal" data-target="#page_edit_model" data-id="'+res.recordlist[i].items_id+'" class="item clearfix page_model_edit">'+
                                     '<i class="fa fa-file-code-o fa-3x img"></i>'+
                                     '<span class="from">'+title+'</span>'+
                                     res.recordlist[i].modify_at+
                                     '<span class="date"></span>'+
                                   '</a>'+
                                 '</li>'
                   }


                  $('#page_list_content').html(AllFiled);
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

      function page_model_edit(page_id){
        var main_user={};
        main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
        main_user['token']= "<?php echo $_SESSION['token'];?>"
        main_user['site_id']= localStorage.getItem('site_id');
        main_user['site_api']= localStorage.getItem('set_api');
        main_user['model_id']= $('#model_id').data("id");
        main_user['record_id']= page_id;
        // alert(main_user['model_id']);

        $.ajax({
           type: 'POST',
           url: '<?php echo $backend_ip; ?>index.php/site_editor_model_record_edit',
           data: main_user,
           dataType: 'json',
           success: function (res){
               if(res){
                //  console.log (JSON.stringify( res , null, '\t'));

                 if(res['success'] == 1){
                      var record_value = res.record_data.attribute;
                      // console.log (JSON.stringify( record_value , null, '\t'));
                      if(record_value.title){

                          $('#page_title').text('PAGES->'+record_value.title);

                      }else{
                          $('#page_title').text('PAGES->No Title');
                      }
                    var AllFiled = '<input type="hidden" id ="" name="record_data_id" class="form-control" id="example10" value="'+main_user['record_id']+'">'

                      AllFiled +=""
                       for(var i=0;i<res.fields.length;i++){
                       if(res.fields[i].field_type=='text'){
                         var requid =" "
                         var match = "text"
                         if(res.fields[i].validators.required=="on"){
                           requid = "required"
                         }
                         if(res.fields[i].validators.match_spec_pattern=="email"){
                           match = "email"
                         }
                         AllFiled +='<div class="form-group">'+
                                     '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                     ' <input type="'+match+'" id ="filed-form" name="'+res.fields[i].label+'" class="form-control" id="example10" value="'+record_value[res.fields[i].label]+'" '+requid+'>'+
                                     '</div>'


                       }
                       if(res.fields[i].field_type=='seo'){
                         var requid =" "
                         if(res.fields[i].validators.required=="on"){
                           requid = "required"
                         }
                         var value = record_value[res.fields[i].label].replace(/\#/g, "\n");
                         AllFiled +='<fieldset>'+
                                    '<legend>Seo</legend>'+
                                    '<div class="form-group">'+
                                     '<label for="example10" class="form-label">Name</label>'+
                                     ' <input type="text" id ="filed-form" name="seo_name" class="form-control" id="example10" value="'+record_value['seo_name']+'" '+requid+'>'+
                                     '</div>'+
                                    '<div class="form-group">'+
                                    '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                    ' <textarea class="form-control" name="'+res.fields[i].label+'" rows="3" id="textarea1" placeholder="Type your description..." '+requid+'>'+value+'</textarea>'+
                                    '<div>'+
                                    '</fieldset>'

                       }
                       if(res.fields[i].field_type=='image'){
                         var requid =" "
                         if(res.fields[i].validators.required=="on"){
                           requid = "required"
                         }
                         AllFiled +='<div class="form-group">'+
                                     '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                     '<input type="file" name = "'+res.fields[i].label+'" class="page_image">'+
                                     '</div>'


                       }

                       }


                     AllFiled+='<br><input type="submit" class="btn btn-default btn-block" value="Add">'
                    $('#page_edit_form').html(AllFiled);

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

      $(document).on('click', '.page_model_edit', function(){
        var main_user={};
        main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
        main_user['token']= "<?php echo $_SESSION['token'];?>"
        main_user['site_id']= localStorage.getItem('site_id');
        main_user['site_api']= localStorage.getItem('set_api');
        main_user['model_id']= $('#model_id').data("id");
        main_user['record_id']= $(this).data("id");
        // alert(main_user['model_id']);

        $.ajax({
           type: 'POST',
           url: '<?php echo $backend_ip; ?>index.php/site_editor_model_record_edit',
           data: main_user,
           dataType: 'json',
           success: function (res){
               if(res){
                //  console.log (JSON.stringify( res , null, '\t'));

                 if(res['success'] == 1){
                      var record_value = res.record_data.attribute;
                      // console.log (JSON.stringify( record_value , null, '\t'));
                      if(record_value.title){

                          $('#page_title').text('PAGES->'+record_value.title);

                      }else{
                          $('#page_title').text('PAGES->No Title');
                      }
                    var AllFiled = '<input type="hidden" id ="" name="record_data_id" class="form-control" id="example10" value="'+main_user['record_id']+'">'

                      AllFiled +=""
                       for(var i=0;i<res.fields.length;i++){
                       if(res.fields[i].field_type=='text'){
                         var requid =" "
                         var match = "text"
                         if(res.fields[i].validators.required=="on"){
                           requid = "required"
                         }
                         if(res.fields[i].validators.match_spec_pattern=="email"){
                           match = "email"
                         }
                         AllFiled +='<div class="form-group">'+
                                     '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                     ' <input type="'+match+'" id ="filed-form" name="'+res.fields[i].label+'" class="form-control" id="example10" value="'+record_value[res.fields[i].label]+'" '+requid+'>'+
                                     '</div>'


                       }
                       if(res.fields[i].field_type=='seo'){
                         var requid =" "
                         if(res.fields[i].validators.required=="on"){
                           requid = "required"
                         }
                                      var value = record_value[res.fields[i].label].replace(/\#/g, "\n");
                         AllFiled +='<fieldset>'+
                                    '<legend>Seo</legend>'+
                                    '<div class="form-group">'+
                                     '<label for="example10" class="form-label">Name</label>'+
                                     ' <input type="text" id ="filed-form" name="seo_name" class="form-control" id="example10" value="'+record_value['seo_name']+'" '+requid+'>'+
                                     '</div>'+
                                    '<div class="form-group">'+
                                    '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                    ' <textarea class="form-control" name="'+res.fields[i].label+'" rows="3" id="textarea1" placeholder="Type your description..." '+requid+'>'+value+'</textarea>'+
                                    '<div>'+
                                    '</fieldset>'

                       }
                       if(res.fields[i].field_type=='image'){
                         var requid =" "
                         if(res.fields[i].validators.required=="on"){
                           requid = "required"
                         }
                         AllFiled +='<div class="form-group">'+
                                     '<label for="example10" class="form-label">'+res.fields[i].name+'</label>'+
                                     '<input type="file" name = "'+res.fields[i].label+'" class="page_edit_image">'+
                                     '<img src="<?php echo $backend_ip ?>upload/'+record_value[res.fields[i].label]+'" id="image_edit_content_'+res.fields[i].label+'" alt="Select _image" class="img" height="50px" width="50px">'+
                                     '<input type="hidden" name = "'+res.fields[i].label+'_name" id="page_image_name_'+res.fields[i].label+'" value="">'+
                                     '</div>'


                       }

                       }


                     AllFiled+='<input type="hidden" name="lable_name" id="edit_lable_name" value="">'+
                     '<br><input type="submit" class="btn btn-default btn-block" value="Add">'
                    $('#page_edit_form').html(AllFiled);

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
      $(document).on('change', '.page_image',function (e) {
        // $('#add_page')[0].files[0]
        var fileName = e.target.files[0].name;

        // alert('The file "' + fileName +  '" has been selected.');
        // alert('ok');
        var name=$(this).attr('name');
        $("#lable_name").val(name);

        var form = $('#add_page')[0];
        var datafile = new FormData(form);
        // alert($(this).attr('name'));
        // console.log(datafile);
        // console.log(e.target.files[0]);

        $.ajax({
                          type: "POST",
                          enctype: 'multipart/form-data',
                          url: "<?php echo $backend_ip; ?>index.php/get_file",
                          data: datafile,
                          processData: false,
                          async: false,
                          contentType: false,
                          cache: false,
                          timeout: 600000,
                          success: function (res){
                            if(res['success'] == 0){

                              // var loc = window.location;
                              // var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                              // window.location.replace(pathName + "logout.php");
                            }
                            if(res['success'] == 2){
                              alert("please select valid file...");

                            }
                            if(res['success'] == 1){
                              alert('uploaded image')
                              $("#page_image_name_"+name).val(res.filename);
                              $('#image_set_content_'+name).attr("src",'<?php echo $backend_ip ?>demo_upload/'+res.filename);

                              // alert()
                            }
                          },
                          error: function (e) {
                              console.log("ERROR : ", e);
                          }
                  });


      });

      $(document).on('change', '.edit_image',function (e) {
        // $('#add_page')[0].files[0]
        var fileName = e.target.files[0].name;

        // alert('The file "' + fileName +  '" has been selected.');
        // alert('ok');
        var name=$(this).attr('name');
        $("#edit_lable_name").val(name);

        var form = $('#edit_page')[0];
        var datafile = new FormData(form);
        // alert($(this).attr('name'));
        // console.log(datafile);
        // console.log(e.target.files[0]);

        $.ajax({
                          type: "POST",
                          enctype: 'multipart/form-data',
                          url: "<?php echo $backend_ip; ?>index.php/get_file",
                          data: datafile,
                          processData: false,
                          async: false,
                          contentType: false,
                          cache: false,
                          timeout: 600000,
                          success: function (res){
                            if(res['success'] == 0){

                              // var loc = window.location;
                              // var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                              // window.location.replace(pathName + "logout.php");
                            }
                            if(res['success'] == 2){
                              alert("please select valid file...");

                            }
                            if(res['success'] == 1){
                              alert('uploaded image')
                              $("#page_image_name_"+name).val(res.filename);
                              $('#image_set_content_'+name).attr("src",'<?php echo $backend_ip ?>demo_upload/'+res.filename);

                              // alert()
                            }
                          },
                          error: function (e) {
                              console.log("ERROR : ", e);
                          }
                  });


      });

     </script>
 </html>
