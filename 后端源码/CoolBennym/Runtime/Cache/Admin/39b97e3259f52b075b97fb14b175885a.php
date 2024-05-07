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
        <li class="li2">学校管理</li>
        <li class="li2 li3">学校列表</li>
    </ul>
</div>

<div class="main-tu-js">
    <p class="attention"><span>注意：</span>这里是学列表</p>
    <div class="tudou-js-nr">
    
    
        <div class="tu-select-nr" style="margin: 10px 20px;">
            <div class="left">
                <?php echo BA('running/schoolPublish',array('school_id'=>$detail['school_id'],'date'=>$date),'添加学校');?>
                <?php echo BA('running/school',array('school_id'=>$detail['school_id']),'刷新本页','','',600,360);?>
            </div>
            <form method="post" action="<?php echo U('running/school',array('school_id'=>$detail['school_id']));?>">
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
                        <td class="w50"><input type="checkbox" class="checkAll" rel="school_id" /></td>
                        <td class="w50">ID</td>
                        <td>会员ID</td>
                        <td>地区</td>
                        <td>名称</td>
                        <td>地区</td>
                        <td>运费说明</td>
                        <td>最少运费</td>
                        <td>开启提现功能</td>
                        
                        <td>会员单笔最低提现多少钱</td>
                        <td>会员单笔最多提现多少钱</td>
                        <td>会员单笔提现手续费</td>
                        <td>会员每日最多提现申请次数</td>
                        
                        <td>商家单笔最低提现多少钱</td>
                        <td>商家单笔最多提现多少钱</td>
                        <td>商家单笔提现手续费</td>
                        <td>商家每日最多提现申请次数</td>
                        
                        <td>平台佣金比例</td>
                        <td>站长会员ID</td>
                        <td>站长佣金比例</td>
                        
                        <td>排序</td>
                        <td>创建时间</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_school_id" type="checkbox" name="school_id[]" value="<?php echo ($var["school_id"]); ?>"/></td>
                            <td><?php echo ($var["school_id"]); ?></td>
                            <td><?php echo ($var["user_id"]); ?></td>
                            <td><?php echo ($var['city']['name']); ?>-<?php echo ($var['area']['area_name']); ?>-<?php echo ($var['business']['business_name']); ?></td>
                            <td><?php echo ($var["Name"]); ?></td>
                            <td><?php echo ($var["Region"]); ?></td>
                            <td><?php echo ($var["FreightMoneyCaption"]); ?></td>
                            <td><?php echo ($var["MinFreightMoney"]); ?></td>
                            
                            <td><?php if(($var["is_cash"]) == "0"): ?>关闭<?php else: ?>开启<?php endif; ?></td>
                            <td><?php echo ($var["user"]); ?></td>
                            <td><?php echo ($var["user_big"]); ?></td>
                            <td><?php echo ($var["user_cash_commission"]); ?>%</td>
                            <td><?php echo ($var["user_cash_second"]); ?></td>
                            <td><?php echo ($var["shop"]); ?></td>
                            <td><?php echo ($var["shop_big"]); ?></td>
                            <td><?php echo ($var["shop_cash_commission"]); ?>%</td>
                            <td><?php echo ($var["shop_cash_second"]); ?></td>
                            
                            
                            <td style="color:#F00"><?php echo ($var["admin_yongjin_rate"]); ?>%</td>
                            <td style="color:#F00"><?php echo ($var["user_id"]); ?></td>
                            <td style="color:#F00"><?php echo ($var["city_yongjin_rate"]); ?>%</td>
                            
                            <td><?php echo ($var["orderby"]); ?></td>
                            <td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                            <td>
                            	<?php echo BA('usermoneylogs/index',array("school_id"=>$var["school_id"],"user_id"=>$var["user_id"],"type"=>5),'站长分成记录','','tu-dou-btn');?>
                                
                            	<?php echo BA('running/shop',array("school_id"=>$var["school_id"]),'外送商家列表','','tu-dou-btn');?>
                                <?php echo BA('running/address',array("school_id"=>$var["school_id"]),'常用地址','','tu-dou-btn');?>
                                <?php echo BA('running/finance',array("school_id"=>$var["school_id"]),'财务','','tu-dou-btn');?>
                                <?php echo BA('running/schoolPublish',array("school_id"=>$var["school_id"]),'编辑','','tu-dou-btn');?>
                                <?php echo BA('running/schoolDelete',array("school_id"=>$var["school_id"]),'删除','act','tu-dou-btn');?>
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