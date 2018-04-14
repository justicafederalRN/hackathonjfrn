<?php
$appConfig = $application->loadConfig('app');
$theme     = isset($appConfig['theme']) ? $appConfig['theme'] : 'blue';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
      <?php if ($this->ui->hasPageTitle()):?>
          <?=$this->ui->getPageTitle()?>
          |
      <?php endif?>
      <?=$this->ui->getProjectName()?>
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Admin styles -->
  <link rel="stylesheet" href="<?=$this->url('/assets/dist/css/admin.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$this->url('/assets/components/admin/dist/css/skins/skin-' . $theme . '.min.css')?>">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
      .pdfobject-container {
          width:100%;
          min-height:400px;
      }
      .pdf-object {
          width:100%;
          height:700px;
      }
  </style>
  <?=$this->renderBlock('css')?>
  <?=$this->renderBlock('head')?>
</head>
<body class="hold-transition skin-<?=$theme?> sidebar-mini<?=isset($collapsed) && $collapsed ? ' sidebar-collapse' : ''?>">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a class="logo" href="<?=$this->routeUrl('admin.dashboard')?>">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><?=$appConfig['display_name_short']?></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <?php 
            if (!empty($appConfig['internal_logo'])):
                if (is_callable($appConfig['internal_logo'])) {
                    $appConfig['internal_logo'] = call_user_func($appConfig['internal_logo'], $this);
                }
            ?>
                <img src="<?= $appConfig['internal_logo']?>" 
                    alt="<?=$appConfig['display_name']?>"
                    height="35"/>
            <?php else:?>
                <?=$appConfig['display_name']?>
            <?php endif;?>
        </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <?php if (false): ?>
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        <?php endif; ?>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <?php if (false): ?>
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-envelope-o"></i>
                    <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                    <li class="header">You have 4 messages</li>
                    <li>
                        <!-- inner menu: contains the messages -->
                        <ul class="menu">
                        <li><!-- start message -->
                            <a href="#">
                            <div class="pull-left">
                                <!-- User Image -->
                                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                            </div>
                            <!-- Message title and timestamp -->
                            <h4>
                                Support Team
                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                            </h4>
                            <!-- The message -->
                            <p>Why not buy a new awesome theme?</p>
                            </a>
                        </li>
                        <!-- end message -->
                        </ul>
                        <!-- /.menu -->
                    </li>
                    <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                    <li class="header">You have 10 notifications</li>
                    <li>
                        <!-- Inner Menu: contains the notifications -->
                        <ul class="menu">
                        <li><!-- start notification -->
                            <a href="#">
                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                            </a>
                        </li>
                        <!-- end notification -->
                        </ul>
                    </li>
                    <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-flag-o"></i>
                    <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                    <li class="header">You have 9 tasks</li>
                    <li>
                        <!-- Inner menu: contains the tasks -->
                        <ul class="menu">
                        <li><!-- Task item -->
                            <a href="#">
                            <!-- Task title and progress text -->
                            <h3>
                                Design some buttons
                                <small class="pull-right">20%</small>
                            </h3>
                            <!-- The progress bar -->
                            <div class="progress xs">
                                <!-- Change the css width attribute to simulate progress -->
                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                <span class="sr-only">20% Complete</span>
                                </div>
                            </div>
                            </a>
                        </li>
                        <!-- end task item -->
                        </ul>
                    </li>
                    <li class="footer">
                        <a href="#">View all tasks</a>
                    </li>
                    </ul>
                </li>
            <?php endif; ?>
          <!-- User Account Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php if ($this->ui->hasUserImage()):?>
                    <img alt="" class="user-image" src="<?=$this->ui->getUserImage()?>" />
                <?php endif?>
                <?php if ($this->ui->hasUsername()):?>
                    <span class="hidden-xs"><?=$this->ui->getUsername()?></span>
                <?php else:?>
                    <span class="hidden-xs">Usuário</span>
                <?php endif?>
                <i class="fa fa-angle-down hidden-xs"></i>
            </a>
            <ul class="dropdown-menu">
                <?php if ($this->ui->hasAnyUserAction()):?>
                    <li class="body">
                        <ul class="menu">
                            <?php
                            foreach ($this->ui->getUserActions() as $id => $menu):
                                if ($menu['separator']) {
                                    continue;
                                }
                            ?>
                                    <li>
                                        <a href="<?=$menu['url']?>">
                                            <?php if (!empty($menu['icon'])):?>
                                                <i class="fa fa-<?=$menu['icon']?>"></i>
                                            <?php endif?>
                                            <?= $menu['name']?>
                                        </a>
                                    </li>
                            <?php endforeach?>
                        </ul>
                    </li>
            </ul>
          </li>
          <?php endif?>
        </ul>
  </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <?php if (false): ?>
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
            <p>Alexander Pierce</p>
            <!-- Status -->
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
      <?php endif; ?>

      <?php
      if ($this->ui->hasAnyMenu()):
          $menu = $this->ui->getMenuData();
      ?>
          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
              <?php
              foreach ($menu as $id => $info):
                  $menuUrl = empty($info['link']) ? 'javascript:void(0);' : $info['link'];
              ?>
                  <li  id="menu-<?=$id?>" class="<?=$this->ui->isMenuActive($id)? 'active' : ''?><?=!empty($info['children']) ? ' treeview' : ''?>">
                      <a href="<?=$menuUrl?>">
                          <?php if (!empty($info['icon'])):?>
                              <?php if (!empty($info['children'])):?>
                                  <?php if (!isset($collapsed) || !$collapsed): ?>
                                  <span class="pull-right-container menu-toggle-icon">
                                      <i class="fa fa-angle-left pull-right"></i>
                                  </span>
                                  <?php endif; ?>
                              <?php endif?>
                              <i class="fa fa-<?=$info['icon']?>"></i>
                              <?php endif?>
                              <span class="title"><?=$info['name']?></span>
                      </a>
                      <?php if (!empty($info['children'])):?>
                          <ul class="treeview-menu">
                              <?php
                              foreach ($info['children'] as $subId => $subInfo):
                                    $submenuUrl = empty($subInfo['link']) ? 'javascript:void(0);' : $subInfo['link'];
                              ?>
                                  <li class="start<?=$this->ui->isMenuActive($id, $subId) ? ' active' : ''?>">
                                      <a href="<?= $submenuUrl?>" class="nav-link">
                                          <?php if (!empty($subInfo['icon'])):?>
                                              <i class="fa fa-<?=$subInfo['icon']?>"></i>
                                          <?php endif?>
                                          <span class="title"><?=$subInfo['name']?></span>
                                          <?php if (!empty($subInfo['badge'])):?>
                                              <span class="badge badge-success"><?=$subInfo['badge']?></span>
                                          <?php endif?>
                                      </a>
                                  </li>
                              <?php endforeach?>
                          </ul>
                      <?php endif?>
                  </li>
              <?php endforeach; ?>
          </ul>
          <!-- /.sidebar-menu -->
      <?php endif; ?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <?php if ($ui->hasPageTitle()):?>
          <section class="content-header">
              <h1>
                  <?=$ui->getPageTitle()?>
                  <?php if ($ui->hasPageDescription()):?>
                      <small><?= $ui->getPageDescription()?></small>
                  <?php endif?>
                    <?php if ($this->ui->hasAnyPageAction() && isset($compactPageActions) && $compactPageActions):?>
                            <?php
                            foreach ($this->ui->getPageActions() as $id => $action):
                                if (!$action['visible']) {
                                    continue;
                                }
                                $classes = isset($action['attrs']['class']) ? $action['attrs']['class'] : '';
                                if (empty($classes)) {
                                    $classes = 'btn-default';
                                }
                            ?>
                            <a href="<?=$action['url']?>" class="btn btn-flat tooltips <?=$classes?>" title="<?=$action['name']?>">
                                <?php if (!empty($action['icon'])):?>
                                    <i class="fa fa-<?=$action['icon']?>"></i>
                                <?php endif?>
                            </a>
                        <?php endforeach?>
                    <?php endif?>
              </h1>
              <?php if ($this->ui->hasAnyCrumb()):?>
                  <ul class="breadcrumb">
                      <?php
                      $crumbs      = $this->ui->getBreadCrumb();
                      $countCrumbs = count($crumbs);
                      foreach ($crumbs as $idx => $crumb):
                                     $isLast = ($idx + 1) == $countCrumbs;
                      ?>
                          <li<?=$isLast ? ' class="active"' : ''?>>
                              <?php if (!empty($crumb['link'])):?>
                                  <a href="<?=$crumb['link']?>">
                              <?php endif?>
                              <?=$crumb['name']?>
                              <?php if (!empty($crumb['link'])):?>
                                  </a>
                              <?php endif?>
                          </li>
                      <?php endforeach?>
                  </ul>
              <?php endif?>
                    <?php if ($this->ui->hasAnyPageAction() && (!isset($compactPageActions) || !$compactPageActions)):?>
                        <div class="" style="margin-top:15px;">
                            <?php
                            foreach ($this->ui->getPageActions() as $id => $action):
                                if (!$action['visible']) {
                                    continue;
                                }
                                $classes = isset($action['attrs']['class']) ? $action['attrs']['class'] : '';
                                if (empty($classes)) {
                                    $classes = 'btn-default';
                                }
                            ?>
                            <a href="<?=$action['url']?>" class="btn btn-flat <?=$classes?>">
                                <?php if (!empty($action['icon'])):?>
                                    <i class="fa fa-<?=$action['icon']?>"></i>
                                <?php endif?>
                                <span> <?=$action['name']?> </span>
                                <?php if (!empty($action['badge'])):?>
                                    <span class="badge badge-danger"> <?= $action['badge']?></span>
                                <?php endif?>
                            </a>
                        <?php endforeach?>
                        </div>
                    <?php endif?>
          </section>
          <div class="clearfix"></div>
      <?php endif?>

    <!-- Main content -->
    <section class="content">
        <?php  if (!isset($this->boxed) || $this->boxed): ?>
            <div class="box">
        <?php endif; ?>
            <?php  if (!isset($this->boxed) || $this->boxed): ?>
                <div class="box-body">
            <?php endif; ?>
            <?=$this->getcontent()?>
            <?php  if (!isset($this->boxed) || $this->boxed): ?>
                </div>
            <?php endif; ?>
        <?php  if (!isset($this->boxed) || $this->boxed): ?>
            </div>
        <?php endif; ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <?php if (!isset($footer) || $footer): ?>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">

    </div>
    <!-- Default to the left -->
    <strong>&copy; <?=date('Y')?> <a href="#"><?=$ui->getProjectName()?></a>.</strong> Todos os Direitos Reservados.
  </footer>
  <?php endif; ?>
