<?php
ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 
//支持的消息类型
//模板消息
//请求get post
//content 消息内容 消息发送通道(微信测试号) color 设置消息颜色
include "wechat.class.php";

$options = array(
	'token'=>'tokenaccesskey', //填写你设定的key
	'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey
	'appid'=>'wxebec204c87514eef', //填写高级调用功能的app id, 请在微信开发模式后台查询
	'appsecret'=>'17a5ce03cb22c9c1e3fdd45a25a8e128' //填写高级调用功能的密钥
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
	"url"=>"http://bjzyy.missfood.net"
);

if(is_array($_GET)&&count($_GET)>0){ 
	
	foreach ($_GET as $key => $value){
		if( isset($value) ){
			$msgInfo[$key]=$value;	
		}
	}
	//var_dump($msgInfo);
	sendTemplateMSG($weObj,$msgInfo);
}else{
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$input = file_get_contents("php://input"); //接收POST数据
		$obj=json_decode($input);
		//var_dump(json_decode($input)); 
		$msgInfo['color']= $obj->color;
		$msgInfo['fromusername']= $obj->fromusername;
		$msgInfo['userid']= $obj->userid;
		$msgInfo['event']= $obj->event;
		$msgInfo['eventkey']= $obj->eventkey;
		$msgInfo['content']= $obj->content;
		$msgInfo['remarks']= $obj->remarks;
		$msgInfo['url']= $obj->url;
		var_dump($msgInfo);
	    sendTemplateMSG($weObj,$msgInfo);

	}else{
		
		echo "参数错误";
	}

}



function sendTemplateMSG($weObj,$msgInfo){
	$data =array(
			"touser"=>"oErEOwKjPYd91ZfGwA0m9MtucnOI",
			"template_id"=>"2cNyXEgzNmKbl6QwUZYsIJWRCNirBf3FJLnPmU-mOIo",
			"url"=>$msgInfo['url'],
			"topcolor"=>"#FF0000",
			"data"=>array(
				    "fromusername"=>array(
						"value"=>$msgInfo['fromusername'],
						"color"=>"#".$msgInfo['color']
					),
					"userid"=>array(
						"value"=>$msgInfo['userid'],
						"color"=>"#".$msgInfo['color']
					),
					"event"=>array(
						"value"=>$msgInfo['event'],
						"color"=>"#".$msgInfo['color']
					),
					"eventkey"=>array(
						"value"=>$msgInfo['eventkey'],
						"color"=>"#".$msgInfo['color']
					),
					"content"=>array(
						"value"=>$msgInfo['content'],
						"color"=>"#".$msgInfo['color']
					),
					"remarks"=>array(
						"value"=>$msgInfo['remarks'],
						"color"=>"#".$msgInfo['color']
					),
				    "createtime"=>array(
						"value"=>date("Y-m-d H:i:s "),
						"color"=>"#".$msgInfo['color']
					)
				
				
			)
		);
		//var_dump($data);
		$re=$weObj->sendTemplateMessage($data);
		echo json_encode($re);
	
}



?>