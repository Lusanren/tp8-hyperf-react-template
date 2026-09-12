# react-web - React 18 企业级 SPA 前端

> 基于 React 18 + Vite 5 + TypeScript 严格模式 (`strict: true`) + Tailwind CSS + shadcn/ui。
> 纯单页静态应用，开箱自带双后端路由代理、统一 Axios 鉴权拦截器、Zustand 状态管理与 SSE 流式打字机组件。

## 目录分层说明
```
src/
├── api/                    # 统一请求层 (request.ts, auth.ts, hyperf.ts)
├── components/
│   ├── ui/                 # shadcn/ui 原子组件库 (button, input, card, badge)
│   ├── common/             # 通用业务组合组件
│   └── layout/             # 系统基础布局 (MainLayout, Header, Sidebar)
├── lib/                    # 工具函数 (utils.ts)
├── pages/                  # 业务路由页面 (login, dashboard, ai-chat)
├── router/                 # 路由配置与鉴权守卫 (index.tsx)
├── store/                  # Zustand 全局状态 (userStore, menuStore)
├── types/                  # 全局强类型定义 (api.ts)
├── App.tsx
└── main.tsx
```

## 本地启动
```bash
npm install
npm run dev
```
构建生产包：
```bash
npm run build
```
