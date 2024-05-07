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
        <li class="li1">频道</li>
        <li class="li2">跑腿信息</li>
        <li class="li2 li3">跑腿管理</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>这里是新版支持多学校的分类</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('runningcate/create','','添加分类');?>  
                
                <a href="<?php echo U('runningcate/index',array('p'=>$p));?>">刷新</a>
                <a <?php if(($school_id) == ""): ?>class="on"<?php endif; ?> href="<?php echo U('runningcate/index',array('school_id'=>$key));?>">全部学校</a>
                <?php if(is_array($schools)): foreach($schools as $key=>$item): ?><a <?php if(($school_id) == $item['school_id']): ?>class="on"<?php endif; ?> href="<?php echo U('runningcate/index',array('school_id'=>$item['school_id']));?>"><?php echo ($item["Name"]); ?></a><?php endforeach; endif; ?>
                
            </div>
            <div class="right">
                <form method="post" action="<?php echo U('runningcate/index');?>">
                  <div class="seleHidden" id="seleHidden">
                        <div class="seleK">
                		     <label>
                                <span>频道选择：</span>
                                <select class="select w120" name="channel_id">
                                 <option <?php if(($channel_id) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择分类</option>
                                	<?php if(is_array($channel_ids)): foreach($channel_ids as $key=>$item): ?><option <?php if(($channel_id) == $key): ?>selected="selected"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; ?>
                                </select>
                            </label>
                            
                             <label>
                                    <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                    <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                    <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                                </label>
                             
                         <label>
                            <input type="text"  class="tu-inpt-text" name="keyword" value="<?php echo ($keyword); ?>"/>
                            <input type="submit" value="   搜索"  class="inpt-button-tudou" />
                          </label> 
                        
                    	</div>
        			</div>
                </form>
            </div>
        </div>
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="cate_id" /></td>
                        <td class="w50">跑腿ID</td>
                        <td>学校ID</td>
                        <td>学校名称</td>
                        <td>跑腿名称</td>
                        <td>标签</td>
                        <td class="w360">Url</td>
                        <td>最低运费</td>
                        <td>结算佣金</td>
                        <td>排序</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_cate_id" type="checkbox" name="cate_id[]" value="<?php echo ($var["cate_id"]); ?>" /></td>
                            <td><?php echo ($var["cate_id"]); ?></td>
                            <td><?php echo ($var["school_id"]); ?></td>
                            <td><?php echo ($var["school"]["Name"]); ?></td>
                            <td><?php echo ($var["cate_name"]); ?></td>
                            <td><?php echo ($var["Tag"]); ?></td>
                            <td class="w360"><?php echo ($var["Url"]); ?></td>
                            <td style="color:red"> &yen; <?php echo round($var['price']/100,2);?> 元</td>
                            <td style="color:red"><?php echo ($var['rate']); ?>%</td>
                            <td style="padding-left:10px;"><input name="orderby[<?php echo ($var["cate_id"]); ?>]" value="<?php echo ($var["orderby"]); ?>" type="text" class="remberinput w80" /></td>
                            <td>
                                <?php echo BA('runningcate/edit',array("cate_id"=>$var["cate_id"]),'编辑','','tu-dou-btn-small');?>
                                <?php echo BA('running/index',array("school_id"=>$var["school_id"]),'订单列表','','tu-dou-btn-small');?>
                                <?php echo BA('running/delivery',array("school_id"=>$var["school_id"]),'配送员列表','','tu-dou-btn-small');?>
                                <?php echo BA('runningcate/delete',array("cate_id"=>$var["cate_id"]),'删除','act','tu-dou-btn-small');?>
                                
                                <?php if(($var["is_show"]) == "1"): echo BA('runningcate/is_show',array("cate_id"=>$var["cate_id"],'p'=>$p),'影藏[开启显示]','act','tu-dou-btn-small');?>
                                <?php else: ?>
                                    <?php echo BA('runningcate/is_show',array("cate_id"=>$var["cate_id"],'p'=>$p),'显示[关闭显示]','act','tu-dou-btn-small-waring'); endif; ?>
                                
                             </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                    <?php echo BA('runningcate/delete','','批量删除','list','a2');?>
                    <?php echo BA('runningcate/update','','批量更新排序','list','a2');?>
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>