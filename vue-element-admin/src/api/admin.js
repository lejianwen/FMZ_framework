import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

export function logout() {
  return request({
    url: '/logout',
    method: 'post'
  })
}
export function role() {
  return request({
    url: '/role',
    method: 'get'
  })
}
const model = 'admin'
export function fetchList(query = {}) {
  return request({
    url: `/${model}/index`,
    method: 'get',
    params: query
  })
}

export function deleteItem(data) {
  return request({
    url: `/${model}/delete`,
    method: 'post',
    data
  })
}

export function createItem(data) {
  return request({
    url: `/${model}/create`,
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/${model}/update`,
    method: 'post',
    data
  })
}

export function itemDetail(id) {
  return request({
    url: `/${model}/detail`,
    method: 'get',
    params: { id }
  })
}
