# CODE_STANDARD.md - 全栈分层与代码规范

> 规范大于一切。无论是人类开发者还是 AI Agent，必须严格遵循以下编程规范。

---

## 1. 🐘 ThinkPHP 8 规范 (`tp8-admin`)

### (1) 严格四层架构 (Controller -> Service -> Model -> Validate)
- **Controller 层**：
  - 职责：只负责接收 HTTP 请求参数、调用 Validate 校验、调用 Service 层处理业务，并调用 `Response::success()` 或 `Response::error()` 返回。
  - **禁忌**：严禁在 Controller 中编写复杂的 SQL 组装、数据库事务或冗长的业务判断逻辑。
- **Service 层**：
  - 职责：承载全部核心业务逻辑、跨表事务（`Db::transaction`）、第三方接口调用。
- **Model 层**：
  - 职责：映射数据表结构、启用软删除（`use SoftDelete`）、定义模型关联、字段类型转换器及获取器。
- **Validate 层**：
  - 职责：独立验证器类，强制校验必填字段、类型、长度、场景规则。

### (2) 命名规范
- 类名：大驼峰（`UserAdminService.php`）
- 方法名：小驼峰（`getUserProfile()`）
- 数据库表名 & 字段名：蛇形下划线（`sys_user_roles`, `created_at`）

---

## 2. 🚀 Hyperf 3.x 规范 (`hyperf-service`)

### (1) 协程安全原则 (Coroutine Safety)
- 严禁使用全局变量 (`$GLOBALS`, `$_GET`, `$_POST`) 或在单例对象中存储与请求绑定的状态数据。
- 必须使用 Hyperf 提供的连接池（DB 协程池、Redis 协程池）以及协程客户端（`Hyperf\Guzzle\ClientFactory`）。

### (2) 依赖注入与注解
- 控制器与服务类使用 `#[Inject]` 注解或构造函数进行依赖注入。
- 路由使用注解路由 `#[Controller]` / `#[PostMapping]` 或统一在 `config/routes.php` 中声明。

### (3) 分层结构
```
app/
├── Controller/         # 控制器（接收请求、参数校验、调用 Service、响应 SSE 流/JSON）
├── Middleware/         # 中间件（统一 JWT 认证、跨域处理）
├── Service/            # 协程业务逻辑与 AI 流式生成器
├── Exception/          # 自定义异常与全局异常处理器
└── Model/              # 协程 ORM 模型
```

---

## 3. ⚛️ React 18 & TypeScript 规范 (`react-web`)

### (1) 严格 TypeScript 规范 (`strict: true`)
- **零 `any` 容忍**：严禁随意使用 `any`。无法确定的类型必须使用泛型、联合类型或 `unknown` 配合类型守卫。
- **接口强类型**：所有请求函数和后端返回数据必须定义专门的 TS `interface`（统一放于 `src/types/`）。

### (2) 组件划分规范
- `src/components/ui/`：专门存放由 shadcn/ui 自动生成的原子 UI 组件（如 `button.tsx`, `input.tsx`, `dialog.tsx`）。
- `src/components/common/`：存放全项目通用的业务组合组件（如通用搜索栏、数据表格包装器、文件上传器）。
- `src/components/layout/`：页面布局框架（侧边栏、顶部导航、面包屑）。
- `src/pages/`：具体路由页面，按业务模块划分文件夹（如 `pages/user/`, `pages/ai-chat/`）。

### (3) 状态管理与数据流
- **全局客户端状态**（用户信息、Token、侧边栏折叠状态）：使用 `zustand`。
- **异步服务端数据缓存**（列表数据、详情查询）：使用 `@tanstack/react-query`，避免在 `useEffect` 中手写脏状态。

---

## 4. 🗄️ 数据库设计与建表通用标准 (Database Schema Standard)

AI Agent 或开发者在设计任何新业务表时，必须遵循以下工业级标准：

### (1) 基础约束
- **引擎与字符集**：表引擎必须为 `ENGINE=InnoDB`，字符集为 `CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`。
- **表名规范**：前缀区分模块，如 `sys_`（系统基表）、`biz_` 或 `tb_`（业务数据表），全小写下划线命名。
- **必须具备注释**：所有数据表和所有字段均必须包含 `COMMENT` 业务说明。

### (2) 通用必备公共字段
每张业务主表原则上必须包含以下标准公共字段：

```sql
CREATE TABLE `biz_example` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  -- 业务具体字段放在此处 --
  `status` TINYINT NOT NULL DEFAULT 1 COMMENT '状态: 1=正常, 0=禁用',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` DATETIME DEFAULT NULL COMMENT '软删除时间, NULL表示未删除',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='示例业务表';
```

---

## 5. 📋 黄金标准 CRUD 代码范式 (Golden CRUD Pattern)

当需要新增一套业务管理模块时，请严格参考以下标准范例编写：

### (1) TP8 后端示例 (以 `Customer` 模块为例)

#### ① Model (`app/admin/model/Customer.php`)
```php
<?php
namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

class Customer extends Model
{
    use SoftDelete;

    protected $name = 'biz_customers';
    protected $deleteTime = 'deleted_at';
    protected $autoWriteTimestamp = 'datetime';
}
```

#### ② Service (`app/admin/service/CustomerService.php`)
```php
<?php
namespace app\admin\service;

use app\admin\model\Customer;
use think\facade\Db;
use think\exception\ValidateException;

