<?php
include "qywechat.class.php";
//支持的消息类型
//图片 文件 图文 文本 
//请求post
//content 消息内容 消息发送通道(微信测试号) color 设置消息颜色
$options = array(
  'token'=>'ysjqyweixin', //填写应用接口的Token
  'encodingaeskey'=>'', //填写加密用的EncodingAESKey
  'appid'=>'', //填写高级调用功能的app id
  'appsecret'=>'', //填写高级调用功能的密钥
  'agentid'=>'11', //应用的id
  'debug'=>true, //调试开关
  '_logcallback'=>'logg', //调试输出方法，需要有一个string类型的参数
);


$weObj = new Wechat($options); //创建实例对象

//默认值
$msgInfo=array(
	"color" => "#0066CC",
	"fromusername" =>"Service Message",
	"userid" =>"Service Default",
	"event" =>"Notify",
	"eventkey"=>"Notify Key",
	"content"=>"Service Message",
	"remarks"=>"Service Message",
	"url"=>"http://bjzyy.missfood.net",
	"agentid"=>"11",
	"msgtype"=>"text",
	"thumb_media_id"=>"",
	"picUrl"=>"http://i.meizitu.net/2016/12/07b01.jpg"
);

$input = file_get_contents("php://input"); //接收POST数据
$obj=json_decode($input);
//var_dump(json_decode($input)); 
//$msgInfo['agentid']= $obj->agentid;
$msgInfo['msgtype']= $obj->msgtype;
//$msgInfo['thumb_media_id']= $obj->thumb_media_id;
//$msgInfo['color']= $obj->color;
//$msgInfo['fromusername']= $obj->fromusername;
//$msgInfo['userid']= $obj->userid;
//$msgInfo['event']= $obj->event;
//$msgInfo['eventkey']= $obj->eventkey;
//$msgInfo['content']= $obj->content;
//$msgInfo['remarks']= $obj->remarks;
//$msgInfo['url']= $obj->url;

if($msgInfo['msgtype']=="mpnews"){
	sendMpnews();
	
}

if($msgInfo['msgtype']=="image"){
	
	$msgInfo['picUrl']=$obj->picUrl;

	$arr = explode('/',$msgInfo['picUrl']); 
	$filename= $arr[count($arr)-1]; 
	dlfile($msgInfo['picUrl'],"pic/".$filename);
	$data=array(
		'media'=>'@pic/'.$filename
	);
	$type="image";
	$res=$weObj->uploadMedia($data, $type);
	//$jsonencode = json_decode($res);
	$media_id=$res['media_id'];
    var_dump( $media_id);
	
	sendImage($weObj,11,$media_id);
	
}

if($msgInfo['msgtype']=="text"){
	$msgInfo['content']=$obj->content;
	sendTextCard($weObj,11,$msgInfo['content']);
	
}

function dlfile($file_url, $save_to){
	$content = file_get_contents($file_url);
	file_put_contents($save_to, $content);
}

function sendImage($weObj,$agentid,$media_id){
	$wxmsg=array(
				  "touser" => "@all",
				  "toparty" => "PartyID1|PartyID2 ",
				  "totag" => "TagID1|TagID2 ",
				  "safe"=>"0",			//是否为保密消息，对于news无效
				  "agentid" =>$agentid,	//应用id
				  "msgtype" =>"image",  //根据信息类型，选择下面对应的信息结构体
				  "image" => array(
						  "media_id" => $media_id
				   )
				  			  
				);	
	$res=$weObj->sendMessage($wxmsg);
	var_dump($res);
}

function sendTextMsg($weObj,$agentid,$content){
	$wxmsg=array(
				  "touser" => "@all",
				  "toparty" => "PartyID1|PartyID2 ",
				  "totag" => "TagID1|TagID2 ",
				  "safe"=>"0",			//是否为保密消息，对于news无效
				  "agentid" =>$agentid,	//应用id
				  "msgtype" =>"text",  //根据信息类型，选择下面对应的信息结构体
				  "text" => array(
						  "content" =>$content
				   )
				  			  
				);	
	$res=$weObj->sendMessage($wxmsg);
	var_dump($res);

}


function sendTextCard($weObj,$agentid,$content){
	$wxmsg=array(
				  "touser" => "@all",
				  "toparty" => "PartyID1|PartyID2 ",
				  "totag" => "TagID1|TagID2 ",
				  "safe"=>"0",			//是否为保密消息，对于news无效
				  "agentid" =>$agentid,	//应用id
				  "msgtype" =>"textcard",  //根据信息类型，选择下面对应的信息结构体
				  "textcard" => array(
				          "title"=>"你好",
						  "description" =>$content,
						  "url"=>"URL",
						  "btntxt"=>"test"
				   )
				  			  
				);	
	$res=$weObj->sendMessage($wxmsg);
	var_dump($res);

}

function sendMpnews($weObj){
	$sd=array(
	          "touser" => "@all",
	          "toparty" => "PartyID1|PartyID2 ",
	          "totag" => "TagID1|TagID2 ",
	          "safe"=>"0",			//是否为保密消息，对于news无效
	          "agentid" =>$agentid,	//应用id
	          "msgtype" => "mpnews",  //根据信息类型，选择下面对应的信息结构体
			  "mpnews" => array(
								  "articles" => array(    //articles  图文消息，一个图文消息支持1到10个图文
									  array(
										  "title" => "Title",             //图文消息的标题
										  "thumb_media_id" =>$thumb_media_id,       //图文消息缩略图的media_id
										  "author" => "Author",           //图文消息的作者(可空)
										  "content_source_url" => "URL",  //图文消息点击“阅读原文”之后的页面链接(可空)
										  "content" =>$content,          //图文消息的内容，支持html标签
										  "digest" => "Digest description",   //图文消息的描述
										  "show_cover_pic" => "0"         //是否显示封面，1为显示，0为不显示(可空)
									  ),
								  )
						  )		  
			  
	        );
	$res=$weObj->sendMessage($sd);
	var_dump($res);

	
}





?>
