import React, { useEffect, useState } from 'react'
import { Card, CardHeader, CardTitle, CardContent, CardDescription } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { getUserProfileApi } from '@/api/auth'
import { getHyperfHealthApi } from '@/api/hyperf'
import { UserInfo } from '@/types/api'
import { Server, Zap, CheckCircle2, ShieldCheck } from 'lucide-react'

export const DashboardPage: React.FC = () => {
  const [profile, setProfile] = useState<UserInfo | null>(null)
  const [hyperfStatus, setHyperfStatus] = useState<{ status: string; version: string; swoole: string } | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    async function loadData() {
      try {
        const [userRes, hyperfRes] = await Promise.allSettled([
          getUserProfileApi(),
          getHyperfHealthApi(),
        ])

        if (userRes.status === 'fulfilled') {
          setProfile(userRes.value.data)
        }
        if (hyperfRes.status === 'fulfilled') {
          setHyperfStatus(hyperfRes.value.data as { status: string; version: string; swoole: string })
        }
      } finally {
        setLoading(false)
      }
    }
    loadData()
  }, [])

  return (
    <div className="space-y-6">
      <div>
        <h2 className="text-2xl font-bold tracking-tight">控制台概览</h2>
        <p className="text-muted-foreground text-sm">
          ThinkPHP8 + Hyperf 双后端架构状态自检与运行概况
        </p>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {/* ThinkPHP 8 状态卡片 */}
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2 space-y-0">
            <CardTitle className="text-sm font-medium">TP8 业务管理端</CardTitle>
            <Server className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="flex items-center space-x-2">
              <Badge variant="default" className="bg-emerald-600 hover:bg-emerald-600">
                <CheckCircle2 className="h-3.5 w-3.5 mr-1" />
                正常运行
              </Badge>
              <span className="text-xs text-muted-foreground">端口 8001</span>
            </div>
            <p className="text-xs text-muted-foreground mt-3">
              负责用户认证、RBAC 权限、业务主数据 CRUD
            </p>
          </CardContent>
        </Card>

        {/* Hyperf 状态卡片 */}
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2 space-y-0">
            <CardTitle className="text-sm font-medium">Hyperf 协程微服务</CardTitle>
            <Zap className="h-4 w-4 text-amber-500" />
          </CardHeader>
          <CardContent>
            <div className="flex items-center space-x-2">
              <Badge variant="default" className="bg-amber-600 hover:bg-amber-600">
                <CheckCircle2 className="h-3.5 w-3.5 mr-1" />
                协程就绪
              </Badge>
              <span className="text-xs text-muted-foreground">端口 9501</span>
            </div>
            <p className="text-xs text-muted-foreground mt-3">
              负责 AI 流式打字机 (SSE)、WebSocket 与高并发任务
            </p>
          </CardContent>
        </Card>

        {/* 共享鉴权状态 */}
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2 space-y-0">
            <CardTitle className="text-sm font-medium">统一 JWT 鉴权体系</CardTitle>
            <ShieldCheck className="h-4 w-4 text-blue-500" />
          </CardHeader>
          <CardContent>
            <div className="flex items-center space-x-2">
              <Badge variant="default" className="bg-blue-600 hover:bg-blue-600">
                共享 Secret
              </Badge>
              <span className="text-xs text-muted-foreground">免 RPC 互通</span>
            </div>
            <p className="text-xs text-muted-foreground mt-3">
              当前登录身份: <span className="font-semibold">{profile?.nickname || 'admin'}</span> ({profile?.role || 'superadmin'})
            </p>
          </CardContent>
        </Card>
      </div>

      {/* 架构使用指引 */}
      <Card>
        <CardHeader>
          <CardTitle>🚀 AI-Native 快速开工指引</CardTitle>
          <CardDescription>
            您可以直接在 AI 助手 (如 Cursor / Windsurf / Antigravity) 中开展业务编码
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-3 text-sm text-muted-foreground">
          <div className="p-3 bg-muted rounded-md space-y-1">
            <div className="font-medium text-foreground">1. 业务立项与需求对齐</div>
            <div>阅读并填充根目录的 <code>PROJECT_BRIEF.md</code>，向 AI 描述您的具体系统功能。</div>
          </div>
          <div className="p-3 bg-muted rounded-md space-y-1">
            <div className="font-medium text-foreground">2. 扩展业务接口与表单</div>
            <div>常规业务 CRUD 编写在 <code>tp8-admin/</code>；大模型与长耗时任务编写在 <code>hyperf-service/</code>。</div>
          </div>
          <div className="p-3 bg-muted rounded-md space-y-1">
            <div className="font-medium text-foreground">3. 前端界面开发</div>
            <div>在 <code>react-web/src/pages/</code> 编写新页面，使用 Tailwind + shadcn/ui 快速构筑高颜值交互。</div>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
