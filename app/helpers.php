<?php

if (! function_exists('sendTextXML')) {
    /**
     * Send text representation.
     *
     * @param $to
     * @param $from
     * @param $content
     * @return string
     */
    function sendTextXML($to, $from, $content)
    {
        $template = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        $xml = sprintf($template, $to, $from, time(), $content);

        return $xml;
    }
}

if (! function_exists('sendImageXML')) {
    /**
     * Send image representation.
     *
     * @param $to
     * @param $from
     * @param $media_id
     * @return string
     */
    function sendImageXML($to, $from, $media_id)
    {
        $template = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                <MediaId><![CDATA[%s]]></MediaId>
                </Image>
                </xml>";
        $xml = sprintf($template, $to, $from, time(), $media_id);

        return $xml;
    }
}

if (! function_exists('sendMessageToChat')) {
    /**
     * Send a message to chat.
     *
     * @param $user_id
     * @param $content
     */
    function sendMessageToChat($user_id, $content)
    {
        client()->postAsync(config('wechat.sendUrl'), [
            'query' => [
                'chat_message' => $content,
                'from'         => 1,
                'user_id'      => $user_id
            ]
        ]);
    }
}

if (! function_exists('getReplyFromChat')) {
    /**
     * Get reply from a chat.
     *
     * @param $user_id
     * @param $content
     * @return mixed
     */
    function getReplyFromChat($user_id, $content)
    {
        $answer = client()->post(config('wechat.replyUrl'), [
            'query' => [
                'from_wechat' => 1,
                'message'     => (string)$content,
                'user_id'     => (string)$user_id
            ]
        ])->getBody()->getContents();

        $newAnswer = str_replace("图灵机器人", "小A", $answer);

        return $newAnswer;
    }
}

if (! function_exists('client')) {
    /**
     * Get the client.
     *
     * @return \GuzzleHttp\Client
     */
    function client()
    {
        return new \GuzzleHttp\Client;
    }
}

if (! function_exists('slack')) {
    /**
     * Send notification to slack.
     * 
     * @param $content
     * @param $answer
     */
    function slack($content, $answer) 
    {
        Slack::send("来自微信用户: :point_right: " . addslashes($content) . "\n>:bulb: 小A答复：_" . addslashes($answer) . "_" . "\n\n<https://mp.weixin.qq.com/cgi-bin/message|点击前往公众号管理页面>");
    }
}