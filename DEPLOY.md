# DEPLOY.md - 生产环境部署与运维指南

> 本系统提供两套成熟的生产部署方案：
> 1. **方案一：Docker Compose 一键容器化部署**（云原生、多环境隔离、推荐）
> 2. **方案二：宝塔面板 (BT-Panel / aaPanel) / 手动 VPS 部署**（传统运维、可视化管理、国内服务器高频使用）

---

# 方案一：Docker Compose 一键容器化部署

## 1. 🚢 容器编排拓扑
```
docker-compose.yml
├── mysql (MySQL 8.0 容器)
├── redis (Redis Alpine 容器)
├── tp8-admin (PHP 8.2-FPM 容器)
├── hyperf-service (Hyperf 3.x Swoole 容器)
├── react-web (Node 构建 -> 静态产物由 Nginx 托管)
└── nginx (Nginx 1.24 网关容器，对外暴露 80/443)
```

## 2. 🚀 一键部署步骤
```bash
git clone <your-repo-url> my-prod-app
cd my-prod-app

# 1. 复制并配置各模块环境变量
cp tp8-admin/.env.example tp8-admin/.env
cp hyperf-service/.env.example hyperf-service/.env

# ⚠️ 关键检查：确保两个 .env 中的 JWT_SECRET 字符串完全一致！

# 2. 一键构建并后台启动
docker compose up -d --build

# 3. 检查容器运行状态
docker compose ps
```

---

# 方案二：宝塔面板 (BT-Panel) 手动部署指南

> 适用于阿里云、腾讯云、华为云等 CentOS / Ubuntu / Debian 服务器安装了宝塔面板的环境。

## 1. 🛠️ 宝塔环境基础软件准备
在宝塔面板的 **「软件商店」** 中安装以下基础组件：
- **Web 服务器**：Nginx 1.24+
- **数据库**：MySQL 8.0+（创建数据库 `tp8_admin_db`，并导入 `docker/mysql/init.sql`）
- **缓存**：Redis 7.x
- **PHP 版本**：PHP 8.2
  - **安装 PHP 扩展**：点击 PHP 8.2 设置 $\rightarrow$ 安装扩展 $\rightarrow$ 安装 **`fileinfo`**、**`opcache`**、**`redis`**、**`swoole`** (或 `swoole4/5`)。
  - **解除 PHP 禁用函数**：点击 PHP 8.2 设置 $\rightarrow$ 禁用函数 $\rightarrow$ 删除以下函数（Hyperf 与 Composer 运行所需）：
    `proc_open`, `proc_get_status`, `putenv`, `pcntl_signal`, `pcntl_alarm`, `pcntl_fork`。
- **进程守护管理器**：在软件商店搜索并安装 **「Supervisor 进程守护」**。
- **Node.js 版本管理器**（可选，若直接上传打包好的 `dist` 则不需要）：安装 Node 20 LTS。

---

## 2. 📁 代码上传与目录规划
假设将项目部署在 `/www/wwwroot/my-app` 目录下：
```
/www/wwwroot/my-app/
├── tp8-admin/           # ThinkPHP 8 目录
├── hyperf-service/      # Hyperf 协程服务目录
└── react-web/           # 前端源码（或直接放打包好的 dist/ 目录）
```

---

## 3. 🐘 部署 ThinkPHP 8 模块 (`tp8-admin`)

1. **安装依赖与配置环境**：
   ```bash
   cd /www/wwwroot/my-app/tp8-admin
   cp .env.example .env
   # 编辑 .env 修改 MySQL 账号密码、Redis 与 JWT_SECRET
   composer install --no-dev -o
   ```
2. **设置目录权限**：
   ```bash
   chown -R www:www /www/wwwroot/my-app/tp8-admin
   chmod -R 755 /www/wwwroot/my-app/tp8-admin
   chmod -R 777 /www/wwwroot/my-app/tp8-admin/runtime
   ```

---

## 4. 🚀 部署 Hyperf 协程微服务 (`hyperf-service`)

1. **安装依赖与配置环境**：
   ```bash
   cd /www/wwwroot/my-app/hyperf-service
   cp .env.example .env
   # 编辑 .env，确保 JWT_SECRET 与 tp8-admin/.env 完全相同！
   composer install --no-dev -o
   ```
