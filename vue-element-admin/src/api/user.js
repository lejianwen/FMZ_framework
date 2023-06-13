import request from '@/utils/request'

export function login (data) {
  return request({
    url: '/login',
    method: 'post',
    data,
  })
}

export function info () {
  return request({
    url: '/info',
  })
}

export function logout () {
  return request({
    url: '/logout',
    method: 'post',
  })
}
