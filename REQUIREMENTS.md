# REQUIREMENTS.md - 运行环境与依赖版本锁定

> ⚠️ **版本一致性原则**：
> 为防止跨大版本升级导致 Breaking Changes，开发者与 AI Agent 应严格遵守以下技术栈版本约束。

---

## 1. 🖥️ 基础运行环境 (Runtimes)

| 环境 / 运行时 | 最低要求 | 推荐版本 | 说明 |
| :--- | :--- | :--- | :--- |
| **PHP** | `>= 8.1` | `8.2.x` | 必须开启 `pdo_mysql`, `redis`, `opcache`, `swoole` (或 `swow`), `curl`, `mbstring` |
| **Node.js** | `>= 18.18.0` | `20.x LTS` | 推荐使用 `pnpm` 或 `npm` |
| **MySQL** | `>= 8.0` | `8.0.x` | 默认字符集推荐 `utf8mb4`, 排序规则 `utf8mb4_unicode_ci` |
| **Redis** | `>= 6.2` | `7.x alpine` | 用于 Token 缓存、分布式锁与队列 |
| **Nginx** | `>= 1.22` | `1.24+` | 用于网关路由分发与反向代理 |
| **Docker / Compose**| Docker 24+, Compose v2.20+ | 最新稳定版 | 一键容器化编排 |

---

## 2. 🐘 后端1：`tp8-admin` 核心依赖约束

```json
{
  "php": ">=8.1.0",
  "topthink/framework": "^8.0.0",
  "topthink/think-orm": "^3.0",
  "firebase/php-jwt": "^6.8"
}
```

---

## 3. 🚀 后端2：`hyperf-service` 核心依赖约束

```json
{
  "php": ">=8.1.0",
  "hyperf/engine": "^2.10",
  "hyperf/framework": "~3.1.0",
  "hyperf/http-server": "~3.1.0",
  "hyperf/guzzle": "~3.1.0",
  "hyperf/redis": "~3.1.0",
  "hyperf/config": "~3.1.0",
  "firebase/php-jwt": "^6.8"
}
```

---

## 4. ⚛️ 前端：`react-web` 核心依赖约束

```json
{
  "dependencies": {
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "react-router-dom": "^6.22.0",
    "axios": "^1.6.8",
    "zustand": "^4.5.2",
    "@tanstack/react-query": "^5.28.0",
    "@radix-ui/react-slot": "^1.0.2",
    "@radix-ui/react-dialog": "^1.0.5",
    "@radix-ui/react-dropdown-menu": "^2.0.6",
    "class-variance-authority": "^0.7.0",
    "clsx": "^2.1.0",
    "tailwind-merge": "^2.2.1",
    "lucide-react": "^0.358.0"
  },
  "devDependencies": {
    "@types/react": "^18.2.66",
    "@types/react-dom": "^18.2.22",
    "@vitejs/plugin-react": "^4.2.1",
    "typescript": "^5.4.2",
    "vite": "^5.1.6",
    "tailwindcss": "^3.4.1",
    "postcss": "^8.4.35",
    "autoprefixer": "^10.4.18"
  }
}
```
