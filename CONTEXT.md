# CONTEXT.md - 项目上下文与演进状态看板
> 📌 **AI 协作说明**：
> 本文件用于持久化记录项目的**核心决策、当前阶段、任务看板、架构演进与风险点**。
> AI Agent 在每次接收指令和完成任务后，**必须及时更新本文件**，确保多轮会话之间上下文完全同步。

---

## 🔹 1. 基础架构上下文【固定不可变更】
- **架构模式**：标准前后端分离 + 全栈 PHP 双后端微服务 + Nginx 网关反向代理 + 统一无状态 JWT 鉴权。
- **模块职责边界**：
  - `tp8-admin` (ThinkPHP 8)：负责 RBAC 权限、用户体系、业务 CRUD、事务一致性、操作日志。
  - `hyperf-service` (Hyperf 3.x)：负责 AI 大模型接入、SSE 流式打字机响应、WebSocket 长连接、文件深度解析、异步高性能协程计算。
  - `react-web` (React 18)：React 18 + Vite 5 + TS Strict (`strict: true`) + Tailwind CSS + shadcn/ui。
- **网关路由前缀**：
  - `/` $\rightarrow$ React 静态资源
  - `/api/admin/*` $\rightarrow$ ThinkPHP 8 接口
  - `/api/hyperf/*` $\rightarrow$ Hyperf 接口

---

## 🔹 2. 关键技术选型决策记录 (Architectural Decisions)
1. **全 PHP 技术栈决策**：将异步流式/AI微服务统一为 Hyperf 3.x（Swoole/Swow 协程常驻内存），实现后端语言统一为 PHP 8.2+，降低环境运维与团队开发门槛。
2. **统一 JWT 方案**：TP8 负责用户登录并签发 JWT，TP8 与 Hyperf 共享同一套 `JWT_SECRET`，Hyperf 通过中间件独立校验 Token，无需跨服务 RPC。
3. **前后端接口规范**：统一包装为 `{ code: 200, msg: "success", data: ... }`。
4. **状态管理分层**：
   - 全局客户端状态（用户 Token、个人信息、动态菜单）：使用 **Zustand**。
   - 异步服务端数据缓存：使用 **Tanstack React Query**。
5. **协程安全铁律**：Hyperf 内部必须使用协程安全的组件和连接池（Guzzle Coroutine Handler, Redis 协程池），禁止全局静态变量跨请求污染。

---

## 🔹 3. 当前项目阶段 (Project Phase)
- **当前阶段**：`【全栈 PHP 预制脚手架构建完成 - 等待具体项目立项】`
- 可选阶段：`[需求立项] -> [脚手架初始化] -> [基础功能开发] -> [业务深度开发] -> [系统联调] -> [生产上线]`

---

## 🔹 4. 任务看板 (Task Board)

### ✅ 已完成事项 (Completed)
- [x] 构建标准 AI Agent 交互规范 (`AGENTS.md`, `.cursorrules`, `PROJECT_BRIEF.md`, `CONTEXT.md`)
- [x] 建立全栈技术标准与规范库 (`README.md`, `ARCHITECTURE.md`, `REQUIREMENTS.md`, `API_SPEC.md`, `CODE_STANDARD.md`, `DEVELOP_GUIDE.md`, `DEPLOY.md`, `TROUBLESHOOT.md`)
- [x] 搭建 `tp8-admin` 核心脚手架与通用分层骨架 (JWT 中间件、统一响应、全局异常、Auth 与 User 模块)
- [x] 搭建 `hyperf-service` 协程高性能微服务骨架 (JWT 中间件、SSE 流式接口、健康检查)
- [x] 搭建 `react-web` 前端工程骨架 (Vite 5, TS Strict, Tailwind, shadcn/ui, 双后端 Axios 封装, 路由与布局)
- [x] 构建 `docker/` 容器编排体系 (`docker-compose.yml`, Nginx 网关配置, MySQL 8 基础初始化脚本)

### 📋 待办事项 (Todo)
> 当新项目克隆本模板后，AI 会根据 `PROJECT_BRIEF.md` 在此处动态追加具体业务任务：
- [ ] 步骤 1：引导用户完成 `PROJECT_BRIEF.md` 业务立项
- [ ] 步骤 2：设计并导入业务数据表结构 (MySQL Migration / SQL)
- [ ] 步骤 3：编写 TP8 业务 Service 与 Controller
- [ ] 步骤 4：编写 Hyperf AI 与异步业务端点
- [ ] 步骤 5：编写 React 业务页面与状态交互
- [ ] 步骤 6：全链路联调与 Docker Compose 部署验证

---

## 🔹 5. 变更历史记录 (Changelog)
- **2026-09-12**：将异步微服务从 FastAPI 切换为 Hyperf 3.x（Swoole/Swow 协程），实现全栈统一 PHP 后端生态体系。

---

## 🔹 6. 待确认事项与技术风险 (Unresolved Risks)
- [ ] 生产环境 Token 存储：开发阶段默认使用 `localStorage`，生产上线时建议评估是否升级为 `HttpOnly Cookie` + CSRF Token。
- [ ] Swoole / Swow 运行时选择：容器内默认打包 Swoole 5.x 扩展，可根据需要切换为 Swow。
