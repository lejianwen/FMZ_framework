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
    <link rel="stylesheet" type="text/css" href="/admin/js/lib/Hui-iconfont/1.0.7/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/lib/icheck/icheck.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/skin/default/skin.css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/css/style.css"/>
    <script type="text/javascript" src="/admin/js/lib/jquery/1.9.1/jquery.min.js"></script>

    <script type="text/javascript" src="/admin/js/lib/layer/2.1/layer.js"></script>
    <script type="text/javascript" src="/admin/js/static/h-ui/js/H-ui.js"></script>
    <script type="text/javascript" src="/admin/js/static/h-ui.admin/js/H-ui.admin.js"></script>
    <script type="text/javascript" src="/admin/js/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/admin/js/admin.js"></script>
</head>
<body>
{include file="../nav.tpl"}
<div class="page-container">
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <div class="l" style="width: 50%">
        </div>
        <span class="r">
            <a href="javascript:;" onclick="add()" class="btn btn-primary radius" data-title="添加"><i
                        class="Hui-iconfont">&#xe600;</i> 添加</a>
         </span>
    </div>
    <div class="mt-20">
        <table id="sources" class="table table-border table-bordered table-bg">
            <thead>
            <tr class="text-c">
                <th>ID</th>
                <th>用户名</th>
                <th>分组</th>
                <th>电话</th>
                <th>QQ</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach $admins as $admin}
                <tr class="text-c">
                    <td>{$admin['id']}</td>
                    <td>{$admin['username']}</td>
                    <td>{($admin['group_id'] == 1) ? '超级管理员' : (($admin['group_id'] == 2) ? '中级管理员': '管理员')}</td>
                    <td>{$admin['telephone']}</td>
                    <td>{$admin['qq']}</td>
                    <td>
                        <span value="{$admin['id']}" class="update btn btn-primary">修改</span>
                        <span value="{$admin['id']}" class="delete btn btn-danger-outline">删除</span>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>
{literal}
    <script>
        //        $.fn.dataTable.ext.errMode = 'none';
        function add() {
            layer_show('添加', '/admin/admin/add');
        }

        $(function () {
            $('#search_btn').click(function () {
                table.ajax.reload();
            });

            $('#sources').on('click', '.delete', function () {
                var _this = $(this);
                var id = $(this).attr('value');
                showConfirm('确定删除么？', '/admin/admin/delete', {id: id}, function () {
                    _this.parents('tr').remove();
                });
            }).on('click', '.update', function () {
                var _this = $(this);
                var id = $(this).attr('value');
                layer_show('修改', '/admin/admin/' + id);
            });

        })
    </script>
{/literal}
</body>

</html>