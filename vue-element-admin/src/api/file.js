import request from '@/utils/request'

export function getOssToken(data) {
  return request({
    url: '/order/getOssToken',
    method: 'get',
    params: data
  })
}

export function downUrl (data) {
  return request({
    url: '/order/downUrl',
    method: 'get',
    params: data
  })
}
