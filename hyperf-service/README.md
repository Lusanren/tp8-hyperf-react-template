# hyperf-service - Hyperf 3.x 协程高性能/AI/流式微服务

> 专门承载系统的 SSE 流式大模型输出、WebSocket 长连接、异步高并发队列、大文件解析与复杂实时计算。
> 采用纯 Swoole/Swow 协程常驻内存设计，极速响应。

## 目录分层说明
```
app/
├── Controller/             # 控制器 (HealthController, AiController)
├── Middleware/             # 统一 JWT 校验中间件、跨域中间件
├── Service/                # AI 协程流式生成器与业务服务
bin/
└── hyperf.php              # Hyperf 启动入口
config/
├── routes.php              # 路由注册
├── server.php              # Swoole 服务监听配置 (9501)
└── autoload/               # 自动加载配置
```

## 本地启动
```bash
composer install
cp .env.example .env
php bin/hyperf.php start
```
*(Windows 用户可通过 `docker compose up -d hyperf-service` 直接在容器内运行)*
