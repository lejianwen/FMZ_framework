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
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/admin/js/lib/html5.js"></script>
    <script type="text/javascript" src="/admin/js/lib/respond.min.js"></script>
    <script type="text/javascript" src="/admin/js/lib/PIE_IE678.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui/css/H-ui.min.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/css/H-ui.admin.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/lib/Hui-iconfont/1.0.8/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/lib/icheck/icheck.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/skin/default/skin.css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/css/style.css"/>
    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js"></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <script type="text/javascript" src="/admin/js/lib/jquery/1.9.1/jquery.min.js"></script>

    <script type="text/javascript" src="/admin/js/lib/layer/2.1/layer.js"></script>
    <script type="text/javascript" src="/admin/js/static/h-ui/js/H-ui.js"></script>
    <script type="text/javascript" src="/admin/js/static/h-ui.admin/js/H-ui.admin.js"></script>
    <script type="text/javascript" src="/admin/js/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/admin/js/admin.js"></script>
</head>
<body>
<div class="page-container">
    <form class="form form-horizontal" id="form" onsubmit="form_submit('/admin/admin/{$admin['id']}');return false;" >
        <div class="row cl">
            <label class="form-label col-xs-4"><span class="c-red"></span>用户名:</label>
            <div class="formControls col-xs-6">
                <input type="text" class="input-text" name="username" required value="{$admin['username']}"/>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4"><span class="c-red"></span>用户分组:</label>
            <div class="formControls col-xs-6">
                <select name="group_id" required class="select">
                    <option value="2" {($admin['group_id'] == 3)?'selected':''}>管理员</option>
                    <option value="2" {($admin['group_id'] == 2)?'selected':''}>中级管理员</option>
                    <option value="1" {($admin['group_id'] == 1)?'selected':''}>超级管理员</option>
                </select>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4"><span class="c-red"></span>密码:</label>
            <div class="formControls col-xs-6">
                <input type="password" class="input-text" name="password" required value="******"/>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4"><span class="c-red"></span>电话:</label>
            <div class="formControls col-xs-6">
                <input type="text" class="input-text" name="telephone" value="{$admin['telephone']}"/>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4"><span class="c-red"></span>QQ:</label>
            <div class="formControls col-xs-6">
                <input type="text" class="input-text" name="qq" value="{$admin['qq']}"/>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4"><span class="c-red"></span>备注:</label>
            <div class="formControls col-xs-6">
                <textarea type="text" class="textarea" name="other">{$admin['other']}</textarea>
            </div>
        </div>
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