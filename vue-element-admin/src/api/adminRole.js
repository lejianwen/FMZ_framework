import request from '@/utils/request'
const model = 'adminRole'
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
export function caiji() {
  return request({
    url: `/${model}/caiji`,
    method: 'get'
  })
}
