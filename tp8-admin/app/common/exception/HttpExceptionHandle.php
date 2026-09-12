<?php
namespace app\common\exception;

use app\common\Response;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Response as TpResponse;
use Throwable;

/**
 * 全局未捕获异常统一接管，确保所有错误均输出统一 JSON 结构
 */
class HttpExceptionHandle extends Handle
{
    public function render($request, Throwable $e): TpResponse
    {
        // 1. 参数验证错误
        if ($e instanceof ValidateException) {
            return Response::error($e->getError(), 400);
        }

        // 2. HTTP 异常 (如 404)
        if ($e instanceof HttpException) {
            return Response::error($e->getMessage(), $e->getStatusCode(), null, $e->getStatusCode());
        }

        // 3. 数据库数据未找到
        if ($e instanceof DataNotFoundException || $e instanceof ModelNotFoundException) {
            return Response::error('请求的数据实体不存在', 404, null, 404);
        }

        // 4. 开发调试模式下可输出详细错误堆栈
        if (env('APP_DEBUG', false)) {
            return Response::error($e->getMessage(), 500, [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString())
            ], 500);
        }

        // 5. 生产模式屏蔽敏感堆栈
        return Response::error('服务器内部异常，请联系管理员', 500, null, 500);
    }
}
