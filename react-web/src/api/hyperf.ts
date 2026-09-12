import { request } from './request'

/**
 * Hyperf 健康检查
 */
export function getHyperfHealthApi() {
  return request<{ status: string; service: string; version: string }>({
    url: '/api/hyperf/v1/health',
    method: 'GET',
  })
}

/**
 * Hyperf SSE 流式 AI 对话请求封装
 * @param prompt 用户输入
 * @param onMessage 接收每次分块内容
 * @param onDone 完成回调
 * @param onError 异常回调
 */
export async function fetchAiStream(
  prompt: string,
  onMessage: (chunk: string) => void,
  onDone: () => void,
  onError: (err: Error) => void
) {
  const token = localStorage.getItem('token') || ''
  try {
    const response = await fetch('/api/hyperf/v1/ai/chat/stream', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify({ prompt }),
    })

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`)
    }

    if (!response.body) {
      throw new Error('ReadableStream not supported')
    }

    const reader = response.body.getReader()
    const decoder = new TextDecoder('utf-8')
    let buffer = ''

    while (true) {
      const { value, done } = await reader.read()
      if (done) {
        onDone()
        break
      }

      buffer += decoder.decode(value, { stream: true })
      const lines = buffer.split('\n\n')
      buffer = lines.pop() || ''

      for (const line of lines) {
        if (line.startsWith('data: ')) {
          const jsonStr = line.replace(/^data:\s*/, '').trim()
          if (!jsonStr) continue
          try {
            const parsed = JSON.parse(jsonStr)
            if (parsed.content) {
              onMessage(parsed.content)
            }
            if (parsed.done) {
              onDone()
              return
            }
          } catch {
            // ignore non-json chunk
          }
        }
      }
    }
  } catch (err: unknown) {
    onError(err instanceof Error ? err : new Error(String(err)))
  }
}
