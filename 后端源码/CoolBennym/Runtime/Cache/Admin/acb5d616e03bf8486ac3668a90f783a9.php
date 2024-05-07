<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title><?php echo ($CONFIG["site"]["sitename"]); ?>管理后台</title>
        <meta name="description" content="<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台"/>
        <meta name="keywords" content="<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台"/>
        <link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="__PUBLIC__/js/jquery-ui.css" rel="stylesheet" type="text/css"/>
        
        
        <script> 
			var TU_PUBLIC = '__PUBLIC__'; 
			var TU_ROOT = '__ROOT__'; 
        </script>
        <script src="__PUBLIC__/js/jquery.js"></script>
        <script src="__PUBLIC__/js/jquery-ui.min.js"></script>
        <script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
        <script src="/Public/js/layer/layer.js"></script>
        <script src="__PUBLIC__/js/admin.js"></script>
        <script src="__PUBLIC__/js/echarts-all-3.js"></script>
        
        <link rel="stylesheet" type="text/css" href="/static/default/webuploader/webuploader.css">
		<script src="/static/default/webuploader/webuploader.min.js"></script>
    </head>
    
    
    </head>
<style type="text/css">
#ie9-warning{ background:#F00; height:38px; line-height:38px; padding:10px;
position:absolute;top:0;left:0;font-size:12px;color:#fff;width:97%;text-align:left; z-index:9999999;}
#ie6-warning a {text-decoration:none; color:#fff !important;}
</style>

<!--[if lte IE 9]>
<div id="ie9-warning">您正在使用 Internet Explorer 9以下的版本，请用谷歌浏览器访问后台、部分浏览器可以开启极速模式访问！不懂点击这里！" target="_blank">查看为什么？</a>
</div>
<script type="text/javascript">
function position_fixed(el, eltop, elleft){  
       // check if this is IE6  
       if(!window.XMLHttpRequest)  
              window.onscroll = function(){  
                     el.style.top = (document.documentElement.scrollTop + eltop)+"px";  
                     el.style.left = (document.documentElement.scrollLeft + elleft)+"px";  
       }  
       else el.style.position = "fixed";  
}
       position_fixed(document.getElementById("ie9-warning"),0, 0);
</script>
<![endif]-->


    <body>
         <iframe id="x-frame" name="x-frame" style="display:none;"></iframe>
   <div class="main">
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">拼车</li>
        <li class="li2">拼车管理</li>
        <li class="li2 li3">拼车列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>当前版本拼车支持小程序哦，后台只有删除功能，暂时的，后期功能逐渐增加</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('pinche/index');?>">
                    <div class="seleHidden" id="seleHidden">
                   		<div class="seleK"> 
                        
                         <label>
                            <span>开始时间</span>
                            <input type="text" name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text" />
                        </label>
                        <label>
                            <span>结束时间</span>
                            <input type="text" name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text" />
                        </label>
                        
                         <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label> 
                    
                     	<label>
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>"/>
                                <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                            </label>
                    	<label>
                            <span>拼车分类：</span>
                            <select id="cate_id" name="cate_id" class="select w100">
                               <option value="0">请选择...</option>
                               <?php if(is_array($getPincheCate)): foreach($getPincheCate as $index=>$var): ?><option value="<?php echo ($index); ?>"  <?php if(($var["cate_id"]) == $cate_id): ?>selected="selected"<?php endif; ?> ><?php echo ($var); ?></option><?php endforeach; endif; ?>
                            </select>
                        </label>
                        <label>
                            <span>关键字:</span>
                            <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" />
                        </label>
                        
                        <input type="submit" value="   搜索"  class="inpt-button-tudou" />
                    </div> 
                </form>
                <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
    </div>
    <form  target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50"><input type="checkbox" class="checkAll" rel="pinche_id" /></td>
                    <td class="w50">ID</td>
                    <td>学校ID</td>
                    <td>拼车分类</td>
                    <td>出发时间</td>
                    <td>出发地点->目的地->途径地</td>
                    <td>拼车说明</td>
                    <td>拼车姓名</td>
                    <td>拼车电话</td>
                    <td style="color:red">支付金额</td>
                    <td>支付状态</td>
                    <td>发布时间</td>
                    <td class="w200">操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_pinche_id" type="checkbox" name="pinche_id[]" value="<?php echo ($var["pinche_id"]); ?>" /></td>
                        <td><?php echo ($var["pinche_id"]); ?></td>
                        <td><?php echo ($var["school_id"]); ?></td>
                        <td>
                            <?php if(empty($var['cate_id'])): ?>暂无分类
                            <?php elseif($var['cate_id'] == 1): ?>
                            车找人
                            <?php elseif($var['cate_id'] == 2): ?>
                            人找车
                            <?php elseif($var['cate_id'] == 3): ?>
                            车找货
                            <?php elseif($var['cate_id'] == 4): ?>
                            货找车<?php endif; ?>
                        </td>
                        <td><?php echo ($var["start_time"]); ?>-<?php echo ($var["start_time_more"]); ?></td>
                        <td><?php echo ($var["goplace"]); ?> -> <?php echo ($var["toplace"]); ?> -> <?php echo ($var["middleplace"]); ?></td>
                        <td>
                            <?php if($var['cate_id'] == 1): ?>可坐<?php echo ($var["num_1"]); ?>人
                            <?php elseif($var['cate_id'] == 2): ?>
                            需要<?php echo ($var["num_2"]); ?>位置
                            <?php elseif($var['cate_id'] == 3): ?>
                            可装<?php echo ($var["num_3"]); ?>吨
                            <?php elseif($var['cate_id'] == 4): ?>
                            需要<?php echo ($var["num_4"]); ?>吨<?php endif; ?>
                        </td>
                        <td><?php echo ($var["name"]); ?></td>
                        <td><?php echo ($var["mobile"]); ?></td>
                        <td style="color:red"><?php echo round($var['money']/100,2);?></td>
                        <td><?php if(($var["status"]) == "1"): ?>已支付<?php else: ?>未支付<?php endif; ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                        
                        <td class="w150">
                            <?php echo BA('pinche/delete',array("pinche_id"=>$var["pinche_id"]),'删除','act','tu-dou-btn');?>
                        </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
            <div class="left">
                <?php echo BA('pinche/delete','','批量删除','list',' a2');?>
            </div>
        </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>