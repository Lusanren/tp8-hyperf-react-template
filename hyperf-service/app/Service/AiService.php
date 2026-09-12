<?php
declare(strict_types=1);

namespace App\Service;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Swoole\Coroutine;
use Throwable;

class AiService
{
    #[Inject]
    protected ConfigInterface $config;

    #[Inject]
    protected ClientFactory $clientFactory;

    /**
     * 仿真或真实调用大模型流式输出
     * @param string $prompt 用户输入
     * @param callable $callback 每次生成分块时回调给客户端
     */
    public function generateChatStream(string $prompt, callable $callback): void
    {
        $apiKey = (string)$this->config->get('ai.api_key', '');
        $baseUrl = (string)$this->config->get('ai.base_url', 'https://api.openai.com/v1');
        $model = (string)$this->config->get('ai.model', 'gpt-3.5-turbo');

        // 如果配置了有效 API Key，则使用 Hyperf Guzzle 协程客户端进行真实请求
        if (!empty($apiKey) && str_starts_with($apiKey, 'sk-')) {
            try {
                $client = $this->clientFactory->create([
                    'base_uri' => $baseUrl,
                    'timeout'  => 60.0,
                ]);

                $response = $client->post('/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model'    => $model,
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                        'stream'   => true,
                    ],
                    'stream' => true,
                ]);

                $body = $response->getBody();
                while (!$body->eof()) {
                    $line = trim($body->read(1024));
                    if (str_starts_with($line, 'data: ')) {
                        $dataStr = trim(substr($line, 6));
                        if ($dataStr === '[DONE]') {
                            $callback(['content' => '', 'done' => true]);
                            return;
                        }
                        $json = json_decode($dataStr, true);
                        $delta = $json['choices'][0]['delta']['content'] ?? '';
                        if ($delta !== '') {
                            $callback(['content' => $delta, 'done' => false]);
                        }
                    }
                }
                $callback(['content' => '', 'done' => true]);
                return;
            } catch (Throwable $e) {
                $callback(['content' => '[AI 调用异常: ' . $e->getMessage() . ']', 'done' => false]);
                $callback(['content' => '', 'done' => true]);
                return;
            }
        }

        // 开箱即用 Demo 协程打字机流式输出
        $demoText = "你好！我是基于 Hyperf 3.x 协程框架驱动的纯 PHP 高性能 AI 助手。\n\n"
            . "已收到你的请求：『" . $prompt . "』。\n\n"
            . "本接口采用标准 Server-Sent Events (SSE) 协议通过 Swoole 协程长驻内存流式推送给前端。\n"
            . "在生产环境中，你只需在 `.env` 中配置 `OPENAI_API_KEY` 即可一键接入真实大模型。";

        // 将文本按字符拆分逐个通过协程推送
        $chars = mb_str_split($demoText);
        foreach ($chars as $char) {
            $callback(['content' => $char, 'done' => false]);
            Coroutine::sleep(0.03); // 协程非阻塞睡眠
        }

        $callback(['content' => '', 'done' => true]);
    }
}
