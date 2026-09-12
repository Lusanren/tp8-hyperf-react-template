import React, { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { loginApi } from '@/api/auth'
import { useUserStore } from '@/store/userStore'
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Sparkles, Lock, User } from 'lucide-react'

export const LoginPage: React.FC = () => {
  const [username, setUsername] = useState('admin')
  const [password, setPassword] = useState('admin123')
  const [loading, setLoading] = useState(false)
  const [errorMsg, setErrorMsg] = useState('')

  const { setLogin } = useUserStore()
  const navigate = useNavigate()

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrorMsg('')
    setLoading(true)

    try {
      const res = await loginApi({ username, password })
      setLogin(res.data.token, res.data.userInfo)
      navigate('/dashboard')
    } catch (err: unknown) {
      setErrorMsg(err instanceof Error ? err.message : '登录失败，请检查账号密码')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen w-full flex items-center justify-center bg-muted/40 p-4">
      <Card className="w-full max-w-md shadow-xl border-border">
        <CardHeader className="text-center space-y-2">
          <div className="mx-auto w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-primary-foreground shadow-md">
            <Sparkles className="h-6 w-6" />
          </div>
          <CardTitle className="text-2xl font-bold">系统管理登录</CardTitle>
          <CardDescription>
            ThinkPHP8 + Hyperf 纯 PHP 架构全栈预制脚手架
          </CardDescription>
        </CardHeader>

        <form onSubmit={handleLogin}>
          <CardContent className="space-y-4">
            {errorMsg && (
              <div className="p-3 text-sm text-destructive bg-destructive/10 rounded-md">
                {errorMsg}
              </div>
            )}

            <div className="space-y-2">
              <label className="text-sm font-medium text-foreground flex items-center gap-1.5">
                <User className="h-4 w-4 text-muted-foreground" />
                <span>账号</span>
              </label>
              <Input
                placeholder="请输入用户名 (默认: admin)"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                required
              />
            </div>

            <div className="space-y-2">
              <label className="text-sm font-medium text-foreground flex items-center gap-1.5">
                <Lock className="h-4 w-4 text-muted-foreground" />
                <span>密码</span>
              </label>
              <Input
                type="password"
                placeholder="请输入密码 (默认: admin123)"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
            </div>
          </CardContent>

          <CardFooter className="flex flex-col space-y-3">
            <Button type="submit" className="w-full" disabled={loading}>
              {loading ? '正在登录...' : '立即登录'}
            </Button>
            <p className="text-xs text-muted-foreground text-center">
              默认体验账号：admin / admin123
            </p>
          </CardFooter>
        </form>
      </Card>
    </div>
  )
}
