import React, { useState } from 'react'
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { fetchAiStream } from '@/api/hyperf'
import { Bot, Send, Sparkles, User, Loader2 } from 'lucide-react'

interface Message {
  id: string
  role: 'user' | 'assistant'
  content: string
}

export const AiChatPage: React.FC = () => {
  const [inputPrompt, setInputPrompt] = useState('')
  const [messages, setMessages] = useState<Message[]>([
    {
      id: 'welcome',
      role: 'assistant',
      content: '你好！我是由 Hyperf 3.x 协程驱动的 AI 流式助手。请在下方输入任何问题，我将通过 Server-Sent Events (SSE) 实时打字机流式响应！',
    },
  ])
  const [streaming, setStreaming] = useState(false)

  const handleSend = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!inputPrompt.trim() || streaming) return

    const userText = inputPrompt.trim()
    setInputPrompt('')

    const userMsg: Message = {
      id: String(Date.now()),
      role: 'user',
      content: userText,
    }

    const aiMsgId = String(Date.now() + 1)
    const assistantMsg: Message = {
      id: aiMsgId,
      role: 'assistant',
      content: '',
    }

    setMessages((prev) => [...prev, userMsg, assistantMsg])
    setStreaming(true)

    await fetchAiStream(
      userText,
      (chunk) => {
        setMessages((prev) =>
          prev.map((msg) =>
            msg.id === aiMsgId ? { ...msg, content: msg.content + chunk } : msg
          )
        )
      },
      () => {
        setStreaming(false)
      },
      (err) => {
        setMessages((prev) =>
          prev.map((msg) =>
            msg.id === aiMsgId
              ? { ...msg, content: msg.content + `\n[请求错误: ${err.message}]` }
              : msg
          )
        )
        setStreaming(false)
      }
    )
  }

  return (
    <div className="max-w-4xl mx-auto space-y-4">
      <div>
        <h2 className="text-2xl font-bold tracking-tight flex items-center gap-2">
          <Bot className="h-7 w-7 text-primary" />
          <span>AI 流式助手 (Hyperf SSE 协程)</span>
        </h2>
        <p className="text-muted-foreground text-sm">
          演示前端与 Hyperf 后端之间的 Server-Sent Events (SSE) 实时打字机长连接流式输出
        </p>
      </div>

      <Card className="h-[600px] flex flex-col shadow-sm">
        <CardHeader className="py-3 border-b bg-muted/20">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-2 text-sm">
              <Sparkles className="h-4 w-4 text-amber-500" />
              <span className="font-medium">Hyperf 协程常驻内存流式输出</span>
            </div>
            {streaming && (
              <div className="flex items-center space-x-1 text-xs text-primary animate-pulse">
                <Loader2 className="h-3.5 w-3.5 animate-spin" />
                <span>AI 正在思考生成中...</span>
              </div>
            )}
          </div>
        </CardHeader>

        <CardContent className="flex-1 overflow-y-auto p-4 space-y-4">
          {messages.map((m) => (
            <div
              key={m.id}
              className={`flex items-start gap-3 ${
                m.role === 'user' ? 'flex-row-reverse' : ''
              }`}
            >
              <div
                className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${
                  m.role === 'user'
                    ? 'bg-primary text-primary-foreground'
                    : 'bg-muted text-foreground'
                }`}
              >
                {m.role === 'user' ? <User className="h-4 w-4" /> : <Bot className="h-4 w-4" />}
              </div>

              <div
                className={`p-3.5 rounded-lg text-sm max-w-[80%] whitespace-pre-wrap leading-relaxed shadow-sm ${
                  m.role === 'user'
                    ? 'bg-primary text-primary-foreground'
                    : 'bg-card border text-card-foreground'
                }`}
              >
                {m.content || (streaming && m.id === messages[messages.length - 1].id ? '▋' : '')}
              </div>
            </div>
          ))}
        </CardContent>

        <CardFooter className="p-3 border-t bg-muted/10">
          <form onSubmit={handleSend} className="flex w-full gap-2">
            <Input
              placeholder="请输入提示词，例如：帮我写一份电商进销存系统的开发方案..."
              value={inputPrompt}
              onChange={(e) => setInputPrompt(e.target.value)}
              disabled={streaming}
              className="flex-1"
            />
            <Button type="submit" disabled={streaming || !inputPrompt.trim()} className="space-x-1">
              <Send className="h-4 w-4" />
              <span>发送</span>
            </Button>
          </form>
        </CardFooter>
      </Card>
    </div>
  )
}
