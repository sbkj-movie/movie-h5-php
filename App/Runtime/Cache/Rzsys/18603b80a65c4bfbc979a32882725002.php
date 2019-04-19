<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>关于我们</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/bootstrap/css/bootstrap.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/global.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/css/personal.css" media="all">
    <script type="text/javascript" charset="utf-8" src="/Public/admin/ueidtor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/admin/ueidtor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/Public/admin/ueidtor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>
     <link rel="stylesheet" type="text/css" href="/Public/admin/js/webuploader.css">

<!--引入JS-->
<script type="text/javascript" src="/Public/admin/js/webuploader.js"></script>
<style>
.uploader-list div{ width:100px; height:100px; float:left}
</style>
</head>
<body>
<section class="layui-larry-box">
	<div class="larry-personal">
		<header class="larry-personal-tit">
			<span>关于我们</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5"  method="post" id="mainform" >
           
                 <div class="layui-form-item"  >
					<label class="layui-form-label">内容</label>
					<div class="layui-input-block"> 
                    	<textarea id="neirong" style="display:none"><?php echo ($data['CONTENT']?$data['CONTENT']:''); ?></textarea>
						<script id="editor" type="text/plain" style="width:1024px;height:500px;"></script>
						
					</div>
				</div>
             	
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" id="demo1" lay-filter="demo1">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>

<script type="text/javascript">

 UE.getEditor('editor').addListener("ready", function () {
        　　// editor准备好之后才可以使用
		 var content =$('#neirong').val();
		
        　　UE.getEditor('editor').setContent(content);

        });
	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则  
	
  
		  //监听提交  
		   //监听提交  
		  form.on('submit(demo1)', function(data){ 
		  	
		 
			$.ajax({
				 type: "post",
				 url: "<?php echo U('shezhi/doadd');?>",
				 data: {content:UE.getEditor('editor').getContent(),type:'about'},
				 dataType: "json",
				 async:false,
				 success: function(data){
					
					layer.msg(data.msg);
					if(data.error!=1){
						setTimeout(function() {
							//window.location =data.href;
						}, 1200);
					}
						
					
				 }
							
			 });
				 return false;  
			
		  }); 
	});
</script>

</body>
</html>