2. **通过 Supervisor 添加守护进程（保证常驻内存与崩溃自启）**：
   - 打开宝塔面板 $\rightarrow$ **「Supervisor 进程守护」** $\rightarrow$ **「添加守护进程」**：
     - **名称**：`hyperf-service`
     - **启动用户**：`www`
     - **运行目录**：`/www/wwwroot/my-app/hyperf-service`
     - **启动命令**：`/www/server/php/82/bin/php /www/wwwroot/my-app/hyperf-service/bin/hyperf.php start`
     - **进程数量**：`1`
   - 保存并启动，检查状态是否为绿色的 **「运行中」**（监听本地 `9501` 端口）。

---

## 5. ⚛️ 构建与部署 React 前端 (`react-web`)

- **方式 A（本地构建后上传，推荐）**：
  在本地开发机执行：
  ```bash
  cd react-web
  npm install
  npm run build
  ```
  将生成的 `react-web/dist` 整个文件夹上传到服务器 `/www/wwwroot/my-app/react-web/dist`。

- **方式 B（服务器端直接构建）**：
  ```bash
  cd /www/wwwroot/my-app/react-web
  npm install
  npm run build
  ```

---

## 6. 🌐 宝塔 Nginx 站点配置（单域名全站整合方案）

在宝塔面板新建一个网站（绑定您的域名），然后在 **「站点设置 $\rightarrow$ 配置文件」** 中，将完整的 Nginx 配置替换为以下标准模板：

```nginx
server
{
    listen 80;
    listen 443 ssl http2;
    server_name your-domain.com; # 替换为您的实际域名
    
    # 根目录指向前端打包产物 dist
    root /www/wwwroot/my-app/react-web/dist;
    index index.html index.htm;

    # SSL 证书配置（宝塔一键申请 Let's Encrypt 会自动生成在此）
    # ssl_certificate /www/server/panel/vhost/cert/your-domain.com/fullchain.pem;
    # ssl_certificate_key /www/server/panel/vhost/cert/your-domain.com/privkey.pem;

    # 1. 前端单页应用 (SPA) History 路由回退（解决刷新 404）
    location / {
        try_files $uri $uri/ /index.html;
    }

    # 2. 静态资源长缓存
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }

    # 3. 反向代理 ThinkPHP 8 业务管理接口
    location /api/admin/ {
        proxy_pass http://127.0.0.1:8001/; # 若使用 php think run
        # 或者直接走 FastCGI 方式解析至 tp8-admin/public/index.php
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_connect_timeout 60s;
        proxy_read_timeout 120s;
    }

    # 4. 反向代理 Hyperf 协程服务（SSE 流式打字机 + WebSocket）
    location /api/hyperf/ {
        proxy_pass http://127.0.0.1:9501/;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        # 核心关键：必须关闭缓冲区以支持 SSE 打字机流式输出
        proxy_buffering off;
        proxy_cache off;
        chunked_transfer_encoding off;
        proxy_read_timeout 600s;
    }

    # 禁止访问敏感文件
    location ~ ^/(\.user.ini|\.htaccess|\.git|\.env|\.svn) {
        return 404;
    }

    access_log  /www/wwwlogs/your-domain.com.log;
    error_log  /www/wwwlogs/your-domain.com.error.log;
}
```

> 💡 **若 TP8 在宝塔中不使用 `php think run`，而是以传统 PHP-FPM 运行**：
> 只需在宝塔新建一个独立纯静态/PHP站点绑定目录至 `/www/wwwroot/my-app/tp8-admin/public`，设置防跨站关闭，伪静态选 `thinkphp`，监听内网端口或域名即可。

---

## 7. 🔒 生产安全加固清单
1. **统一 JWT Secret**：必须为高强度随机字符串（32位以上），严禁使用默认值。
2. **数据库安全**：禁止 MySQL 3306 端口对外网开放，仅允许 `127.0.0.1` 访问。
3. **关闭调试模式**：
   - `tp8-admin/.env` 中设置 `APP_DEBUG = false`。
   - `hyperf-service/.env` 中设置 `APP_ENV = prod`。
