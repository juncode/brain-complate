<?php
/**
 * socket 进程 启动器
 * User: huangjun<j@wonhsi.com>
 * Date: 2018/2/9
 * Time: 18:03
 */

namespace app\socket\controller;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;

class Start
{
    public function __construct()
    {
        new Register("text://0.0.0.0:1236");

        $worker = new BusinessWorker();

        $worker->name = "WechatBrainBusiness";
        $worker->count = 2;
        $worker->registerAddress = "127.0.0.1:1236";

        $worker->eventHandler = "\app\socket\controller\Events";

        $gateway = new Gateway("websocket://0.0.0.0:7272");

        $gateway->name = "WechatBrainGateway";
        $gateway->count = 2;
        $gateway->lanIp = "127.0.0.1";
        $gateway->startPort = '2300';
        $gateway->registerAddress = '127.0.0.1:1236';
        // 心跳间隔
        $gateway->pingInterval = 2;
        // 心跳数据
        $gateway->pingData = '{"type":"ping"}';

        Worker::runAll();
    }
}