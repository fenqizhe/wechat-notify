<?php
include "wechat.class.php";

//连接数据库的参数  
    $host = "localhost";  
    $user = "root";  
    $pass = "usbw";  
    $db = "mac_video";  

$options = array(
		'token'=>'btzy666', //填写你设定的key
        'encodingaeskey'=>'encodingaeskey' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
	);
$weObj = new Wechat($options);
$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
//处理消息
////消息类型，使用实例调用getRevType()方法取得
///const MSGTYPE_TEXT = 'text';
//const MSGTYPE_IMAGE = 'image';
//const MSGTYPE_LOCATION = 'location';
//const MSGTYPE_LINK = 'link';
//const MSGTYPE_EVENT = 'event';
//const MSGTYPE_MUSIC = 'music';
//const MSGTYPE_NEWS = 'news';
//const MSGTYPE_VOICE = 'voice';
//const MSGTYPE_VIDEO = 'video';
//MSGTYPE_SHORTVIDEO


function queryMySql($msg){
	//连接数据库的参数  
    $host = "localhost";  
    $user = "root";  
    $pass = "usbw";  
    $db = "mac_video";  
	//创建一个mysql连接  
    $connection = mysql_connect($host, $user, $pass) or die("Unable to connect!");  
	mysql_query("SET NAMES UTF8"); 
    //选择一个数据库  
    mysql_select_db($db) or die("Unable to select database!");  
    //开始查询  
    $query = "SELECT * FROM mac_vod WHERE d_name LIKE '%".$msg."%' OR d_subname LIKE '%".$msg."%' ORDER BY d_hits DESC,d_time DESC limit 7";  
	//执行SQL语句  
    $result = mysql_query($query) or die("Error in query: $query. ".mysql_error());  
	//显示返回的记录集行数  
	$news =array();
	$resultArr=array();
    $Count =mysql_num_rows($result);
	$resultArr[]=$Count;
    if($Count>0){  
	
		while($row=mysql_fetch_row($result)){
				$remarkes="【".$row[12]."】";			
		        if (empty($row[12])){
					$remarkes="";
				}
				$news[]=array(
							'Title'=>$row[1].$remarkes,
							'Description' => $row[2],
							'PicUrl' =>$row[6],
							'Url' =>"http://v2.pet2.net/?m=vod-play-id-".$row[0]."-src-1-num-1.html"
						);
							
			}  
			
		$news[]=array(
							'Title'=>"更多搜索结果",
							'Description' => "影视集",
							'PicUrl' =>"https://mmbiz.qlogo.cn/mmbiz_jpg/gficbZUAWqiccWiciatmF2uBZ0JCfybnPKf3PjvTUtJIsAiaz1hlP7xeSIhHFam2bsvzSKzy6YpPGADbNKMEKQolzww/0?wx_fmt=jpeg",
							'Url' =>"http://v2.pet2.net/"
						);
	
	}else{
		
		
		$news[]=array(
						'Title'=>"抱歉没有找到相关视频",
						'Description' => "",
						'PicUrl' =>"https://mmbiz.qlogo.cn/mmbiz_jpg/gficbZUAWqiccWiciatmF2uBZ0JCfybnPKf3cZk7eBGicicFwc0kXmpBJuH5YnrDfCNrYuzSdttgSEJmns9cKGFyibbnw/0?wx_fmt=jpeg",
						'Url' =>"http://v2.pet2.net/"
					);
		$news[]=array(
						'Title'=>"热门视频推荐",
						'Description' => "",
						'PicUrl' =>"https://mmbiz.qlogo.cn/mmbiz_jpg/gficbZUAWqiccWiciatmF2uBZ0JCfybnPKf3cZk7eBGicicFwc0kXmpBJuH5YnrDfCNrYuzSdttgSEJmns9cKGFyibbnw/0?wx_fmt=jpeg",
						'Url' =>"http://v2.pet2.net/"
					);	
		$news[]=array(
						'Title'=>"专题推荐",
						'Description' => "",
						'PicUrl' =>"https://mmbiz.qlogo.cn/mmbiz_jpg/gficbZUAWqiccWiciatmF2uBZ0JCfybnPKf3cZk7eBGicicFwc0kXmpBJuH5YnrDfCNrYuzSdttgSEJmns9cKGFyibbnw/0?wx_fmt=jpeg",
						'Url' =>"http://v2.pet2.net/"
					);
		$news[]=array(
						'Title'=>"广告位",
						'Description' => "",
						'PicUrl' =>"https://mmbiz.qlogo.cn/mmbiz_jpg/gficbZUAWqiccWiciatmF2uBZ0JCfybnPKf3cZk7eBGicicFwc0kXmpBJuH5YnrDfCNrYuzSdttgSEJmns9cKGFyibbnw/0?wx_fmt=jpeg",
						'Url' =>"http://v2.pet2.net/"
					);			
	}
	$resultArr[]=$news;

	return $resultArr;
	
}





function deBug($info){
	$open=fopen("log.txt","a" );
	fwrite($open,$info);
	fclose($open);

}

//用户行为统计
function sendUserAction($event,$msg,$id){
		//file_put_contents('./Public/Upload/test.txt',$msg);
	    $url = 'http://cloud.bmob.cn/5026270c37f23450/WeiXinMessager';
	    //$arr = array('appid'=>'btzy666','event'=>$event,'msg'=>$msg,'id'=>$id);
	    $postData = "appid=btzy666&event=".$event."&msg=".$msg."&id=".$id;
	    $ch = curl_init ();
	    // print_r($ch);
	    curl_setopt ( $ch, CURLOPT_URL, $url );
	    curl_setopt ( $ch, CURLOPT_POST, 1 );
	    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postData );
	    $output = curl_exec ( $ch );
	    curl_close ( $ch );
}
	
