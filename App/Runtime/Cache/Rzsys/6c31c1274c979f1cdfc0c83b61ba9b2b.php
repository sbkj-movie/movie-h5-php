<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>管理员管理</title>
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
	<div class="larry-personal" >
	    <div class="layui-tab">
            <blockquote class="layui-elem-quote news_search" >
		
	</blockquote>
            
		    <div class="layui-tab-content larry-personal-body clearfix mylog-info-box">
		         <!-- 操作日志 -->
                <div class="layui-tab-item layui-field-box layui-show">
                     <table class="layui-table table-hover" lay-even="" lay-skin="nob">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" id="selected-all"></th>
                                  <th>ID</th>
                                  <th>账号</th>
                                  <th>权限</th>
                                   
                                  <th>添加时间</th>
                                
                                  <th>操作</th>
                              </tr>
                          </thead>
                          <tbody>
                           <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                            <td><input type="checkbox"></td>
                                            <td><?php echo ($data['ID']); ?></td>
                                            <td><?php echo ($data['AU_ACCOUNT']); ?></td>
                                            <td><?php echo ($data['AU_PERMISSION']); ?></td>
                                            <td><?php echo ($data['GMT_CREATE']); ?></td>
                                              
											<td>
                                            <?php if($adminuid!=$data['ID']): ?><a href="javascript:void(0)"  onclick="return delall(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>)" class="layui-btn layui-btn-small"><i class="iconfont icon-shanchu1"></i>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?>
                                           
                                            <a href="<?php echo U('power/add');?>?id=<?php echo ($data['ID']); ?>&pg=<?php echo ($pg); ?>" class="layui-btn layui-btn-small"><i class="iconfont icon-shanchu1"></i>编辑</a></td>
                                          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                              
                          </tbody>
                     </table>
                      <div class="larry-table-page clearfix">
                         
				          <div id="page2" class="page"><?php echo ($page); ?></div>
			         </div>
			    </div>
			     <!-- 登录日志 -->
			  
		    </div>
		</div>
	</div>
</section>
<script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>
<script type="text/javascript">
layui.use('layer', function(){
var layer = layui.layer;
}); 
function delall(id,pg,state){
	if(confirm("确认要删除吗？")){
		$.ajax({
				 type: "get",
				 url: "<?php echo U('power/del');?>",
				 data: {id:id,pg:pg},
				 dataType: "json",
				 async:false,
				 success: function(data){
					layer.msg(data.msg);
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					
				 }
							
			 });
	}else{
		return false;
	}
}
</script>
</body>
</html>