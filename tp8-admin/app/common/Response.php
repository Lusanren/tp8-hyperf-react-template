<?php
namespace app\common;

use think\Response as TpResponse;
use think\response\Json;

/**
 * 统一标准 JSON 响应工具类
 */
class Response
{
    /**
     * 成功响应
     * @param mixed $data 业务负载数据
     * @param string $msg 提示文本
     * @param int $code 业务状态码
     * @return Json
     */
    public static function success(mixed $data = null, string $msg = '操作成功', int $code = 200): Json
    {
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data ?? (object)[]
        ]);
    }

    /**
     * 失败/异常响应
     * @param string $msg 错误提示文本
     * @param int $code 业务错误状态码
     * @param mixed $data 补充错误详情
     * @param int $httpStatus HTTP 状态码
     * @return Json
     */
    public static function error(string $msg = '操作失败', int $code = 400, mixed $data = null, int $httpStatus = 200): Json
    {
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data ?? (object)[]
        ], $httpStatus);
    }

    /**
     * 分页列表统一响应
     * @param array $list 列表数据
     * @param int $total 总条数
     * @param int $page 当前页码
     * @param int $pageSize 每页大小
     * @param string $msg 提示文本
     * @return Json
     */
    public static function page(array $list, int $total, int $page, int $pageSize, string $msg = 'success'): Json
    {
        return self::success([
            'list'     => $list,
            'total'    => $total,
            'page'     => $page,
            'pageSize' => $pageSize
        ], $msg);
    }
}
