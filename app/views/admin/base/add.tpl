<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit|ie-comp|ie-stand">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport"
        content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <LINK rel="Bookmark" href="/favicon.ico">
  <LINK rel="Shortcut Icon" href="/favicon.ico"/>
  <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui/css/H-ui.min.css"/>
  <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/css/H-ui.admin.css"/>
  <link rel="stylesheet" type="text/css" href="/admin/js/lib/Hui-iconfont/1.0.7/iconfont.css"/>
  <link rel="stylesheet" type="text/css" href="/admin/js/lib/icheck/icheck.css"/>
  <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/skin/default/skin.css" id="skin"/>
  <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/css/style.css"/>
  <script type="text/javascript" src="/admin/js/lib/jquery/1.9.1/jquery.min.js"></script>
  <script type="text/javascript" src="/admin/js/lib/layer/2.1/layer.js"></script>
  <script type="text/javascript" src="/admin/js/static/h-ui/js/H-ui.js"></script>
  <script type="text/javascript" src="/admin/js/static/h-ui.admin/js/H-ui.admin.js"></script>
  <script type="text/javascript" src="/admin/js/admin.js"></script>

</head>
<body>
<input type="hidden" id="_class" value="{$_class}">
<input type="hidden" id="_model" value="{$_model}">
<div class="page-container">
  <form class="form form-horizontal" id="form" onsubmit="form_submit('/admin/{$_class}/add');return false;">
    {foreach $inputs as $input}
      {$input}
    {/foreach}
    <div class="row cl">
      <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
        <button onClick="closeParent();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
      </div>
    </div>
  </form>
</div>
</body>
</html>