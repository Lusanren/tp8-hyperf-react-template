import React from 'react'
import { Link, useLocation } from 'react-router-dom'
import { useMenuStore } from '@/store/menuStore'
import { cn } from '@/lib/utils'
import { LayoutDashboard, Bot, Sparkles } from 'lucide-react'

export const Sidebar: React.FC = () => {
  const { isCollapsed } = useMenuStore()
  const location = useLocation()

  const navItems = [
    { title: '控制台概览', path: '/dashboard', icon: LayoutDashboard },
    { title: 'AI 对话助手 (SSE)', path: '/ai-chat', icon: Bot },
  ]

  return (
    <aside
      className={cn(
        'border-r bg-card h-screen flex flex-col transition-all duration-300 select-none',
        isCollapsed ? 'w-16' : 'w-64'
      )}
    >
      <div className="h-16 border-b flex items-center px-4 space-x-3">
        <div className="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-primary-foreground font-bold">
          <Sparkles className="h-5 w-5" />
        </div>
        {!isCollapsed && (
          <span className="font-bold text-base tracking-tight truncate">
            TP8 + Hyperf AI
          </span>
        )}
      </div>

      <nav className="flex-1 p-3 space-y-1">
        {navItems.map((item) => {
          const Icon = item.icon
          const isActive = location.pathname === item.path
          return (
            <Link
              key={item.path}
              to={item.path}
              className={cn(
                'flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors',
                isActive
                  ? 'bg-primary text-primary-foreground'
                  : 'text-muted-foreground hover:bg-muted hover:text-foreground'
              )}
            >
              <Icon className="h-5 w-5 shrink-0" />
              {!isCollapsed && <span>{item.title}</span>}
            </Link>
          )
        })}
      </nav>

      <div className="p-4 border-t text-xs text-muted-foreground text-center">
        {!isCollapsed && <span>AI-Native Scaffold v1.0</span>}
      </div>
    </aside>
  )
}
