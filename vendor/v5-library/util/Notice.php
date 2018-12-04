<?php

namespace V5\Library\Util;

class Notice
{
    private $code = null;
    private $message = null;
    private $data = null;

    public function __construct(array $noticeCode, $data = null)
    {
        $this->code = $noticeCode['code'];
        $this->message = $noticeCode['message'];
        $this->data = $data;
    }

    public function response($isJsonp = false)
    {
        $response = $this->toArray();

        if (is_null($this->data)) {
            unset($response['data']);
        }

        if ($isJsonp) {
            return jsonp($response);
        }

        return json($response);
    }

    public function appendData($append)
    {
        $data = $this->data;

        if (is_array($data)) {
            $data = array_merge($data, $append);
        }

        if (is_string($data)) {
            $data = $append;
        }

        $this->data = $data;
    }

    public function toArray()
    {
        return [
            'notice' => true,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
