# tp8-admin - ThinkPHP 8 业务管理底座

> 负责系统的用户体系、RBAC 权限、组织架构、业务数据主事务 CRUD、操作审计日志。
> 采用纯前后端分离设计，只返回 JSON，关闭所有 HTML 模板渲染。

## 目录分层说明
```
app/
├── admin/                  # 后台管理业务模块
│   ├── controller/         # 控制器层（仅参数接收与响应返回）
│   ├── service/            # 业务服务层（核心业务逻辑与事务）
│   ├── model/              # 数据模型层
│   ├── validate/           # 入参验证器层
│   └── middleware/         # 鉴权与跨域中间件
├── common/                 # 公共基础设施
│   ├── Response.php        # 统一标准 JSON 响应工具类
│   └── exception/          # 全局未捕获异常统一处理器
config/                     # 配置目录 (jwt.php, database.php 等)
route/                      # 路由定义 (admin.php)
public/                     # Web 入口
```

## 本地启动
```bash
composer install
cp .env.example .env
php think run -p 8001
```
