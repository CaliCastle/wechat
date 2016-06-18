<?php

return [
    'token'         => env('WECHAT_TOKEN'),
    'id'            => env('WECHAT_ID'),
    'secret'        => env('WECHAT_SECRET'),
    'sendUrl'       => 'http://chat.abletive.com/functions/send_message.php',
    'replyUrl'      => 'http://chat.abletive.com/functions/get_reply.php',
    'credentialUrl' => 'https://api.weixin.qq.com/cgi-bin/user/info'
];