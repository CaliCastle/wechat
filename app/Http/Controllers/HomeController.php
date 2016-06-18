<?php

namespace App\Http\Controllers;

use Token;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * First time welcome message.
     * 
     * @var string
     */
    protected $welcomeMessage = "感谢你的关注~ 初次见面请多关照噢(●'◡'●)ﾉ♥ 我叫小A，是 Abletive 的智能AI，赶快来认领我与我聊天哟，launchpad没灯光没声音，io等常见问题，输入关键字【工程下载】，【abletive】，【bgm下载】，【城市名+天气预报】，【我的小A】，【技能列表】，【现在几点】等技能等你来发掘<span class=\"emoji emoji27b0\"></span>。喜欢的话别忘了告诉小伙伴们噢_(:з」∠)_ 哈哈";
    
    /**
     * Endpoint handler.
     * 
     * @param Request $request
     */
    public function index(Request $request)
    {
        $token = config('wechat.token');
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        $echostring = $request->input('echostr');
        $params = array($timestamp, $token, $nonce);
        sort($params);

        $temp = implode("", $params);
        $temp = sha1($temp);

        $signature = $request->input('signature');

        if ($temp == $signature && $echostring) {
            exit($echostring);
        } else {
            $this->replyMessage();
        }
    }

    /**
     * Get the appropriate message to reply.
     * 
     * @return string
     */
    public function replyMessage(Request $request)
    {
        // Get the data from Wechat server (Format: XML)
        $xmlString = $request->getQueryString();
        // Example:
        $xmlObject = simplexml_load_string($xmlString);
        $xmlObject->MsgType = strtolower($xmlObject->MsgType);
        
        switch ($xmlObject->MsgType) {
            /*
            <xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[FromUser]]></FromUserName>
            <CreateTime>123456789</CreateTime>
            <MsgType><![CDATA[event]]></MsgType>
            <Event><![CDATA[subscribe]]></Event>
            </xml>
            */
            case 'event':
                $xmlObject->Event = strtolower($xmlObject->Event);
                if ($xmlObject->Event == 'subscribe') {
                    // Reply a message back to user
                    $ToUserName = $xmlObject->FromUserName;
                    $FromUserName = $xmlObject->ToUserName;

                    echo sendTextXML($ToUserName, $FromUserName, $this->welcomeMessage);
                }
                break;
            /*
             <xml>
             <ToUserName><![CDATA[toUser]]></ToUserName>
             <FromUserName><![CDATA[fromUser]]></FromUserName> 
             <CreateTime>1348831860</CreateTime>
             <MsgType><![CDATA[text]]></MsgType>
             <Content><![CDATA[this is a test]]></Content>
             <MsgId>1234567890123456</MsgId>
             </xml>
            */
            case 'text':
                // What the user sent to us
                $xmlObject->Content = strtolower($xmlObject->Content);
                // Reply a message back to user
                $ToUserName = $xmlObject->FromUserName;
                $FromUserName = $xmlObject->ToUserName;

                switch ($xmlObject->Content) {
                    case '网页':
                    case '链接':
                        $Content = '网页在线版<a href="http://chat.abletive.com/">小 A 地址</a>';
                        break;
                    case 'bgm下载':
                        $Content = 'Abletive社区2.0的 BGM 下载：\n\n<a href="https://dn-abletive.qbox.me/music/01%20Stay%20with%20Me.m4a" >Christina Grimmie & Diamond Eyes - Stay With Me</a>\n<a href="https://dn-abletive.qbox.me/music/01%20Tame.m4a" >Kezwik - Tame</a>\n<a href="https://dn-abletive.qbox.me/music/02%20Hold%20Your%20Breath.m4a" >Kezwik - Hold Your Breath</a>\n<a href="https://dn-abletive.qbox.me/music/03%20Dirty%20Fresh%20-%20Maybe%20In%20Japan%20-%209A%20-%20110.mp3" >DJ Fresh - Maybe In Japan</a>\n<a href="https://dn-abletive.qbox.me/music/04%20Promise%20Me.m4a">Kezwik - Promise Me</a>\n<a href="https://dn-abletive.qbox.me/music/1-Snowblind%20(Original%20Mix).mp3" >Au5 - Snowblind</a>\n<a href="https://dn-abletive.qbox.me/music/Astronaut%20-%20Earthsphere%20-%204A%20-%20140.mp3" >Astronaut - Earthsphere</a>\n<a href="https://dn-abletive.qbox.me/music/Corporate%20-%20Forward%20-%209A%20-%20140.mp3" >Corporate - Forward</a>\n<a href="https://dn-abletive.qbox.me/music/Hyper%20(Original%20Mix).mp3" >Mr.Cali - Hyper</a>\n<a href="https://dn-abletive.qbox.me/music/Kevin%20Drew%20-%20Lost%20-%204A%20-%20140.mp3" >Kevin Drew - Lost</a>\n<a href="https://dn-abletive.qbox.me/music/Pop%20Dance%20Skrillex%20-%207B%20-%20106.mp3" >Skrillex - Pop Dance</a>\n<a href="https://dn-abletive.qbox.me/music/Retro%20City%20(David%20A%20Remix).mp3" >David A. - Retro City</a>\n<a href="https://dn-abletive.qbox.me/music/Show-Me-Ur-Tits.mp3">David A. - Show Me Ur Tits</a>\n<a href="https://dn-abletive.qbox.me/music/twerk%20(david%20a%20remix).mp3" >David A - Twerk</a>\n<a href="https://dn-abletive.qbox.me/music/Varien%20ft.%20Laura%20Brehm%20-%20Valkyrie%20-%203A%20-%2087.mp3" >Varien - Valkyrie</a>';
                        break;
                    case '工程下载':
                    case '下载工程':
                    case '工程':
                        $Content = '<a href="http://abletive.com/category/sharing/launchpad-live-sets" >点击前往社区下载</a>, 如果你是社区 VIP 会员的话可以前往<a href="http://abletive.com/vip/">VIP 专属页面</a>下载噢 (^-^) ';
                        break;
                    case 'live下载':
                    case '下载live':
                    case '破解':
                    case '破解live':
                    case 'live安装':
                    case '安装live':
                        $Content = '<a href="http://abletive.com/tutorial/live-tutorial/live-installation">社区安装教程</a>';
                        break;
                    case 'vip':
                        $Content = '<a href="http://abletive.com/vip/">VIP 专属页面</a>, 工程专属下载页面';
                        break;
                    case 'abletive':
                        $Content = '<a href="http://abletive.com/" >Abletive 音乐社区</a>，我的家~ (^0^)/传送门：\n<a href="http://bbs.abletive.com/home" >Abletive 论坛</a>\n<a href="http://lp.abletive.com/" >Abletive第一届Launchpad 工程大赛</a>\n<a href="http://www.calicastle.com/" >Cali 男神的主页</a>\n<a href="http://wechat.abletive.com/" >官方微信平台</a>\n<a href="http://weibo.com/abletive" >官方微博</a>';
                        break;
                    default:
//                        $Content = C('COMING_SOON');
                        sendMessageToChat($ToUserName, $xmlObject->Content);
                        if ($reply = getReplyFromChat($ToUserName, $xmlObject->Content)) {
                            $Content = $reply;
                        }
                        break;
                }

                echo sendTextXML($ToUserName, $FromUserName, $Content);

                break;
            /*
            <xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[fromUser]]></FromUserName>
            <CreateTime>1357290913</CreateTime>
            <MsgType><![CDATA[voice]]></MsgType>
            <MediaId><![CDATA[media_id]]></MediaId>
            <Format><![CDATA[Format]]></Format>
            <Recognition><![CDATA[腾讯微信团队]]></Recognition>
            <MsgId>1234567890123456</MsgId>
            </xml>
            */
            case 'voice':
                // What the user sent to us
                $xmlObject->Recognition = strtolower($xmlObject->Recognition);
                // Reply a message back to user
                $ToUserName = $xmlObject->FromUserName;
                $FromUserName = $xmlObject->ToUserName;

                sendMessageToChat($ToUserName, $xmlObject->Recognition);
                if ($reply = getReplyFromChat($ToUserName, $xmlObject->Recognition)) {
                    $Content = $reply;
                    echo sendTextXML($ToUserName, $FromUserName, $Content);
                }

                break;
            default:
                break;
        }
        
        return '';
    }

    /**
     * Get the access token.
     * 
     * @return string
     */
    public function getToken()
    {
        return Token::get();
    }
}