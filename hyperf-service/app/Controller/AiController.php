<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AiService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Engine\ResponseEmitter\SslResponseEmitter;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Swoole\Http\Response as SwooleResponse;

class AiController extends AbstractController
{
    #[Inject]
    protected AiService $aiService;

    /**
     * AI 对话 SSE 流式接口: POST /v1/ai/chat/stream
     */
    public function stream()
    {
        $prompt = (string)$this->request->input('prompt', '');
        if (empty($prompt)) {
            return $this->error('Prompt 不能为空', 400);
        }

        // 获取底层 Swoole 响应对象进行流式分块写入
        /** @var SwooleResponse $rawResponse */
        $rawResponse = $this->response->unwrap();

        $rawResponse->header('Content-Type', 'text/event-stream; charset=utf-8');
        $rawResponse->header('Cache-Control', 'no-cache');
        $rawResponse->header('Connection', 'keep-alive');
        $rawResponse->header('X-Accel-Buffering', 'no');

        $this->aiService->generateChatStream($prompt, function(array $data) use ($rawResponse) {
            $rawResponse->write("data: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n\n");
        });

        $rawResponse->end();
        return '';
    }
}
