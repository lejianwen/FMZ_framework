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

</head>
<body>
{include file="../nav.tpl"}
<input type="hidden" id="_class" value="{$_class}">
<input type="hidden" id="_model" value="{$_model}">
<div class="page-container">
  <div class="cl pd-5 bg-1 bk-gray mt-20">
    <div class="l" style="width: 50%">
      <div class="col-xs-4">
        <input type="text" class="input-text" id="search" placeholder="输入关键字">
      </div>
      <input type="button" value="查询" class="btn" id="search_btn">
      <input type="button" value="重置" class="btn btn-secondary" id="search_reset">
    </div>
    <span class="r">
      {if !$grid['no_add']}
        <a href="javascript:;" id="_add" class="btn btn-primary radius" data-title="添加">
           <i class="Hui-iconfont">&#xe600;</i> 添加
        </a>
      {/if}
    </span>
  </div>
  <div class="mt-20">
    <table id="lists" class="table table-border table-bordered table-bg">
      <thead>
      <tr class="text-c">
        {foreach $grid['header'] as $header}
          <th>{$header}</th>
        {/foreach}
      </tr>
      </thead>
      <tbody class="text-c">
      </tbody>
    </table>
  </div>
</div>
</body>
<script type="text/javascript" src="/admin/js/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/js/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="/admin/js/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/admin/js/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript" src="/admin/js/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/admin/js/admin.js"></script>
<script>
  $(function () {
    {$grid['js']}
  })
</script>

<script>
  //        $.fn.dataTable.ext.errMode = 'none';

  $(function () {
    var _class = $('#_class').val()
    $('#search_btn').click(function () {
      table.ajax.reload()
    })

    var table = $('#lists').dataTable({
      'sDom': 'lrtip',
      'pagingType': 'full_numbers',
      'pageLength': 20,
      'processing': true,
      'serverSide': true,
      'aaSorting': [[0, 'desc']],
      'lengthMenu': [20, 40, 100],
      'error': function () {
        alert('网络错误!')
      },
      'ajax': {
        'url': '/admin/' + _class + '/lists',
        'dataType': 'json',
        'dataSrc': 'data',
        'data': function (d) {
          d.order_str = d.columns[[d.order[0].column]].data
          d.order_dir = d.order[0].dir
          d.search = $('#search').val()
        }
      },
      'aoColumns': [
        {$grid['jsData']}

      ]
    }).api()
  })
</script>
</html>