<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>购买管理</title>
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
		<div class="layui-inline">
        	<form method="get" action="<?php echo U('Pay/alist');?>" id="sub" >
           	<div class="layui-input-inline">
		    	<input  name="uid" placeholder="请输入用户id" class="layui-input search_input" value="<?php echo ($getall['uid']?$getall['uid']:''); ?>" type="text">
		    </div>
            <div class="layui-input-inline">
             			<label class="layui-form-label">支付</label>
		    		<select name="pay"  id='pay' lay-filter="pay"   class="layui-select "  >
                     <option value="1"  <?php if(isset($getall['pay'])&&($getall['pay']==1)): ?>selected<?php endif; ?>>已支付</option>
                            <option value="2" <?php if(isset($getall['pay'])&&($getall['pay']==2)): ?>selected<?php endif; ?>>未支付</option>
                           
						    </select>
                           
		    </div>
		    <div class="layui-input-inline">
             			
                            <div class="layui-inline">
                              <label class="layui-form-label">日期</label>
                              <div class="layui-input-block">
                                <input name="date" id="date1" autocomplete="off" value="<?php echo ($getall['date']?$getall['date']:''); ?>" class="layui-input" type="date">
                              </div>
                            </div>
		    </div>
            <div class="layui-input-inline">
             			
                            <div class="layui-inline">
                              <label class="layui-form-label">---</label>
                              <div class="layui-input-block">
                                <input name="date1" id="date2" autocomplete="off"  value="<?php echo ($getall['date1']?$getall['date1']:''); ?>" class="layui-input" type="date">
                              </div>
                            </div>
		    </div>
            
		    <a class="layui-btn search_btn" onclick="suball()">查询</a>
            </form>
		</div>
		<div class="layui-input-inline">
             		<font style="font-size:20px; font-weight:bold">支付总金额：<?php echo ($paymoney); ?>元</font>
		    </div>
	</blockquote>
            
		    <div class="layui-tab-content larry-personal-body clearfix mylog-info-box">
		         <!-- 操作日志 -->
                <div class="layui-tab-item layui-field-box layui-show">
                     <table class="layui-table table-hover" lay-even="" lay-skin="nob">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" id="selected-all"></th>
                                  <th>ID</th>
                                  <th>用户id</th>
                                   <th>用户名</th>
                                   <th>订单号</th>
                                   <th>套餐名称</th>
                                   <th>套餐金额</th>
                                   <th>付款金额</th>
                                   <th>套餐到期时间</th>
                                  <th>是否支付</th>
                                  <th>添加时间</th>
                                  
                               
                              </tr>
                          </thead>
                          <tbody>
                           <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                            <td><input type="checkbox"></td>
                                            <td><?php echo ($data['ID']); ?></td>
                                            <td><?php echo ($data['SH_USERID']); ?></td>
                                          <td><?php echo ($data['SH_USERNAME']); ?></td>
                                           <td><?php echo ($data['SH_ORDER']); ?></td>
                                          <td><?php echo ($data['SH_NAME']); ?></td>
                                          
                                            <td><?php echo ($data['SH_PRICE']); ?></td>
                                            <td><?php echo ($data['SH_PAY']); ?></td>
                                            <td><?php echo ($data['SH_END']); ?></td>
                                            <td><?php echo ($ispay[$data['IS_PAY']]); ?></td>
                                             <td><?php echo ($data['GMT_CREATE']); ?></td>
                                              
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

function delall(id,pg){
	if(confirm("确认要删除吗？")){
		$.ajax({
				 type: "get",
				 url: "<?php echo U('comment/del');?>",
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
function suball(){
	document.getElementById('sub').submit();
	
}
</script>
</body>
</html>