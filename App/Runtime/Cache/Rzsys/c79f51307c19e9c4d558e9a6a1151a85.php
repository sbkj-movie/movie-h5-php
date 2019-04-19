<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>客服添加</title>
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

    <script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>


</head>
<body>
<section class="layui-larry-box">
	<div class="larry-personal">
		<header class="larry-personal-tit">
			<span>添加客服</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5"  method="post" id="mainform" >
            <input type="hidden" name="id" id="upid" value="<?php echo ($data['ID']?$data['ID']:''); ?>">
            <input type="hidden" name="pg" id="pg" value="<?php echo ($data['pg']?$data['pg']:'1'); ?>">
           	    
                 <div class="layui-form-item">
                      <label class="layui-form-label">客服方式</label>
                      <div class="layui-input-block">  
                      
                        <select name="modules" id="type" lay-verify="required" lay-search="">
                        
                      <option value="微信"  <?php if(isset($data['CS_TYPE'])&&($data['CS_TYPE']=='微信')): ?>selected<?php endif; ?>>微信</option>
					<option value="QQ"   <?php if(isset($data['CS_TYPE'])&&($data['CS_TYPE']=='QQ')): ?>selected<?php endif; ?>>QQ</option>
                    <option value="电话"   <?php if(isset($data['CS_TYPE'])&&($data['CS_TYPE']=='电话')): ?>selected<?php endif; ?>>电话</option>
                        </select>
                      </div>
                    </div>
                   
                <div class="layui-form-item">
					<label class="layui-form-label">客服号码</label>
					<div class="layui-input-block">  
						<input type="text" name="hao" id="hao" required class="layui-input " value="<?php echo ($data['CS_NUMBER']?$data['CS_NUMBER']:''); ?>"  >
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

	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则 
		
		   //监听提交  
		  form.on('submit(demo1)', function(data){ 
		  	
		  
			 //$("#demo1").attr('disabled',true);
			// $("#demo1").css("background", "#ccc");
			$.ajax({
				 type: "post",
				 url: "<?php echo U('shezhi/kefudoadd');?>",
				 data: {type:$('#type').val(),hao:$('#hao').val(),id:$('#upid').val(),pg:$('#pg').val()},
				 dataType: "json",
				 async:false,
				 success: function(data){
					 
					layer.msg(data.msg);
					if(data.error!=1){
						setTimeout(function() {
							window.location =data.href;
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