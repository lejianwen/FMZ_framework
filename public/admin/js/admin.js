/**
 * Created by Administrator on 2017/3/21.
 */
//确认窗口
function showConfirm(msg, url, data, callback) {
  layer.confirm(msg, function () {
    $.ajax({
      url: url,
      data: data,
      type: 'post',
      dataType: 'json',
      success: function (res) {
        var data = eval(res)
        layer_msg(data.msg)
        if (data.error == 0) {
          if (typeof callback != 'undefined')
            callback(data)
        }

      }
    })
  })
}

function layer_msg(msg, shade, time) {
  if (typeof shade == 'undefined')
    shade = 0.2
  if (typeof  time == 'undefined')
    time = 800
  layer.msg(msg, {shade: shade, time: time})
}

/**表单提交
 *
 * @param url
 * @param selector form的id
 * @param callback 回调方法，比如提交完的动作
 * @returns {boolean}
 */
function form_submit(url, selector, callback) {
  if (typeof selector == 'undefined')
    selector = 'form'
  if (typeof callback == 'undefined') {
    callback = function () {
      setTimeout('window.close()', 1000)
      //window.parent.location.reload();
    }
  }
  var form = new FormData(document.getElementById(selector))
  $.ajax({
    url: url,
    data: form,
    type: 'post',
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function (res) {
      layer_msg(res.msg)
      if (res.error == 0) {
        callback()
      }
    }
  })
  return false
}

/**关闭上级窗口
 */
function closeParent() {
  var index = parent.layer.getFrameIndex(window.name)
  parent.layer.close(index)
}

/**改变状态
 * @param url
 * @param data
 * @param callback
 */
function postData(url, data, callback) {
  if (typeof callback == 'undefined')
    callback = function () {}
  $.ajax({
    url: url,
    data: data,
    type: 'post',
    dataType: 'json',
    success: function (res) {
      layer_msg(res.msg)
      if (res.error == 0) {
        callback()
      }
    }
  })
}

$(function () {
  var _class = $('#_class').val()
  var _model = $('#_model').val()
  $('#_add').click(function () {
    layer_show('添加', '/admin/' + _class + '/add')
  })
  $('#lists').on('click', '.delete', function () {
    var _this = $(this)
    var id = $(this).attr('value')
    showConfirm('确定删除么？', '/admin/' + _class + '/delete', {id: id}, function () {
      _this.parents('tr').remove()
    })
  }).on('click', '.update', function () {
    var id = $(this).attr('value')
    layer_show('修改', '/admin/' + _class + '/' + id)
  }).on('click', '.change', function () {
    var id = $(this).data('id')
    var attr = $(this).data('attr')
    var value = $(this).data('value')
    postData('/admin/' + _class + '/changeAttr/' + id, {attr: attr, value: value})
  }).on('change', 'select', function () {
    var id = $(this).data('id')
    var attr = $(this).data('attr')
    var value = $(this).val()
    postData('/admin/' + _class + '/changeAttr/' + id, {attr: attr, value: value})
  })
  $('.batch').click(function () {
    var ids = []
    var route = $(this).data('route')
    $('.batch_id').each(function (k, v) {
      if ($(v).prop('checked')) {
        ids.push($(v).data('value'))
      }
    })
    if (ids.length === 0) {
      layer_msg('请选择至少一行')
      return;
    }
    showConfirm('确定执行该操作?', '/admin/' + _class + '/' + route, {ids: ids}, function () {
      table.ajax.reload()
    })
  })
})
var table;