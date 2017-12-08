<!-- Start Page Loading -->
<div class="loading"><img src="img/loading.gif" alt="loading-img"></div>
<!-- End Page Loading -->
<!-- //////////////////////////////////////////////////////////////////////////// -->
<!-- START TOP -->
<div id="top" class="clearfix">

  <!-- Start App Logo -->
  <div class="applogo">
    <a href="<?php echo $admin_ip; ?>index.php" class="logo">HUGO cms</a>
  </div>
  <!-- End App Logo -->

  <!-- Start Sidebar Show Hide Button -->
  <a href="<?php echo $admin_ip; ?>dashboard.php" class="sidebar-open-button"><i class="fa fa-bars"></i></a>
  <a href="#" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i></a>
  <!-- End Sidebar Show Hide Button -->

  <!-- Start Searchbox -->

  <!-- End Top Menu -->

  <!-- Start Sidepanel Show-Hide Button -->
  <a href="#sidepanel" class="sidepanel-open-button"><i class="fa fa-outdent"></i></a>
  <!-- End Sidepanel Show-Hide Button -->

  <!-- Start Top Right -->
  <ul class="top-right">
  <li class="dropdown link">
    <a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox"><b><?php echo $_SESSION['email']; ?></b><span class="caret"></span></a>
      <ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
        <li role="presentation" class="dropdown-header">Profile</li>
        <li><a href="#"><i class="fa falist fa-inbox"></i>Inbox<span class="badge label-danger">4</span></a></li>
        <li><a href="#"><i class="fa falist fa-file-o"></i>Files</a></li>
        <li><a href="#"><i class="fa falist fa-wrench"></i>Settings</a></li>
        <li class="divider"></li>
        <li><a href="#"><i class="fa falist fa-lock"></i> Lockscreen</a></li>
        <li><a href="logout.php"><i class="fa falist fa-power-off"></i> Logout</a></li>
      </ul>
  </li>

  </ul>
  <!-- End Top Right -->

</div>
<!-- END TOP -->
