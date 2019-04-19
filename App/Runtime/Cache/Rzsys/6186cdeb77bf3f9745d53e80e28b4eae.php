<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>提现管理</title>
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
        	<form method="get" action="<?php echo U('Pay/tixian');?>" id="sub" >
           	<div class="layui-input-inline">
		    	<input  name="uid" placeholder="请输入用户id" class="layui-input search_input" type="text">
		    </div>
		    <div class="layui-input-inline">
             			<label class="layui-form-label">类型</label>
		    		<select name="type"  id='type' lay-filter="type"  required class="layui-select "  >
                       <option value="" >全部</option>
						<option value="0" >未审核</option>
                        <option value="1" >通过</option>
                        <option value="2" >拒绝</option>
                          
					</select>
                            <div class="layui-inline">
                              <label class="layui-form-label">购买日期</label>
                              <div class="layui-input-block">
                                <input name="date" id="date1" autocomplete="off" class="layui-input" type="date">
                              </div>
                            </div>
		    </div>
		    <a class="layui-btn search_btn" onclick="suball()">查询</a>
            </form>
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
                                   <th>提现金额</th>
                                   <th>卡号</th>
                                   <th>持卡人姓名</th>
                                   <th>持卡人手机号</th>
                                   <th>开户行</th>
                                   <th>开户行地址</th>
                                  <th>状态</th>
                                  <th>添加时间</th>
                                  <th width="300px;">操作</th>
                               
                              </tr>
                          </thead>
                          <tbody>
                           <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                            <td><input type="checkbox"></td>
                                            <td><?php echo ($data['ID']); ?></td>
                                            <td><?php echo ($data['PF_USER_ID']); ?></td>
                                          <td><?php echo ($data['SH_USERNAME']); ?></td>
                                          <td><?php echo ($data['PF_PRICE']); ?></td>
                                          
                                            <td><?php echo ($data['BC_NUMBER']); ?></td>
                                            <td><?php echo ($data['BC_NAME']); ?></td>
                                            <td><?php echo ($data['BC_TELNO']); ?></td>
                                             <td><?php echo ($data['BC_OPEN_BANK']); ?></td>
                                             <td><?php echo ($data['BC_OPEN_BANK_ADDRESS']); ?></td>
                                             <td><?php echo ($state[$data['PF_STATUS']]); ?></td>
                                              <td><?php echo ($data['GMT_CREATE']); ?></td>
                 		<td><a href="javascript:void(0)"  class="layui-btn layui-btn-small" onclick="return fenghao(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>,1)"  ><i class="iconfont icon-shanchu1"></i> 通过</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)"  class="layui-btn layui-btn-small" onclick="return fenghao(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>,2)"  ><i class="iconfont icon-shanchu1"></i> 不通过</a></td>

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
function fenghao(id,pg,state){
	var desc='';
	if(state==2){
		layer.prompt({title: '输入原因', formType: 2}, function(text, index){
			 layer.close(index);
		  	
			$.ajax({
				 type: "get",
				 url: "<?php echo U('Pay/tongguo');?>",
				 data: {id:id,pg:pg,state:state,desc:text},
				 dataType: "json",
				 async:true,
				 success: function(data){
					layer.msg(data.msg);
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					
				 }
							
			 });
		 
		});
	}else{
		$.ajax({
				 type: "get",
				  url: "<?php echo U('Pay/tongguo');?>",
				 data: {id:id,pg:pg,state:state,desc:desc},
				 dataType: "json",
				 async:true,
				 success: function(data){
					layer.msg(data.msg);
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					
				 }
							
			 });
	}
	
		
	
}
function suball(){
	document.getElementById('sub').submit();
	
}
</script>
</body>
</html>