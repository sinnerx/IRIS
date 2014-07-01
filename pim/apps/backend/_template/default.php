<!DOCTYPE html>
<html lang="en" class="app">
<head>  
  <meta charset="utf-8" />
  <title>P1M Dashboard | Web Application</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="<?php echo url::asset("_scale/css/bootstrap.css");?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo url::asset("_scale/css/animate.css");?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo url::asset("_scale/css/font-awesome.min.css");?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo url::asset("_scale/css/icon.css");?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo url::asset("_scale/css/font.css");?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo url::asset("_scale/css/app.css");?>" type="text/css" />
  <script src="<?php echo url::asset("_scale/js/jquery.min.js");?>"></script>
  <script type="text/javascript" src='<?php echo url::asset("backend/js/pim.js");?>'></script>
  <script type="text/javascript">
  var pim = new pim({base_url:"<?php echo url::base();?>"});
  var p1mloader = new function()
  {
    this.id = false;

    // add listener on every ajax complete.
    $(document).ajaxComplete(function(){
      p1mloader.stop();
    });
    this.start  = function(id)
    {
      if(id)
      {
        this.id = id;
      }
      var id  = !id?"#main-content-wrapper":id;
      $(id).addClass("animated fadeOut");
    }

    this.stop = function(id)
    {
      var id  = !id?(this.id?this.id:"#main-content-wrapper"):id;
      $(id).removeClass("fadeOut").addClass("fadeIn flipInX");
      setTimeout(function()
      {
        //remove class after 500ms
        $(id).removeClass("fadeIn flipInX");
      },500);
      
      this.id = false;
    }
  }
  </script>
    <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<style type="text/css">
  
.main-info
{
  border-top:1px solid #c7cfe0;
  border-bottom:1px solid #c7cfe0;
  padding:5px;
  margin-bottom: 20px;
}

#view-site-link
{
  border-left:1px dashed #cecece;
  color: #8b8b8b;
  letter-spacing: 1px;
}

#view-site-link span:last-child
{
  display:inline;
}
  
