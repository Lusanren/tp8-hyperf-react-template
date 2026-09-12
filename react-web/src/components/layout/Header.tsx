import React from 'react'
import { useUserStore } from '@/store/userStore'
import { useMenuStore } from '@/store/menuStore'
import { Button } from '@/components/ui/button'
import { Menu, LogOut, User } from 'lucide-react'
import { useNavigate } from 'react-router-dom'
import { logoutApi } from '@/api/auth'

export const Header: React.FC = () => {
  const { userInfo, logout } = useUserStore()
  const { toggleCollapse } = useMenuStore()
  const navigate = useNavigate()

  const handleLogout = async () => {
    try {
      await logoutApi()
    } finally {
      logout()
      navigate('/login')
    }
  }

  return (
    <header className="h-16 border-b bg-card flex items-center justify-between px-6 sticky top-0 z-30">
      <div className="flex items-center space-x-4">
        <Button variant="ghost" size="icon" onClick={toggleCollapse}>
          <Menu className="h-5 w-5" />
        </Button>
        <h1 className="text-lg font-semibold tracking-tight">管理控制台</h1>
      </div>

      <div className="flex items-center space-x-4">
        <div className="flex items-center space-x-2 text-sm">
          <div className="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
            <User className="h-4 w-4" />
          </div>
          <span className="font-medium">{userInfo?.nickname || userInfo?.username || '管理员'}</span>
        </div>

        <Button variant="outline" size="sm" onClick={handleLogout} className="space-x-1">
          <LogOut className="h-4 w-4" />
          <span>退出登录</span>
        </Button>
      </div>
    </header>
  )
}
