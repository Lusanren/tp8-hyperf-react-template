import { request } from './request'
import { LoginResult, UserInfo, MenuItem } from '@/types/api'

/**
 * 登录接口（调用 ThinkPHP 8 后端）
 */
export function loginApi(params: { username: string; password: string }) {
  return request<LoginResult>({
    url: '/api/admin/auth/login',
    method: 'POST',
    data: params,
  })
}

/**
 * 退出登录接口
 */
export function logoutApi() {
  return request<null>({
    url: '/api/admin/auth/logout',
    method: 'POST',
  })
}

/**
 * 获取用户个人信息
 */
export function getUserProfileApi() {
  return request<UserInfo>({
    url: '/api/admin/user/profile',
    method: 'GET',
  })
}

/**
 * 获取用户动态菜单
 */
export function getUserMenusApi() {
  return request<MenuItem[]>({
    url: '/api/admin/user/menus',
    method: 'GET',
  })
}
