/**
 * 统一标准后端响应体
 */
export interface ApiResponse<T = unknown> {
  code: number
  msg: string
  data: T
}

/**
 * 分页结构
 */
export interface PageResult<T> {
  list: T[]
  total: number
  page: number
  pageSize: number
}

/**
 * 用户信息
 */
export interface UserInfo {
  id: number
  username: string
  nickname: string
  role: string
  avatar?: string
}

/**
 * 登录响应
 */
export interface LoginResult {
  token: string
  expiresIn: number
  userInfo: UserInfo
}

/**
 * 动态菜单
 */
export interface MenuItem {
  id: number
  parentId: number
  title: string
  path: string
  component: string
  icon?: string
  sort?: number
  children?: MenuItem[]
}