class CustomerService
{
    public function getList(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $pageSize = (int)($params['pageSize'] ?? 10);

        $query = Customer::order('id', 'desc');

        if (!empty($params['keyword'])) {
            $query->whereLike('name|phone', '%' . trim($params['keyword']) . '%');
        }
        if (isset($params['status']) && $params['status'] !== '') {
            $query->where('status', (int)$params['status']);
        }

        $total = $query->count();
        $list = $query->page($page, $pageSize)->select()->toArray();

        return [
            'list'     => $list,
            'total'    => $total,
            'page'     => $page,
            'pageSize' => $pageSize
        ];
    }

    public function create(array $data): Customer
    {
        return Db::transaction(function () use ($data) {
            return Customer::create($data);
        });
    }

    public function update(int $id, array $data): bool
    {
        $model = Customer::find($id);
        if (!$model) {
            throw new ValidateException('数据不存在');
        }
        return Db::transaction(function () use ($model, $data) {
            return $model->save($data);
        });
    }

    public function delete(int $id): bool
    {
        $model = Customer::find($id);
        if (!$model) {
            throw new ValidateException('数据不存在');
        }
        return $model->delete();
    }
}
```

#### ③ Controller (`app/admin/controller/CustomerController.php`)
```php
<?php
namespace app\admin\controller;

use app\admin\service\CustomerService;
use app\admin\validate\CustomerValidate;
use app\common\Response;
use think\Request;
use think\response\Json;

class CustomerController
{
    public function index(Request $request, CustomerService $service): Json
    {
        $params = $request->get();
        $data = $service->getList($params);
        return Response::page($data['list'], $data['total'], $data['page'], $data['pageSize']);
    }

    public function save(Request $request, CustomerService $service, CustomerValidate $validate): Json
    {
        $params = $request->post();
        if (!$validate->scene('create')->check($params)) {
            return Response::error($validate->getError(), 400);
        }
        $model = $service->create($params);
        return Response::success($model, '创建成功');
    }

    public function update(Request $request, int $id, CustomerService $service, CustomerValidate $validate): Json
    {
        $params = $request->put();
        $params['id'] = $id;
        if (!$validate->scene('update')->check($params)) {
            return Response::error($validate->getError(), 400);
        }
        $service->update($id, $params);
        return Response::success(null, '更新成功');
    }

    public function delete(int $id, CustomerService $service): Json
    {
        $service->delete($id);
        return Response::success(null, '删除成功');
    }
}
```

---

### (2) 前端 React 示例 (`react-web/src/pages/customer/index.tsx`)

```tsx
import React, { useState } from 'react'
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Plus, Search, Trash2, Edit2 } from 'lucide-react'
import { request } from '@/api/request'
import { PageResult } from '@/types/api'

interface CustomerItem {
  id: number
  name: string
  phone: string
  status: number
  created_at: string
}

export const CustomerPage: React.FC = () => {
  const queryClient = useQueryClient()
  const [page, setPage] = useState(1)
  const [keyword, setKeyword] = useState('')

  // 1. 列表数据获取
  const { data, isLoading } = useQuery({
    queryKey: ['customers', page, keyword],
    queryFn: () =>
      request<PageResult<CustomerItem>>({
        url: '/api/admin/customer',
        method: 'GET',
        params: { page, pageSize: 10, keyword },
      }),
  })

  // 2. 删除 Mutation
  const deleteMutation = useMutation({
    mutationFn: (id: number) =>
      request({
        url: `/api/admin/customer/${id}`,
        method: 'DELETE',
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['customers'] })
    },
  })

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-bold tracking-tight">客户管理</h2>
        <Button className="space-x-1">
          <Plus className="h-4 w-4" />
          <span>新建客户</span>
        </Button>
      </div>

      {/* 搜索工具栏 */}
      <Card>
        <CardContent className="p-4 flex gap-3">
          <Input
            placeholder="搜索客户姓名 / 手机号..."
            value={keyword}
            onChange={(e) => setKeyword(e.target.value)}
            className="max-w-xs"
          />
          <Button variant="secondary" className="space-x-1">
            <Search className="h-4 w-4" />
            <span>查询</span>
          </Button>
        </CardContent>
      </Card>

      {/* 数据表格 */}
      <Card>
        <CardContent className="p-0">
          <table className="w-full text-sm text-left border-collapse">
            <thead className="border-b bg-muted/40 text-muted-foreground font-medium">
              <tr>
                <th className="p-4">ID</th>
                <th className="p-4">客户姓名</th>
                <th className="p-4">联系电话</th>
                <th className="p-4">状态</th>
                <th className="p-4">创建时间</th>
                <th className="p-4 text-right">操作</th>
              </tr>
            </thead>
            <tbody className="divide-y">
              {data?.data.list.map((item) => (
                <tr key={item.id} className="hover:bg-muted/20 transition-colors">
                  <td className="p-4">{item.id}</td>
                  <td className="p-4 font-medium">{item.name}</td>
                  <td className="p-4">{item.phone}</td>
                  <td className="p-4">
                    <Badge variant={item.status === 1 ? 'default' : 'secondary'}>
                      {item.status === 1 ? '正常' : '禁用'}
                    </Badge>
                  </td>
                  <td className="p-4 text-muted-foreground">{item.created_at}</td>
                  <td className="p-4 text-right space-x-2">
                    <Button variant="ghost" size="sm">
                      <Edit2 className="h-3.5 w-3.5 mr-1" />
                      编辑
                    </Button>
                    <Button
                      variant="ghost"
                      size="sm"
                      className="text-destructive hover:text-destructive"
                      onClick={() => deleteMutation.mutate(item.id)}
                    >
                      <Trash2 className="h-3.5 w-3.5 mr-1" />
                      删除
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </CardContent>
      </Card>
    </div>
  )
}
```