</style>
<body class="">
  <section class="vbox">
    <header class="bg-white header header-md navbar navbar-fixed-top-xs box-shadow">
      <div class="navbar-header aside-md dk">
        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
          <i class="fa fa-bars"></i>
        </a>
        <a href="" class="navbar-brand"><img src="<?php echo url::asset("_scale/images/logo.png");?>"><span <?php echo $siteHref;?> ><?php echo $dashboardTitle;?> </span></a>
        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".user">
          <i class="fa fa-cog"></i>
        </a>
      </div>
      <?php if(session::get("userLevel") == 2):?>
      <ul class="nav navbar-nav hidden-xs">
        <li>
          <a href="<?php echo $siteHref;?>" target="_blank" id='view-site-link'><span class='btn btn-primary'>Site Preview</span></a>
        </li>
      </ul>
    <?php endif;?>
    <?php /*
      <ul class="nav navbar-nav hidden-xs">
        <li class="dropdown" style='display:none;'>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="i i-grid"></i>
          </a>
          <section class="dropdown-menu aside-lg bg-white on animated fadeInLeft">
            <div class="row m-l-none m-r-none m-t m-b text-center">
              <div class="col-xs-4">
                <div class="padder-v">
                  <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-mail i-2x text-primary-lt"></i>
                    </span>
                    <small class="text-muted">Mailbox</small>
                  </a>
                </div>
              </div>
              <div class="col-xs-4">
                <div class="padder-v">
                  <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-calendar i-2x text-danger-lt"></i>
                    </span>
                    <small class="text-muted">Calendar</small>
                  </a>
                </div>
              </div>
              <div class="col-xs-4">
                <div class="padder-v">
                  <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-map i-2x text-success-lt"></i>
                    </span>
                    <small class="text-muted">Map</small>
                  </a>
                </div>
              </div>
              <div class="col-xs-4">
                <div class="padder-v">
                  <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-paperplane i-2x text-info-lt"></i>
                    </span>
                    <small class="text-muted">Trainning</small>
                  </a>
                </div>
              </div>
              <div class="col-xs-4">
                <div class="padder-v">
                  <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-images i-2x text-muted"></i>
                    </span>
                    <small class="text-muted">Photos</small>
                  </a>
                </div>
              </div>
              <div class="col-xs-4">
                <div class="padder-v">
                  <a href="#">
                    <span class="m-b-xs block">
                      <i class="i i-clock i-2x text-warning-lter"></i>
                    </span>
                    <small class="text-muted">Timeline</small>
                  </a>
                </div>
              </div>
            </div>
          </section>
        </li>
      </ul>
      <!-- Search box -->
      <form class="navbar-form navbar-left input-s-lg m-t m-l-n-xs hidden-xs" role="search">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-sm bg-white b-white btn-icon"><i class="fa fa-search"></i></button>
            </span>
            <input type="text" class="form-control input-sm no-border" placeholder="Search something...">            
          </div>
        </div>
      </form>
      */?>
      <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">
        <?php ## Notification
        /*<li class="hidden-xs">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="i i-chat3"></i>
            <span class="badge badge-sm up bg-danger count">2</span>
          </a>
          <section class="dropdown-menu aside-xl animated flipInY">
            <section class="panel bg-white">
              <header class="panel-heading b-light bg-light">
                <strong>You have <span class="count">2</span> notifications</strong>
              </header>
              <div class="list-group list-group-alt">
                <a href="#" class="media list-group-item">
                  <span class="pull-left thumb-sm">
                    <img src="images/a0.png" alt="John said" class="img-circle">
                  </span>
                  <span class="media-body block m-b-none">
                    Use awesome animate.css<br>
                    <small class="text-muted">10 minutes ago</small>
                  </span>
                </a>
                <a href="#" class="media list-group-item">
                  <span class="media-body block m-b-none">
                    1.0 initial released<br>
                    <small class="text-muted">1 hour ago</small>
                  </span>
                </a>
              </div>
              <footer class="panel-footer text-sm">
                <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                <a href="#notes" data-toggle="class:show animated fadeInRight">See all the notifications</a>
              </footer>
            </section>
          </section>
        </li>*/?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="thumb-sm avatar pull-left">
              <img src="<?php echo url::asset("_scale/images/a0.png");?>">
            </span>
            <?php echo $userFullName;?> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu animated fadeInRight">
            <span class="arrow top"></span>
            <?php /*<li>
              <a href="#">Settings</a>
            </li>
            <li>
              <a href="<?php echo url::base("user/profile");?>">Profile</a>
            </li>
            <li>
              <a href="#">
                <span class="badge bg-danger pull-right">3</span>
                Notifications
              </a>
            </li>
            <li>
              <a href="docs.html">Help</a>
            </li>
            <li class="divider"></li>*/?>
            <li>
              <a href="<?php echo url::base("user/profile");?>">My Profile</a>
            </li>
            <li>
              <a href="<?php echo url::base("user/changePassword");?>">Change Password</a>
            </li>
            <!-- <li>
              <a href='<?php echo url::base("account/my_transaction");?>'>Account's Transaction</a>
            </li> -->
            <li class="divider"></li>
            <li>
              <a href="<?php echo url::base("logout");?>"  >Logout</a>
            </li>
          </ul>
        </li>
      </ul>      
    </header>
    <section>
      <section class="hbox stretch">
        <!-- .aside -->
        <aside class="bg-black aside-md hidden-print" id="nav">          
          <section class="vbox">
                        <section class="w-f scrollable">
              <div class=slim-scroll data-height=auto data-disable-fade-out=true data-distance=0 data-size=10px data-railOpacity=0.2>
                <div class="clearfix wrapper dk nav-user hidden-xs">
      <div class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <span class="thumb avatar pull-left m-r">                        
      <img src="<?php echo url::asset("_scale/images/a0.png");?>" class="dker">
      <i class="on md b-black"></i>
    </span>
    <span class="hidden-nav-xs clear">
      <span class="block m-t-xs">
        <strong class="font-bold text-lt"><?php echo $userFullName;?></strong> 
        <b class="caret"></b>
      </span>
      <span class="text-muted text-xs block"><?php echo $userLevel;?></span>
    </span>
  </a>
  <ul class="dropdown-menu animated fadeInRight m-t-xs">
    <?php
    /*<span class="arrow top hidden-nav-xs"></span>
    <li>
      <a href="#">Settings</a>
    </li>
    <li>
      <a href="<?php echo url::base("user/profile");?>">Profile</a>
    </li>
    <li>
      <a href="#">
        <span class="badge bg-danger pull-right">3</span>
        Notifications
      </a>
    </li>
    <li>
      <a href="#">Help</a>
    </li>*/?>
    <li>
      <a href="<?php echo url::base("user/profile");?>">My Profile</a>
    </li>
    <li>
      <a href="<?php echo url::base("user/changePassword");?>">Change Password</a>
    </li>
    <!-- <li>
      <a href='<?php echo url::base("account/my_transaction");?>'>Account's Transaction</a>
    </li> -->
    <li class="divider"></li>
    <li>
      <a href="<?php echo url::base("logout");?>" >Logout</a>
    </li>
  </ul>
