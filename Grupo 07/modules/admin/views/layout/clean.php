<?php
$appConfig = $application->loadConfig('app');
$theme     = 'blue';
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
  <?=$this->renderBlock('css')?>
  <?=$this->renderBlock('head')?>
</head>
<body>
<div>
    <?=$this->getcontent()?>
</div>
<script src="<?=$this->url('/assets/dist/js/admin.min.js')?>"></script>
<script src="<?=$this->url('/assets/components/tinymce/tinymce.js')?>"></script>
<script src="<?=$this->url('/assets/components/tinymce/jquery.tinymce.js')?>"></script>
<script src="<?=$this->url('/assets/components/tinymce/langs/pt_BR.js')?>"></script>
<script type="text/javascript">
 (function ($) {
     $(document).ready(function() {
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
