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
        <li class="li1">跑腿系统</li>
        <li class="li2">配送员管理</li>
        <li class="li2 li3">配送员列表</li>
    </ul>
</div>

<div class="main-tu-js">
    <p class="attention"><span>注意：</span>这里是跑腿的配送员列表，审核状态在编辑里面</p>
    <div class="tudou-js-nr">
    
    
        <div class="tu-select-nr" style="margin: 10px 20px;">
            <div class="left">
                <?php echo BA('running/deliveryPublish',array('school_id'=>$detail['school_id'],'date'=>$date),'添加配送员');?>
                <?php echo BA('running/delivery',array('school_id'=>$detail['school_id']),'刷新本页','','',600,360);?>
            </div>
            <form method="post" action="<?php echo U('running/delivery',array('school_id'=>$detail['school_id']));?>">
                <div class="right">
                     <div class="seleK">
                         <label>
                            <span>开始时间</span>
                            <input type="text" name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>"  onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text w150" />
                        </label>
                        <label>
                            <span>结束时间</span>
                            <input type="text" name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text w150" />
                    	</label>
                        
                         <label>
                            <span>审核状态：</span>
                            <select class="select w120" name="audit">
                             <option <?php if(($audit) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                                <?php if(is_array($getDeliveryAudits)): foreach($getDeliveryAudits as $key=>$item): ?><option <?php if(($audit) == $key): ?>selected="selected"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; ?>
                            </select>
                        </label>
                       <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label> 
                        <label>
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                            <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                            <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                        </label>
                        <input type="text" name="keyword" placeholder=" 输入关键字"  value="<?php echo ($keyword); ?>" class="tu-inpt-text"/>
                        <input type="submit" value="  搜索"  class="inpt-button-tudou"/>
                    </div>
                </div>
           </form>
        </div>
 
 
 
        <form target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="delivery_id" /></td>
                        <td class="w50">ID</td>
                        <td>会员ID</td>
                        <td>学校ID</td>
                        <td>学生代码</td>
                        <td>实名</td>
                        <td>专业</td>
                        <td>身份证</td>
                        <td>收款二维码</td>
                        <td>男女</td>
                        <td>入学日期</td>
                        <td>院系</td>
                        <td>手机</td>
                        <td>认证图1</td>
                        <td style="color:#F00">审核状态</td>
                        <td>通知状态</td>
                        <td>绑定公众号状态</td>
                        <td>接单开始时间</td>	
                        <td>接单结束时间</td>
                        <td>创建时间</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_delivery_id" type="checkbox" name="delivery_id[]" value="<?php echo ($var["delivery_id"]); ?>"/></td>
                            <td><?php echo ($var["delivery_id"]); ?></td>
                            <td><?php echo ($var["user_id"]); ?></td>
                            <td><?php echo ($var["school_id"]); ?></td>
                            <td><?php echo ($var["StudentCode"]); ?></td>
                            <td><?php echo ($var["RealName"]); ?></td>
                            <td><?php echo ($var["Major"]); ?></td>
                            <td><?php echo ($var["IdCode"]); ?></td>
                            <td><?php echo ($var["Gender"]); ?></td>
                            <td><?php echo ($var["EnrollmentDate"]); ?></td>
                            <td><?php echo ($var["Department"]); ?></td>
                            <td><?php echo ($var["phoneNumber"]); ?></td>
                            <td><a href="<?php echo config_weixin_img($var['PicUrl0']);?>" class="tooltip"><img src="<?php echo config_img($var['PicUrl0']);?>" class="w80"></a></td>
                            <td><a href="<?php echo config_weixin_img($var['PicUrl1']);?>" class="tooltip"><img src="<?php echo config_img($var['PicUrl1']);?>" class="w80"></a></td>
                            <td><?php echo ($getDeliveryAudits[$var['audit']]); ?></td>
                            
                            <td>  
                            	<?php if(($var["notifyFlag"]) == "1"): ?>接受消息<?php endif; ?>
                                <?php if(($var["notifyFlag"]) == "0"): ?>不接受消息<?php endif; ?>
                            </td>
                            <td>  
                                <?php if(($var["bindFlag"]) == "1"): ?>已绑定<?php endif; ?>
                                <?php if(($var["bindFlag"]) == "0"): ?>未绑定<?php endif; ?>
                            </td>
                            <td><?php echo ($var["notifyFrom"]); ?></td>	
                            <td><?php echo ($var["notifyEnd"]); ?></td>
                        
                            <td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                            <td>
                                <?php echo BA('running/deliveryPublish',array("delivery_id"=>$var["delivery_id"]),'编辑','','tu-dou-btn');?>
                                <?php echo BA('running/finance',array("delivery_id"=>$var["delivery_id"]),'财务','','tu-dou-btn');?>
                                <?php echo BA('running/deliveryDelete',array("delivery_id"=>$var["delivery_id"]),'删除','act','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr">
                <div class="left">
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>