</div>
<!-- ./wrapper -->
<script type="text/javascript">
 var Admin = {};
 Admin.baseUrl = '<?=$this->url('/')?>';
</script>
<script src="<?=$this->url('/assets/dist/js/admin.min.js')?>"></script>
<script src="<?=$this->url('/assets/components/tinymce/tinymce.js')?>"></script>
<script src="<?=$this->url('/assets/components/tinymce/jquery.tinymce.js')?>"></script>
<script src="<?=$this->url('/assets/components/tinymce/langs/pt_BR.js')?>"></script>
<script src="<?=$this->url('/assets/components/pdf-object/pdfobject.min.js')?>"></script>
<script type="text/javascript">
 (function ($) {
     $(document).ready(function() {
         $('.pdf-object').each(function() {
             var source  = $(this).data('src');
             var options = {
                 fallbackLink: '<p>Este navegador não suporta a exibição de conteúdo PDF</p>',
                 pdfOpenParams: { view: 'FitH', toolbar: '0', statusbar: '0' },
             };

             PDFObject.embed(source, '#' + $(this).attr('id'), options);
         });
         $('.input-ajax-options').each(function () {
             var handler = $(this).data('ajaxHandler');
             $(this).select2({
                 ajax: {
                     url: '<?=$this->url('/ajax')?>/' + handler,
                     dataType: 'json',
                     delay: 250,
                     data: function (params) {
                         return {
                             q:    params.term,
                             page: params.page
                         };
                     },
                     processResults: function (data, params) {
                         return {
                             results: data
                         };
                     },
                     cache: true
                 },
                 minimumInputLength: 1,
                 language: 'pt-BR',
                 theme: 'bootstrap',
                 allowClear: true
             });
         });
     });
 })(jQuery);
 var Modal = function() {
     this.title                = null;
     this.confirmButtonEnabled = true;
     this.cancelButtonEnabled  = true;
     this.confirmButtonText    = 'OK';
     this.cancelButtonText     = 'Cancelar';
     this.content              = '';
     this.visible              = false;
 };
</script>
<template id="modal-template">
<div class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Default Modal</h4>
            </div>
            <div class="modal-body">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
</template>
<?=$this->renderBlock('footer')?>
<?=$this->renderBlock('js')?>
</body>
</html>