function replace_specialChar($strParam){
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
    return preg_replace($regex,"",$strParam);
}




/**
	 * 设置回复图文
	 * @param array $newsData
	 * 数组结构:
	 *  array(
	 *  	"0"=>array(
	 *  		'Title'=>'msg title',
	 *  		'Description'=>'summary text',
	 *  		'PicUrl'=>'http://www.domain.com/1.jpg',
	 *  		'Url'=>'http://www.domain.com/1.html'
	 *  	),
	 *  	"1"=>....
	 *  )
	 */
	 

$type = $weObj->getRev()->getRevType();
$id=$weObj->getRev()->getRevFrom();
switch($type) {
	case Wechat::MSGTYPE_TEXT:
			$msg = $weObj->getRev()->getRevContent();
			$resMsg = replace_specialChar($msg);

			//$resMsg=trim($msg,"/r/r(::).。！，？? ￥$【】[]@&*%$#->+=>");
			
			$result=queryMySql($resMsg);
			$weObj->news($result[1])->reply();
			//$weObj->text($result)->reply();

			if($result[0]==1){
				sendUserAction("istrue",$msg,$id);
			}else{
				sendUserAction("isfail",$msg,$id);
			}
			
			exit;
			break;
	case Wechat::MSGTYPE_IMAGE:
			$md=$weObj->getRev()->getRevPic(); 
			$weObj->image($md['mediaid'])->reply();
			sendUserAction("MSGTYPE_IMAGE","用户发来一张图片",$id);
			exit;
			break;
	case Wechat::MSGTYPE_LOCATION:
			$weObj->text("抱歉不能来找你")->reply();
			sendUserAction("MSGTYPE_LOCATION","用户上送位置信息",$id);
			exit;
			break;
	case Wechat::MSGTYPE_LINK:
			$weObj->text("待我访问后再来告诉你答案。")->reply();
			sendUserAction("MSGTYPE_LINK","用户发送了一个链接地址",$id);
			exit;
			break;
	case Wechat::MSGTYPE_EVENT:
			//$event= $weObj->getRev()->getRevEvent(); 
			//$debug=json_encode($event);
			deal($weObj);
			//$weObj->text($event['event'])->reply();
			exit;
			break;
	case Wechat::MSGTYPE_MUSIC:
			$weObj->text("原来你也喜欢听这首歌啊")->reply();
			sendUserAction("MSGTYPE_MUSIC","用户发送了一段音乐",$id);
			exit;
			break;
	case Wechat::MSGTYPE_NEWS:
			$weObj->text("正在认证阅读，稍后与你讨论。")->reply();
			sendUserAction("MSGTYPE_NEWS","用户发送了一组新闻",$id);
			exit;
			break;
	case Wechat::MSGTYPE_VOICE:
			$RevVoice = $weObj->getRev()->getRevContent();
			if($RevVoice==''){
				$weObj->text("你确定你说话的？")->reply();
			}else{
				$resMsg=trim($RevVoice,"/r/r(::).。！，？?");
				//$res=json_encode($RevVoice);
				$result=querybmob($resMsg);
				$weObj->news($result[1])->reply();
			}
			if($result[0]==1){
				sendUserAction("语音搜索-已回复资源","语音识别结果:".$RevVoice,$id);
			}else{
				sendUserAction("语音搜索-未找到资源","语音识别结果:".$RevVoice,$id);
			}
			exit;
			break;
	case Wechat::MSGTYPE_VIDEO:
			$resMsg="<a href='http://gx.missfood.net/'>也许你会喜欢这个，全民自制魔性视频，戳我！戳我！</a>";
			$weObj->text($resMsg)->reply();
			sendUserAction("MSGTYPE_VIDEO","用户发送了视频",$id);
			exit;
			break;
	case Wechat::MSGTYPE_SHORTVIDEO:
			$resMsg="<a href='http://gx.missfood.net/'>也许你会喜欢这个，全民自制魔性视频，戳我！戳我！</a>";
			$weObj->text($resMsg)->reply();
			sendUserAction("MSGTYPE_SHORTVIDEO","用户发送了一段短视频",$id);
			exit;
			break;
	default:
			$weObj->text("你发的什么呀，机器人太笨无法识别。")->reply();
			sendUserAction("未知消息类型","有可能是表情包",$id);
}


//处理事件
function deal($weObj){
	$event = $weObj->getRev()->getRevEvent();
	$id=$weObj->getRev()->getRevFrom();
	switch($event['event']) {
		case Wechat::EVENT_SUBSCRIBE:
		        sendUserAction("subscribe","",$id);
				//$weObj->text($event['event'])->reply();
				exit;
				break;
		case Wechat::EVENT_UNSUBSCRIBE:
		        sendUserAction("unsubscribe","",$id);
				//$weObj->text($event['event'])->reply();
				exit;
				break;
		case Wechat::EVENT_MENU_VIEW:
		        sendUserAction("VIEW",$event['key'],$id);
				//$weObj->text($event['event'])->reply();
				exit;
				break;
		case Wechat::EVENT_MENU_CLICK:
				//$weObj->text($event['event'])->reply();
				exit;
				break;
		default:
		        $debug=json_encode($event);
				//$weObj->text($debug)->reply();
	}
		
}








?>