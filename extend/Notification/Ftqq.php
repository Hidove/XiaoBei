<?php

namespace Notification;

class Ftqq implements Notification
{

    private $error = '';

    public function main($opt): bool
    {
        $url = "https://sctapi.ftqq.com/{$opt['sct_key']}.send";
        $data = [
            'title' => $opt['title'],
            'desp' => $opt['content'],
        ];
        $hidove_post = hidove_post($url, $data);
        $json_decode = json_decode($hidove_post);
        if (isset($json_decode->code) && $json_decode->code != 0) {
            $this->error = $json_decode->message;
            return false;
        }
        return true;
    }

    public function getError(): string
    {
        return $this->error;
    }
}