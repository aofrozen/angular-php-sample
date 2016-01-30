{{  stylesheet_link("//fonts.googleapis.com/css?family=Exo+2:300italic,800,400,500,900") }}
    {{ stylesheet_link("//fonts.googleapis.com/css?family=Lato:300") }}
    {{ stylesheet_link("//fonts.googleapis.com/css?family=Open+Sans:700|Ledger") }}
<!-- Loading Bootstrap -->
<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
{{ javascript_include("js/html5shiv.js") }}
{{ javascript_include("js/respond.min.js") }}
<![endif]-->
<div ng-controller="messageEventController"></div>
<script src="/js/app/app-bundle.js"></script>
<script src="/js/app/shared/editor/editor.js"></script>