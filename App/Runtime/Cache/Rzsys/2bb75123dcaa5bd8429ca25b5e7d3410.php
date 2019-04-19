<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>视频添加</title>
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
			<span>添加视频</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5"  method="post" id="mainform" >
            <input type="hidden" name="id" id="upid" value="<?php echo ($data['ID']?$data['ID']:''); ?>">
            <input type="hidden" name="pg" id="pg" value="<?php echo ($data['pg']?$data['pg']:'1'); ?>">
           	    <div class="layui-form-item">
					<label class="layui-form-label">视频名称</label>
					<div class="layui-input-block">  
						<input type="text" name="name" id="name" required class="layui-input " lay-verify="name" value="<?php echo ($data['MV_NAME']?$data['MV_NAME']:''); ?>"  >
					</div>
				</div>
                 <div class="layui-form-item" >
					<label class="layui-form-label">国内视频地址</label>
					<div class="layui-input-block" style="width:800px!important;">  
						<input type="text" name="d_href" id="d_href" required class="layui-input "  lay-verify="d_href" value="<?php echo ($data['MV_HURL']?$data['MV_HURL']:''); ?>"  >
					</div>
				</div>
                 <div class="layui-form-item" >
					<label class="layui-form-label">国外视频地址</label>
					<div class="layui-input-block" style="width:800px!important;">  
						<input type="text" name="w_href" id="w_href" required class="layui-input "  lay-verify="w_href" value="<?php echo ($data['MV_WURL']?$data['MV_WURL']:''); ?>"  >
					</div>
				</div>
                <div class="layui-form-item" >
					<label class="layui-form-label">国内视频试看地址</label>
					<div class="layui-input-block" style="width:800px!important;">  
						<input type="text" name="sd_href" id="sd_href" required class="layui-input " lay-verify="sd_href"   value="<?php echo ($data['MV_SHURL']?$data['MV_SHURL']:''); ?>"  >
					</div>
				</div>
                 <div class="layui-form-item" >
					<label class="layui-form-label">国外视频试看地址</label>
					<div class="layui-input-block" style="width:800px!important;">  
						<input type="text" name="sw_href" id="sw_href" required class="layui-input "   lay-verify="sw_href"value="<?php echo ($data['MV_WHURL']?$data['MV_WHURL']:''); ?>"  >
					</div>
				</div>
                 <div class="layui-form-item" >
					<label class="layui-form-label">播放时长</label>
					<div class="layui-input-block">  
						<input type="text" name="mvtime" id="mvtime" required class="layui-input " value="<?php echo ($data['MV_TIME']?$data['MV_TIME']:''); ?>"  >
					</div>
				</div>
                 <div class="layui-form-item" >
					<label class="layui-form-label">视频类别</label>
					<div class="layui-input-block">  
						<input type="text" name="lei" id="lei" required class="layui-input " placeholder="科幻" value="<?php echo ($data['MV_CATEGORY']?$data['MV_CATEGORY']:''); ?>"  >
					</div>
				</div>
                <div class="layui-form-item">
				    <label class="layui-form-label">视频种类</label>
				    <div class="layui-input-block">
                     <?php echo ($str); ?>
				    </div>
				  </div>
                <div class="layui-form-item">
					<label class="layui-form-label">视频类型</label>
                     <input type="hidden" id="typeall" value="<?php echo ($data['MV_TYPE']?$data['MV_TYPE']:'1'); ?>">
					<div class="layui-input-block">
						<input type="radio" name="type" value="1" lay-filter="type"  title="免费" <?php if(isset($data['MV_TYPE'])&&$data['MV_TYPE']==1 ): ?>checked="checked"<?php endif; ?> <?php if(!isset($data['MV_TYPE'])): ?>checked="checked"<?php endif; ?>><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><span>免费</span></div>
						<input type="radio" name="type" value="2" lay-filter="type"  title="会员" <?php if(isset($data['MV_TYPE'])&&$data['MV_TYPE']==2): ?>checked="checked"<?php endif; ?>><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><span>会员</span></div>
					</div>
				</div>
  
                  <div class="layui-form-item">
                        <label class="layui-form-label">视频封面图地址(527*375)</label>
                        <div class="layui-input-block">
                           <input type="text" name="spic" id="spic" lay-verify="required" class="layui-input " value="<?php echo ($data['MV_PHOTO_URL']?$data['MV_PHOTO_URL']:''); ?>"  >
					
                        </div>
                       
                    </div>
                 
                <div class="layui-form-item">
					<label class="layui-form-label">播放次数</label>
					<div class="layui-input-block">  
						<input type="text" name="click" id="click" required class="layui-input " value="<?php echo ($data['MV_PLAY_COUNT']?$data['MV_PLAY_COUNT']:'0'); ?>"  >
					</div>
				</div>
                <div class="layui-form-item layui-form-text">
					<label class="layui-form-label">简介</label>
					<div class="layui-input-block">
						<textarea placeholder="" value="" name="desc" id="desc" lay-verify="required" class="layui-textarea"><?php echo ($data['MV_CONTENT']?$data['MV_CONTENT']:''); ?></textarea>
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

<script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>

<script type="text/javascript">

	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则 
		  var type=$('#typeall').val(); 
		  form.on('radio(type)', function (data) { 
		 		if(data.value==2){
					type=2;
				}else{
					type=1;
				}
		   });
		   
		  form.verify({  
				name: function(value){  
				  if(value==''){  
					return '视频名称不能为空';  
				  }  
				},d_href:function(value){  
				  if(value==''){  
					return '视频地址不能为空';  
				  }  
				},w_href:function(value){  
				  if(value==''){  
					return '视频地址不能为空';  
				  }  
				},sd_href:function(value){  
				  if(value==''){  
					return '视频地址不能为空';  
				  }  
				},sw_href:function(value){  
				  if(value==''){  
					return '视频地址不能为空';  
				  }  
				}
				
		  });
		   //监听提交  
		  form.on('submit(demo1)', function(data){ 
		  	var arr ='';
            $("input:checkbox[name='like']:checked").each(function(i){
                arr+= $(this).val()+',';
            });
		  //alert(111);
			 //$("#demo1").attr('disabled',true);
			// $("#demo1").css("background", "#ccc");
			$.ajax({
				 type: "post",
				 url: "<?php echo U('video/doadd');?>",
				 data: {name:$('#name').val(),mvtime:$('#mvtime').val(),classid:arr,d_href:$('#d_href').val(),sd_href:$('#sd_href').val(),lei:$('#lei').val(),type:type,pic:$('#spic').val(),'w_href':$('#w_href').val(),'sw_href':$('#sw_href').val(),click:$('#click').val(),desc:$('#desc').val(),id:$('#upid').val(),pg:$('#pg').val()},
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
		 
		 
	});
</script>
</body>
</html>