</div>
      </div>
        <nav class="nav-primary hidden-xs">
          <ul class="nav nav-main" data-ride="collapse">                
            <?php
              $controller = controller::getCurrentController();
              $v          = explode("/",$controller);
              $controller = array_pop($v);## pop last name from it.
              $method   = controller::getCurrentMethod();

              foreach($menu as $menu_name=>$module)
              {
                if(!is_array($module))
                {
                  

                  list($menu_con,$menu_meth)  = explode("/",$module);

                  $url    = url::base($module);

                  $active = $module == $controller."/".$method?"class='active'":"";

                  echo '<li '.$active.'><a href="'.$url.'" class="auto">
                        <i class="i i-statistics icon"></i>
                        <span class="font-bold">'.$menu_name.'</span>
                        </a></li>';
                }
                else
                {
                  $active  = in_array($controller."/".$method,$module)?"class='active'":"";

                  foreach($module as $submod)
                  {
                    if(is_array($submod))
                    {
                      $active = in_array($controller."/".$method, $submod)?"class='active'":$active;
                    }
                  }

                  echo '<li '.$active.'><a href="javascript:void(0);" class="auto">
                        <span class="pull-right text-muted">
                          <i class="i i-circle-sm-o text"></i>
                          <i class="i i-circle-sm text-active"></i>
                        </span>
                        <i class="i i-stack icon">
                        </i>
                        <span class="font-bold">'.$menu_name.'</span>
                      </a>';

                  echo "<ul class='nav dk'>";
                  foreach($module as $sub_menuname=>$submod)
                  {
                    if(is_array($submod))
                    {
                      $sub_active   = in_array($controller."/".$method, $submod)?"class='active'":"";
                      $submod = $submod[0];
                    }
                    else
                    {
                      list($menu_con,$menu_meth)  = explode("/",$submod);
                      $sub_active   = $controller == $menu_con && $method == $menu_meth?"class='active'":"";
                    }

                    $url  = url::base($submod);
                    
                    echo '<li '.$sub_active.'>
                          <a href="'.$url.'" class="auto">                                                        
                            <i class="i i-dot"></i>
                            <span>'.$sub_menuname;
                    echo '</span>
                          </a>
                        </li>';
                  }
                  echo "</ul></li>";
                }
              }
              ?>
        </ul>
        </nav>
              </div>
            </section>
            
            <footer class="footer hidden-xs no-padder text-center-nav-xs">
              <!-- <a href="modal.lockme.html" data-toggle="ajaxModal" class="btn btn-icon icon-muted btn-inactive pull-right m-l-xs m-r-xs hidden-nav-xs">
                <i class="i i-logout"></i>
              </a> -->
              <a href="#nav" data-toggle="class:nav-xs" class="btn btn-icon icon-muted btn-inactive m-l-xs m-r-xs">
                <i class="i i-circleleft text"></i>
                <i class="i i-circleright text-active"></i>
              </a>
            </footer>
          </section>
        </aside>
        <!-- /.aside -->
        <section id="content">
          <section class="vbox">          
            <section class="scrollable padder" id='main-content-wrapper'>
              <!-- CONTENT START AFTER HERE -->
              <?php template::showContent();?>
              <!-- CONTENT END HERE -->
            </section>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>
      </section>
    </section>
  </section>
  <!-- Bootstrap -->
  <script src="<?php echo url::asset("_scale/js/bootstrap.js");?>"></script>
  <!-- App -->
  <script src="<?php echo url::asset("_scale/js/app.js");?>"></script>  
  <script src="<?php echo url::asset("_scale/js/slimscroll/jquery.slimscroll.min.js");?>"></script>
    <script src="<?php echo url::asset("_scale/js/app.plugin.js");?>"></script>
</body>
</html>