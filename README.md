# ThinkPHP8 + Hyperf + React18 (TS Strict + Tailwind + shadcn/ui) AI-Native 全栈通用预制板

> ⚡ **专为 AI 辅助编程打造的纯 PHP 全栈现代化样板工程（AI-Native Full-Stack PHP Starter Boilerplate）**
> 无论是使用 **Cursor**, **Windsurf**, **Claude Code**, **Copilot**, **DeepSeek** 还是 **Antigravity**，只需拉取本模板并告诉 AI 您的业务需求，AI 将自动化、精准、高质量地完成业务系统的搭建！

---

## 🌟 核心特性与架构优势

1. **AI-First 深度优化**：
   - 预制 `.cursorrules` 与 `AGENTS.md`，从根本上杜绝 AI 乱改架构、乱用 `any`、缺少分层等常见失控问题。
   - 预制 `PROJECT_BRIEF.md` 引导式立项问答，帮助您在开工前与 AI 梳理清晰业务逻辑。
   - 预制 `CONTEXT.md` 自动维系多轮对话上下文与任务状态看板。

2. **纯 PHP 双后端架构（All-PHP Dual-Backend）**：
   - **`tp8-admin` (ThinkPHP 8)**：成熟稳定的管理底座，负责 RBAC 权限体系、用户认证、标准业务 CRUD、数据库事务与审计日志。
   - **`hyperf-service` (Hyperf 3.x / Swoole)**：高性能常驻内存协程微服务，负责大模型对接、SSE 流式打字机输出、WebSocket 长连接、文件深度解析与异步高并发任务。
   - **统一无状态鉴权**：两套后端共用同一套 JWT Secret，一套 Token 无缝畅行双端。
   - **零技术栈割裂**：前后端统一技术栈（后端全 PHP 8.2+，前端 React 18），部署和维护成本极低。

3. **企业级前端标准 (`react-web`)**：
   - **React 18 + Vite 5 + TypeScript 严格模式 (`strict: true`)**：强类型约束，消除类型隐患。
   - **Tailwind CSS + shadcn/ui**：现代化高颜值、高灵活度的 UI 组件体系。
   - **双端网络请求适配**：统一封装 Axios 拦截器，轻松调用 TP8 与 Hyperf 接口并支持 SSE 流式接收。

4. **双部署模式支持（Docker + 宝塔面板 BT-Panel）**：
   - **Docker Compose**：内置完整 `docker-compose.yml`，一键拉起全套服务。
   - **宝塔面板 (BT-Panel)**：提供详尽的宝塔 PHP8.2、Swoole、Supervisor 进程守护及 Nginx 单域名反代完整配置。

---

## 📁 目录结构概览

```
├── .cursorrules                  # Cursor/Windsurf 强约束 AI 规则
├── AGENTS.md                     # AI Agent 行为宪法与协作协议
├── PROJECT_BRIEF.md              # 项目立项与业务需求定义书（新项目首填）
├── CONTEXT.md                    # 动态上下文追踪看板（AI 持续维护）
│
├── README.md                     # 模板说明与快速上手
├── ARCHITECTURE.md               # 架构拓扑、网络流转与鉴权规范
├── REQUIREMENTS.md               # 环境版本与依赖锁定清单
├── API_SPEC.md                   # 统一接口响应体与错误码规范
├── CODE_STANDARD.md              # 全栈代码规范（分层、命名、TS strict、黄金CRUD）
├── DEVELOP_GUIDE.md              # 本地开发与 AI 开发 SOP 指南
├── DEPLOY.md                     # 生产部署指南（含 Docker 与 宝塔面板 BT-Panel 部署）
├── TROUBLESHOOT.md               # 常见问题排查手册（含宝塔排错）
│
├── tp8-admin/                    # 【后端1：ThinkPHP 8 业务管理底座】
├── hyperf-service/               # 【后端2：Hyperf 3.x 协程高性能/AI/流式微服务】
├── react-web/                    # 【前端：React 18 + TS Strict + shadcn/ui】
│
├── docker/                       # 容器化编排与 Nginx 网关配置
├── docker-compose.yml            # 一键启动全栈容器
├── Makefile                      # 便捷管理指令集
├── init.sh / init.bat            # 一键依赖安装与初始化脚本
└── .gitignore
```

---

## 🚀 极速上手（开发环境）

### 方式一：交给 AI 自动引导（推荐）
1. 从 GitHub 克隆本仓库：
   ```bash
   git clone https://github.com/Lusanren/tp8-hyperf-react-template.git my-project
   cd my-project
   ```
2. 打开您的 AI IDE（如 Cursor / Windsurf / Antigravity），对 AI 发送第一句话：
   > *"你好 AI，我已经拉取了这套模板，请阅读 `PROJECT_BRIEF.md` 并一步步引导我完成当前项目的业务立项！"*
3. AI 将根据您的回答自动填充需求文档，并按照工程规范开始编写业务代码。

---

### 方式二：手动本地开发启动

#### 1. 初始化配置文件与依赖
- **Windows 用户**：双击运行 `init.bat`
- **Linux / macOS 用户**：运行 `chmod +x init.sh && ./init.sh`

#### 2. 分别启动服务
1. **启动 MySQL 8 与 Redis**（可通过 `docker-compose up -d mysql redis` 拉起）
2. **启动 ThinkPHP 8**：
   ```bash
   cd tp8-admin
   composer install
   php think run -p 8001
   ```
3. **启动 Hyperf**（需 Swoole 扩展）：
   ```bash
   cd hyperf-service
   composer install
   php bin/hyperf.php start
   ```
4. **启动 React 前端**：
   ```bash
   cd react-web
   npm install
   npm run dev
   ```
5. 打开浏览器访问 `http://localhost:5173`。

---

## 🚢 生产环境部署方案（双方案支持）

详细图文操作步骤请参阅 👉 **[`DEPLOY.md`](DEPLOY.md)**

### 方案一：宝塔面板 (BT-Panel / aaPanel) 部署
1. **软件安装**：安装 Nginx 1.24+、MySQL 8.0+、Redis 7.x、PHP 8.2（安装扩展 `swoole`, `redis`, `fileinfo`, `opcache`，并解除 `proc_open` 等禁用函数）、Supervisor 进程守护管理器。
2. **TP8 部署**：进入 `tp8-admin`，配置 `.env`，运行 `composer install --no-dev -o`，设置 `runtime` 目录 777 权限。
3. **Hyperf 部署 (Supervisor 守护)**：进入 `hyperf-service`，配置 `.env`（确保与 TP8 `JWT_SECRET` 一致），在 Supervisor 中添加守护进程：启动命令 `/www/server/php/82/bin/php bin/hyperf.php start`。
4. **前端打包与 Nginx 整合**：前端运行 `npm run build`，宝塔新建网站根目录指向 `react-web/dist`，在 Nginx 配置文件中加入 SPA 路由回退、`/api/admin/` 反代 TP8、`/api/hyperf/` 反代 Hyperf（关闭缓冲以支持 SSE 流式）。

### 方案二：Docker Compose 一键部署
```bash
cp tp8-admin/.env.example tp8-admin/.env
cp hyperf-service/.env.example hyperf-service/.env
docker compose up -d --build
```

---

## 📄 开源与商用许可
本项目模板基于 [MIT License](LICENSE) 开源，欢迎自由用于商业或开源项目中。
