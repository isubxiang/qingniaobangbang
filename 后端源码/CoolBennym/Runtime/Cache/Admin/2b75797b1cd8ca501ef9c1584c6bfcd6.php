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
        <li class="li1">运营</li>
        <li class="li2"> 广告管理</li>
        <li class="li2 li3"> 广告列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>广告删除是软删除,图片广告需要上传图片，代码广告可以不用上传图片，文字广告不需要填写图片和代码两项！</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
             <?php echo BA('ad/create',array('site_id'=>$site_id),'添加广告');?>
             <a href="<?php echo U('ad/index',array('site_id'=>$site_id));?>">刷新本页</a>
            </div>
            <div class="right">

                    <div class="seleHidden" id="seleHidden">
                    
                    <div class="seleK">
                    	<label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label>
                             
                             
                        <span>搜索(广告名称)</span>
                        <input type="text"  class="tu-inpt-text" name="keyword" value="<?php echo ($keyword); ?>"  />
                        <input type="submit" value=" 搜索"  class="inpt-button-tudou" />
                        </div>
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
               <tr>
                <td><input type="checkbox" class="checkAll" rel="ad_id" /></td>
                <td>ID</td>
                <td>学校ID</td>
                <td>所属广告位</td>
                <td>所属城市</td>
                <td>广告名称</td>
                <td>图片</td>
                <td>购买用户</td>
                <td>购买用户ID</td>
                <td>链接地址</td>
                <td>点击次数</td>
                <td>剩余积分</td>
                <td>开始时间</td>
                <td>结束时间</td>
                <td>是否小程序</td>
                <td>排序</td>
                <td>操作</th></tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                    <td><input class="child_ad_id" type="checkbox" name="ad_id[]" value="<?php echo ($var["ad_id"]); ?>" /> </td>
                    <td><?php echo ($var["ad_id"]); ?></td>
                    <td><?php echo ($var["school_id"]); ?></td>
                    <td><?php echo ($sites[$var['site_id']]['site_name']); ?></td>
                    <td><?php echo (($citys[$var['city_id']]['name'])?($citys[$var['city_id']]['name']):'通用'); ?></td>
                    <td><?php echo ($var["title"]); ?></td>
                    <td><a href="<?php echo config_weixin_img($var['photo']);?>" class="tooltip"><img src="<?php echo config_img($var['photo']);?>" class="w120"></a></td>
                    <td><?php echo ($var["nickname"]); ?></td>
                    <td><?php echo ($var["user_id"]); ?></td>
                    <td><?php echo ($var["link_url"]); if(($var["is_target"]) == "1"): ?>(新窗口)<?php endif; ?></td>
                    <td>点击数量：<?php echo ($var["click"]); ?>
                        <?php if(!empty($var['reset_time'])): ?><br/>更新时间：<?php echo (date("Y-m-d H:i:s",$var["reset_time"])); endif; ?>
                    </td>
                    <td><?php echo ($var["prestore_integral"]); ?></td>
                    <td><?php echo ($var["bg_date"]); ?></td>
                    <td><?php echo ($var["end_date"]); ?></td>
                    <td><?php if(($var["is_wxapp"]) == "1"): ?>是<?php else: ?>否<?php endif; ?></td>
                    <td><?php echo ($var["orderby"]); ?></td>
                    <td>
                        <?php echo BA('ad/edit',array("ad_id"=>$var["ad_id"]),'编辑','','tu-dou-btn');?>
                        <?php echo BA('ad/delete',array("ad_id"=>$var["ad_id"]),'删除','act','tu-dou-btn');?>
                        <?php echo BA('ad/reset',array("ad_id"=>$var["ad_id"],'site_id'=>$var["site_id"]),'重置点击量','act','tu-dou-btn');?>
                    </td>
                </tr><?php endforeach; endif; ?>
                    <tr>
                        <td colspan="20">
                            <?php echo BA('ad/delete','','批量删除','list',' piliangcaozuo');?>
                        </td>
                    </tr>
                </table>
                <?php echo ($page); ?>

            </div>


        </form>
    </div>
</div>

  		</div>
	</body>
</html>