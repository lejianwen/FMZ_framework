import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'
import qs from 'qs'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  withCredentials: true, // send cookies when cross-domain requests
  timeout: 50000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    // post 请求使用表单提交
    if (config.method === 'post' || config.method === 'POST') {
      config.headers['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8'
      // config.headers['Content-Type'] = ' multipart/form-data;'
      if (config.data) {
        // const formData = new FormData()
        // Object.keys(config.data).forEach((key) => {
        //   formData.append(key, config.data[key])
        // })
        // config.data = formData
        config.data = qs.stringify(config.data)
      }
    }
    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      if (!config.headers) {
        config.headers = {}
      }
      config.headers['admin-token'] = getToken()
      // config.params = qs.stringify(config.params)
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
   */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data

    // if the custom code is not 20000, it is judged as an error.
    if (res.code !== 200) {
      Message({
        message: res.msg || 'error',
        type: 'error',
        duration: 5 * 1000
      })

      // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
      if (res.code === 403) {
        // to re-login
        MessageBox.confirm('登陆已失效', '确认登出', {
          confirmButtonText: '重新登陆',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          store.dispatch('admin/resetToken').then(() => {
            location.reload()
          })
        })
      }
      return Promise.reject(res.msg || 'error')
    } else {
      return res
    }
  },
  error => {
    console.log('err', error) // for debug
    if (error.code == 'ECONNABORTED' && error.message.indexOf('timeout') != -1) {
      error.message = '请求超时！'
    }
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
