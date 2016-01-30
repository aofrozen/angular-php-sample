<?php echo $this->tag->stylesheetLink('//fonts.googleapis.com/css?family=Exo+2:300italic,800,400,500,900'); ?>
    <?php echo $this->tag->stylesheetLink('//fonts.googleapis.com/css?family=Lato:300'); ?>
    <?php echo $this->tag->stylesheetLink('//fonts.googleapis.com/css?family=Open+Sans:700|Ledger'); ?>
<!-- Loading Bootstrap -->
<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
<?php echo $this->tag->javascriptInclude('js/html5shiv.js'); ?>
<?php echo $this->tag->javascriptInclude('js/respond.min.js'); ?>
<![endif]-->
<div ng-controller="messageEventController"></div>
<script src="/js/app/app-bundle.js"></script>
<script src="/js/app/shared/editor/editor.js"></script>