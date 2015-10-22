<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Drug Repository Application</title>

    <link href=<?php echo css_url().'bootstrap.min.css'?> rel="stylesheet">
    <link href=<?php echo css_url().'dashboard.css'?> rel="stylesheet">
    <script src=<?php echo js_url().'jquery-2.1.3.min.js';?>></script>
    <script src=<?php echo js_url().'bootstrap.min.js';?>></script>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href=<?php echo base_url()?>>Drug Repository Application</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href=<?php echo site_url("user/signin")?>>Sign In</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>