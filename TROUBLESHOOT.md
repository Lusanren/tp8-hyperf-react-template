# TROUBLESHOOT.md - 常见问题排查与速查手册

---

## 1. ⚛️ 前端 (React / Vite) 常见问题

### Q1: 前端页面刷新报 404 Not Found
- **原因**：React SPA 采用了 History 路由模式，直接刷新页面时 Nginx 会当成静态文件寻找，而文件不存在。
- **解决**：在 Nginx 配置中增加 `try_files $uri $uri/ /index.html;`。

### Q2: 本地开发请求后端接口报 404 或 CORS 跨域错误
- **原因**：本地请求未经过 Vite Proxy 代理转发，或者 Vite 配置的代理前缀不匹配。
- **解决**：检查 `react-web/vite.config.ts` 中的 `proxy` 配置，确保目标端口对应：
  - `/api/admin` $\rightarrow$ `http://127.0.0.1:8001`
  - `/api/hyperf` $\rightarrow$ `http://127.0.0.1:9501`

### Q3: TypeScript 编译报错 "Type 'any' is not assignable..."
- **原因**：项目开启了 `tsconfig.json` 的 `strict: true`，严禁隐式或显式滥用 `any`。
- **解决**：在 `src/types/` 中补齐该数据结构的 `interface` 或 `type` 定义。

---

## 2. 🐘 后端1 (ThinkPHP 8) 常见问题

### Q1: 请求返回 401 Unauthorized / Token 校验失败
- **原因**：
  1. 请求未携带 `Authorization: Bearer <token>` 请求头。
  2. `tp8-admin/.env` 中的 `JWT_SECRET` 与签发时不一致或已过期。
- **解决**：重新登录获取最新 Token，并检查 `.env` 配置。

### Q2: 数据库连接失败 `SQLSTATE[HY000] [2002]`
- **原因**：MySQL 服务未启动，或 `.env` 中的数据库主机 `DB_HOST`、端口、账号密码填写有误。
- **解决**：确保本地或 Docker/宝塔 MySQL 正常运行，测试账号密码连接可用。

### Q3: 宝塔下 TP8 提示 open_basedir restriction in effect
- **原因**：宝塔面板默认开启了「防跨站攻击 (open_basedir)」，限制了 PHP 访问父目录。
- **解决**：在宝塔站点设置 $\rightarrow$ 网站目录 $\rightarrow$ 关闭 **「防跨站攻击」**，或者在 `php.ini` 中调整 `open_basedir` 路径。

---

## 3. 🚀 后端2 (Hyperf 3.x) 常见问题

### Q1: SSE 流式打字机在前端是一次性全部弹出，没有流式效果
- **原因**：Nginx 或中间代理开启了 `proxy_buffering` 缓冲机制，将流式数据缓存后一次性发送。
- **解决**：在 Nginx 的 `/api/hyperf/` 配置块中加入：
  ```nginx
  proxy_buffering off;
  proxy_cache off;
  ```

### Q2: Hyperf 接口报错 JWT Decode Error
- **原因**：`hyperf-service/.env` 中的 `JWT_SECRET` 与 `tp8-admin/.env` 中的 `JWT_SECRET` 不一致。
- **解决**：对比两边的 `.env` 文件，确保 Secret 字符串完全一致。

### Q3: 宝塔 Supervisor 启动 Hyperf 失败或秒退
- **原因**：
  1. PHP 禁用了相关进程函数（如 `proc_open`, `putenv`, `pcntl_*`）。
  2. 宝塔默认 CLI PHP 版本不是 PHP 8.2。
  3. Swoole 扩展未在 PHP 8.2 中正确启用。
- **解决**：
  - 在宝塔 PHP 8.2 设置中解除 `proc_open` 等禁用函数。
  - 在 Supervisor 启动命令中使用绝对路径指定 PHP 8.2：`/www/server/php/82/bin/php bin/hyperf.php start`。
  - 终端运行 `/www/server/php/82/bin/php -m | grep swoole` 确认已加载 Swoole 扩展。

### Q4: Windows 本地直接运行 Hyperf 报错找不到 Swoole
- **原因**：Swoole 原生不支持 Windows 环境。
- **解决**：Windows 环境推荐直接使用 WSL2 或通过 Docker 一键启动 `docker compose up -d hyperf-service`。
