<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"F:\programs\yuetanglvju\public/../application/index\view\index\index.html";i:1534133321;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>悦棠旅居</title>
    <style type="text/css">
    	html,body{
    		padding: 0;
    		margin: 0;
    		width: 100%;
    		height: 100%;
    	}
    	.content{
    		margin: 0;
    		padding: 0;
    		width: 100%;
    		height: 100%;
    	}
    	.content img{
    		width: 100%;
    		display: block;
    		margin-bottom: 30px;
    	}
    	.content_buttom{
    		width: 60%;
    		margin:0 20%;
    	}
    	.content_buttom img{
    		width: 100%;
    		margin: 10px 0;
    	}
    </style>
</head>
<body>
	<div class="content">
		<img src="/static/images/back.png"/>
		<div class="content_buttom">
			<img id="ios" src="/static/images/ios.png"/>
			<img id="and" src="/static/images/ad.png"/>
		</div>
	</div>
</body>
<script type="text/javascript">
	document.getElementById('ios').onclick = function(){
		window.location.href= 'https://itunes.apple.com/cn/app/%E7%99%BE%E5%BA%A6%E5%9C%B0%E5%9B%BE-%E6%99%BA%E8%83%BD%E5%AF%BC%E8%88%AA%E8%B7%AF%E7%BA%BF%E8%A7%84%E5%88%92%E6%97%85%E6%B8%B8%E5%87%BA%E8%A1%8C/id452186370?mt=8';
	};
	document.getElementById('and').onclick = function(){
		window.location.href= ''
	};
</script>
</html>