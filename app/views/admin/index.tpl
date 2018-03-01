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
    <link rel="stylesheet" type="text/css" href="/admin/js/lib/Hui-iconfont/1.0.8/iconfont.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/lib/icheck/icheck.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/skin/default/skin.css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="/admin/js/static/h-ui.admin/css/style.css"/>
    <title>FMZ后台</title>
</head>
<body>
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <span class="logo navbar-slogan f-l mr-10 hidden-xs">FMZ后台</span>
            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li class="dropDown dropDown_hover"><a href="#" class="dropDown_A">{$admin} <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            {*<li><a href="#">个人信息</a></li>*}
                            <li><a href="/admin/logout">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value=""/>
    <div class="menu_dropdown bk_2">
        {foreach $menus as $child_menus}
            <dl id="menu-article">
                <dt>
                    <i class="Hui-iconfont">{$child_menus['icon']}</i> {$child_menus['name']}
                    <i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
                </dt>
                <dd>
                    <ul>
                        {foreach $child_menus['children'] as $menu}
                            {if $menu['status']}
                                <li>
                                    <a _href="{$menu['path']}" data-title="{$menu['name']}" href="javascript:;">
                                        <i class="Hui-iconfont">{$menu['icon']}</i> {$menu['name']}
                                    </a>
                                </li>
                            {/if}
                        {/foreach}
                    </ul>
                </dd>
            </dl>
        {/foreach}
    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
</div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active"><span title="我的桌面" data-href="/admin/index/welcome">我的桌面</span><em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
            <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d4;</i>
            </a>
            <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d7;</i>
            </a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="/admin/index/welcome"></iframe>
        </div>
    </div>
</section>
<script type="text/javascript" src="/admin/js/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/js/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="/admin/js/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/admin/js/static/h-ui.admin/js/H-ui.admin.js"></script>
<script>
    $(function () {
        $('.auto_click').click();
    })
</script>

</body>
</html>