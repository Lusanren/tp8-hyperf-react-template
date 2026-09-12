# API_SPEC.md - 统一前后端接口与通信规范

> 统一的接口规范是全栈协作与 AI 自动代码生成的基石。TP8 与 Hyperf 均严格遵循本协议。

---

## 1. 📤 通用 JSON 响应体结构

所有常规 HTTP 请求统一返回以下三段式结构：

```json
{
  "code": 200,
  "msg": "操作成功",
  "data": {}
}
```

### 字段说明：
- `code` (number): 业务状态码（`200` 表示成功；其他值表示业务错误/异常）。
- `msg` (string): 提示信息（前端全局 Toast 提示直接读取此字段）。
- `data` (any): 业务负载对象，空数据时返回 `{}` 或 `null`。

---

## 2. 🔢 统一业务状态码对照表

| 业务状态码 `code` | HTTP Status | 含义说明 | 前端行为 |
| :--- | :--- | :--- | :--- |
| **`200`** | `200 OK` | 操作成功 | 正常消费 `data` |
| **`400`** | `400 Bad Request` | 客户端入参校验失败 / 业务前置条件不满足 | 弹窗展示 `msg` 提示 |
| **`401`** | `401 Unauthorized`| Token 缺失、已过期或签名无效 | 清空本地 Token 并重定向至 `/login` |
| **`403`** | `403 Forbidden` | 权限不足，禁止访问该接口或资源 | 提示“无权限访问该资源” |
| **`404`** | `404 Not Found` | 请求的接口或数据实体不存在 | 提示“请求资源未找到” |
| **`500`** | `500 Server Error`| 服务器内部异常 / 未捕获异常 | 提示“系统繁忙，请稍后重试” |

---

## 3. 📄 分页列表标准数据结构

当接口为分页列表查询时，`data` 字段必须包含以下分页元数据：

```json
{
  "code": 200,
  "msg": "success",
  "data": {
    "list": [
      { "id": 1, "name": "示例数据" }
    ],
    "total": 100,
    "page": 1,
    "pageSize": 10
  }
}
```

---

## 4. 🌊 SSE (Server-Sent Events) 流式协议规范（Hyperf 专属）

对于 AI 对话、长文本生成或大任务进度通知，必须使用 SSE 协议：

- **请求头**：
  - `Accept: text/event-stream`
  - `Authorization: Bearer <token>`
- **响应头**：
  - `Content-Type: text/event-stream`
  - `Cache-Control: no-cache`
  - `Connection: keep-alive`
  - `X-Accel-Buffering: no` (禁止 Nginx 缓冲)

### SSE 传输数据包格式：
```
data: {"content": "你", "done": false}

data: {"content": "好", "done": false}

data: {"content": "！", "done": false}

data: {"content": "", "done": true}
```
前端收到 `done: true` 或 `[DONE]` 标记时停止读取并关闭流。

---

## 5. 🛡️ 鉴权 Header 规范
客户端请求受保护的接口时，必须在 HTTP Header 中携带：
```http
Authorization: Bearer <jwt_token_string>
```
缺少该 Header 或格式错误将直接触发 `401 Unauthorized` 响应。

---

## 6. 🌐 RESTful 业务接口动词映射规范表

所有业务管理模块的 API 路由和 HTTP 动词统一遵循以下规范：

| 操作行为 | HTTP Method | 路由示例 | 说明 |
| :--- | :--- | :--- | :--- |
| **分页列表查询** | `GET` | `/api/admin/customer` | Query 参数: `page`, `pageSize`, `keyword` 等 |
| **单条详情查询** | `GET` | `/api/admin/customer/:id` | 路径参数传递数据主键 ID |
| **创建新数据** | `POST` | `/api/admin/customer` | Request Body 传递 JSON 字段 |
| **修改更新数据** | `PUT` | `/api/admin/customer/:id` | Request Body 传递需更新的 JSON 字段 |
| **单条/批量删除** | `DELETE` | `/api/admin/customer/:id` | 软删除对应实体 |
| **AI 协程流式接口** | `POST` | `/api/hyperf/v1/ai/chat/stream` | SSE 流式长连接响应 |
