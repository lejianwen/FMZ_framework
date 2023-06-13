import request from '@/utils/request'

export function list (params) {
  return request({
    url: '/admin/index',
    params,
  })
}

export function detail (id) {
  return request({
    url: '/admin/detail',
    params: {id},
  })
}

export function create (data) {
  return request({
    url: '/admin/create',
    method: 'post',
    data,
  })
}

export function update (data) {
  return request({
    url: '/admin/update',
    method: 'post',
    data,
  })
}

export function remove (data) {
  return request({
    url: '/admin/delete',
    method: 'post',
    data,
  })
}

export function changePwd (data) {
  return request({
    url: '/admin/upPass',
    method: 'post',
    data,
  })
}
