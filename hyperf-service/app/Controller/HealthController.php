<?php
declare(strict_types=1);

namespace App\Controller;

class HealthController extends AbstractController
{
    public function index()
    {
        return $this->success([
            'status'  => 'ok',
            'service' => 'hyperf-service',
            'version' => '3.1.0',
            'swoole'  => defined('SWOOLE_VERSION') ? SWOOLE_VERSION : 'unknown'
        ]);
    }
}
