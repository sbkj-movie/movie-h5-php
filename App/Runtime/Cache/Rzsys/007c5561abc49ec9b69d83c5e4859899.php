<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>轮播图添加</title>
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
    
</head>
<body>
<section class="layui-larry-box">
	<div class="larry-personal">
		<header class="larry-personal-tit">
			<span>轮播图添加</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5" method="post" >
             <input type="hidden" name="id" id="upid" value="<?php echo ($data['ID']?$data['ID']:''); ?>">
            <input type="hidden" name="pg" id="pg" value="<?php echo ($data['pg']?$data['pg']:'1'); ?>">
           	    <div class="layui-form-item">
					<label class="layui-form-label">名称</label>
					<div class="layui-input-block">  
						<input type="text" name="name" id="name" required class="layui-input " value="<?php echo ($data['BN_NAME']?$data['BN_NAME']:''); ?>"  >
					</div>
				</div>
                
                 <div class="layui-form-item">
                        <label class="layui-form-label">轮播图地址(900*450)</label>
                        <div class="layui-input-block">
                           <input type="text" name="spic" id="spic" lay-verify="required" class="layui-input " value="<?php echo ($data['BN_URL']?$data['BN_URL']:''); ?>"  >
                        </div>
                       
                    </div>

				<div class="layui-form-item">
					<label class="layui-form-label">位置</label>
					<div class="layui-input-block">
						<select name="postion" id="postion" lay-verify="required" lay-search="">
							<option value="1" <?php if(isset($data['BN_POSTION'])&&($data['BN_POSTION']==1)): ?>selected<?php endif; ?>>精选</option>
							<option value="2" <?php if(isset($data['BN_POSTION'])&&($data['BN_POSTION']==2)): ?>selected<?php endif; ?>>视频</option>
							<option value="3" <?php if(isset($data['BN_POSTION'])&&($data['BN_POSTION']==3)): ?>selected<?php endif; ?>>影片</option>
							<option value="4" <?php if(isset($data['BN_POSTION'])&&($data['BN_POSTION']==4)): ?>selected<?php endif; ?>>漫画</option>
						</select>
					</div>
				</div>

				<div class="layui-form-item">
					<label class="layui-form-label">分类</label>
					<div class="layui-input-block">
						<select name="modules" id="classid" lay-verify="required" lay-search="">
							<?php if(is_array($data["class"])): $i = 0; $__LIST__ = $data["class"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><option value="<?php echo ($da["ID"]); ?>" <?php if(isset($data['BN_TYPE'])&&($data['BN_TYPE']==$da['ID'])): ?>selected<?php endif; ?>><?php echo ($da["CL_NAME"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							<option value="0" <?php if(isset($data['BN_TYPE'])&&($data['BN_TYPE']==0)): ?>selected<?php endif; ?>>自定义链接</option>
						</select>
					</div>
				</div>
                    
                <div class="layui-form-item">
					<label class="layui-form-label">视频/漫画id</label>
					<div class="layui-input-block">  
						<input type="text" name="typeid" id="typeid" required class="layui-input " value="<?php echo ($data['BN_TYPE_ID']?$data['BN_TYPE_ID']:''); ?>" >
					</div>
				</div>
            
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>
<script type="text/javascript">
	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则  
		 layui.upload({ 
		  	 elem: '#pic',
             url: "<?php echo U('login/upload');?>" ,//上传接口
			 data:{name:'pic'},
			  before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
				layer.load(); //上传loading
			  },
             success: function(res){
				layer.closeAll('loading'); //关闭loading
                var img='<img src="/'+res.img+'"  width="120px">';
				img+='<input type="hidden" name="spic" id="spic" value="'+res.img+'">';
				$('#pical').html(img);
            } 
         });
		 
		  var type=$('#typeall').val(); 
		  form.on('radio(type)', function (data) { 
		 		if(data.value==2){
					type=2;
				}else{
					type=1;
				}
		   });

		form.on('submit(demo1)', function (data) {

			$.ajax({
				type: "post",
				url: "<?php echo U('banner/doadd');?>",
				data: {
					name: $('#name').val(),
					spic: $('#spic').val(),
					postion: $('#postion').val(),
					type: $('#classid').val(),
					typeid: $('#typeid').val(),
					id: $('#upid').val(),
					pg: $('#pg').val(),
				},
				dataType: "json",
				async: false,
				success: function (data) {
					layer.msg(data.msg);
					setTimeout(function () {
						window.location = data.href;
					}, 1200);

				}

			});
			return false;
		});
	});
</script>
</body>
</html>