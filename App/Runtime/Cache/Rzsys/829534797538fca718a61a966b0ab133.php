<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>管理员添加</title>
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
			<span>添加管理员</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5"  method="post" >
             <input type="hidden" name="id" id="upid" value="<?php echo ($data['ID']?$data['ID']:''); ?>">
            <input type="hidden" name="pg" id="pg" value="<?php echo ($data['pg']?$data['pg']:'1'); ?>">
           	    <div class="layui-form-item">
					<label class="layui-form-label">账号</label>
					<div class="layui-input-block"> 
                    <input type="text" name="name" id="name" required value="<?php echo ($data['AU_ACCOUNT']?$data['AU_ACCOUNT']:''); ?>"  class="layui-input"   >
					</div>
				</div>
              	<div class="layui-form-item">
				    <label class="layui-form-label">复选框</label>
				    <div class="layui-input-block">
                    <?php if(!isset($data['AU_PERMISSION'])): ?><input type="checkbox" name="like" value="look" title="查看" >
                     <input type="checkbox" name="like" value="edit" title="编辑" >
                     <input type="checkbox" name="like" value="del" title="删除">
                   <?php else: ?>
                   		<?php if(in_array('look',$data['AU_PERMISSION'])): ?><input type="checkbox" name="like" value="look" title="查看" checked>
                      <?php else: ?>
                      <input type="checkbox" name="like" value="look" title="查看" ><?php endif; ?>
                       <?php if(in_array('edit',$data['AU_PERMISSION'])): ?><input type="checkbox" name="like" value="edit" title="编辑" checked >
                      <?php else: ?>
                       <input type="checkbox" name="like" value="edit" title="编辑" ><?php endif; ?>
                       <?php if(in_array('del',$data['AU_PERMISSION'])): ?><input type="checkbox" name="like" value="del" title="删除" checked>
                      <?php else: ?>
                       <input type="checkbox" name="like" value="del" title="删除"><?php endif; endif; ?>
                      
                       
                       
				      
				    </div>
				  </div>
                 <div class="layui-form-item">
					<label class="layui-form-label">密码</label>
					<div class="layui-input-block">  
						<input type="password" name="password" id="pwd" required lay-verify="password"  class="layui-input " value="" >
					</div>
				</div>
                 <div class="layui-form-item">
					<label class="layui-form-label">确认密码</label>
					<div class="layui-input-block">  
						<input type="password" name="querenpwd" required  lay-verify="querenpwd"  class="layui-input " value="" >
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
		 var upid=$('#upid').val();
		 if(upid==''){
			form.verify({  
				querenpwd: function(value){  
				  if(value==''){  
					return '密码不能为空';  
				  }  
				  if(value!=$('#pwd').val()){  
					return '确认密码与密码不一致';  
				  }  
				}
				
		  });
		}else{
			form.verify({  
				querenpwd: function(value){  
				  if(value!=$('#pwd').val()){  
					return '确认密码与密码不一致';  
				  }  
				}
				
		  });
		}
		  
		  
		  //监听提交  
		   form.on('submit(demo1)', function(data){  
		   var arr ='';
            $("input:checkbox[name='like']:checked").each(function(i){
                arr+= $(this).val()+',';
            });
			
			$.ajax({
				 type: "post",
				 url: "<?php echo U('power/doadd');?>",
				 data: {name:$('#name').val(),arr:arr,pwd:$('#pwd').val(),id:$('#upid').val(),pg:$('#pg').val(),},
				 dataType: "json",
				 async:false,
				 success: function(data){
					 layer.msg(data.msg);
					 if(data.code==0){
						
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