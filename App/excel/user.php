<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>导入视频</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<style type="text/css">
		.down{ height:40px; width:200px; margin-left:20px; margin-top:40px;}
		.down a{ text-decoration:none;  width:100px; height:35px; line-height:35px; text-align:center; background:#009688; color:#fff; display:block}
		.daoru{ margin-left:20px; height:100px; margin-top:30px;}
		.daoru div{ float:left}
		.inp input{ width:150px; background:#fff}
		.inp{ height:40px;}
		.inp{ line-height:40px;}
		.btn{width:100px; height:35px; line-height:35px; text-align:center; background:#009688; color:#fff; border:none }
	</style>
</head>
<body>
<div class="down"><a href="/excel/downuser.php">下载样表</a></div>
<div class="daoru">
	<form enctype="multipart/form-data" method="post" id="uploadForm" action="/excel/userimport.php" data-type='ajax'>
      <input type="hidden" name="dopost" value='1' />
     <div class="inp">
         <input type="file" name="excel" />
     </div>
     <div class="form-group col-sm-2">
         <input type="submit" class="btn" value="确认导入">
     </div>
</form>
</div>
</body>
</html>