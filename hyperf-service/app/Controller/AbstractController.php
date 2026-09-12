<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;

    protected function success(mixed $data = null, string $msg = 'success', int $code = 200)
    {
        return $this->response->json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data ?? (object)[]
        ]);
    }

    protected function error(string $msg = 'error', int $code = 400, mixed $data = null, int $status = 200)
    {
        return $this->response->json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data ?? (object)[]
        ])->withStatus($status);
    }
}
