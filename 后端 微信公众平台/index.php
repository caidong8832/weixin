<?php


define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    
    
  
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        $category = substr($keyword,0,6);
        $code = trim(substr($keyword,6,strlen($keyword)));
        switch ($category)
        {
            case "会员":
                $content[] = array("Title" =>"会员卡", 
                "Description" =>"点击图片领取会员卡", 
                "PicUrl" =>"http://pic2.ooopic.com/01/38/77/75bOOOPICea.jpg", 
                "Url" =>"http://cctv15.sinaapp.com/member.php?openid=".$object->FromUserName);
                break;
            case "预订":
                $content[] = array("Title" =>"预订酒店", 
                "Description" =>"点击图片开始预约", 
                "PicUrl" =>"http://pic42.photophoto.cn/20170124/1155117102486842_b.jpg", 
                "Url" =>"http://caidong8832.applinzi.com/order.php?openid=".$object->FromUserName);
                break;
            case "房间":
               $conn = new mysqli(SAE_MYSQL_HOST_M, SAE_MYSQL_USER, SAE_MYSQL_PASS , SAE_MYSQL_DB);
                $sql="select * from fangjian";  
               $result = $conn->query($sql);
               if ($result->num_rows > 0) {
               // 输出每行数据
               while($row = $result->fetch_assoc()) {
                    
                    $content.="\n房间id: ".$row["content"];
                    }   
            } else {
                $content =  "0 个结果";
            }
                break;
           
         

            default:
                $content = "";
                break;
        }
        if(is_array($content)){
            $result = $this->transmitNews($object, $content);
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    private function transmitNews($object, $arr_item)
    {
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item));
        return $result;
    }

    private function logger($log_content)
    {
    }
}


?>