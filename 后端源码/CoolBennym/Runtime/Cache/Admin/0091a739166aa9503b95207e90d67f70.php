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
<!DOCTYPE html>
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
<style>
.seleK{height: 30px;}
.seleK label {margin-bottom: 10px;}
.main-tu-js .tudou-js-nr .tu-select-nr .left a, .piliangcaozuo, .main-sc .tudou-js-nr .tu-select-nr .right span{line-height:30px; height: 30px;}
.seleK .text{height: 28px; width:120px !important;}
.seleK .sumit{height:30px;padding-left:27px;background-position:6px center;padding-right:10px;line-height:30px}
.main-sc .tudou-js-nr .tu-select-nr .right .select{height: 30px; line-height: 30px;width: 80px;margin-right:5px}
.tu-inpt-text{width: 120px; height: 28px; line-height: 30px;}
.inpt-button-tudou{height: 30px;line-height:30px;background:rgb(0, 153, 204); width:70px;text-align: center;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">外卖频道</li>
        <li class="li2">餐饮频道</li>
        <li class="li2 li3">商家列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>如果未添加商家，那么不能发布菜单，这里可以按照条件搜索外卖商家，已可以查询商家的营业状态等</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="border-top: none; margin-top: 0px;">
            <div class="left">
                <?php echo BA('ele/create','','添加');?>  
            </div>
            <div class="right">
                <form method="post" action="<?php echo U('ele/index');?>">
                    <div class="seleHidden" id="seleHidden">
                    
                        <label>
                           <span>是否打烊：</span>
                           <select class="select w120" name="is_open">
                               <option <?php if(($is_open) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                               <option <?php if(($is_open) == "0"): ?>selected="selected"<?php endif; ?>  value="0">已打烊</option>
                               <option <?php if(($is_open) == "1"): ?>selected="selected"<?php endif; ?>  value="1">营业中</option>
                           </select>
                        </label>
                    
                        
                         <label>
                            <span>选择城市：</span>
                             <select name="city_id" id="city_id"  class="select w100"></select>
                            <select name="area_id" id="area_id"  class="select w100"></select>
                        </label>
                       <script src="<?php echo U('app/datas/onecity',array('name'=>'cityareas'));?>"></script> 
                       <script>
								var city_id = <?php echo (int)$city_id;?>;
                    			var area_id = <?php echo (int)$area_id;?>;
                                $(document).ready(function () {
                                    var city_str = ' <option value="0">请选择...</option>';
                                    for (a in cityareas.city) {
                                        if (city_id == cityareas.city[a].city_id) {
                                            city_str += '<option selected="selected" value="' + cityareas.city[a].city_id + '">' + cityareas.city[a].name + '</option>';
                                        } else {
                                            city_str += '<option value="' + cityareas.city[a].city_id + '">' + cityareas.city[a].name + '</option>';
                                        }
                                    }
                                    $("#city_id").html(city_str);
                                    $("#city_id").change(function () {
                                        if ($("#city_id").val() > 0) {
                                            city_id = $("#city_id").val();
     										   $.ajax({
													  type: 'POST',
													  url: "<?php echo U('app/datas/twoarea');?>",
													  data:{cid:city_id},
													  dataType: 'json',
													  success: function(result){
                                                         var area_str = ' <option value="0">请选择...</option>';
                                                        for (a in result) {
                                                          area_str += '<option value="' + result[a].area_id + '">' + result[a].area_name + '</option>';                                                        }
                                                       $("#area_id").html(area_str);
													  }
												});
                                            $("#area_id").html(area_str);
                                        } else {
                                            $("#area_id").html('<option value="0">请选择...</option>');
                                        }
                                    });
									
                              		if (city_id > 0) {
                                        var area_str = ' <option value="0">请选择...</option>';
										$.ajax({
										  type: 'POST',
										  url: "<?php echo U('app/datas/twoarea');?>",
										  data:{cid:city_id},
										  dataType: 'json',
										  success: function(result){
                                             for (a in result) {
                                                if (area_id == result[a].area_id) {
                                                    area_str += '<option selected="selected" value="' + result[a].area_id + '">' + result[a].area_name + '</option>';
                                                } else {
                                                    area_str += '<option value="' + result[a].area_id + '">' + result[a].area_name + '</option>';
                                                }
                                              }
                                             $("#area_id").html(area_str);
											}
										});
                                    }
                                   
                                });
                        </script> 
                        
                        
                        <span>关键字：</span>   
                        <input type="text" name="keyword" value="<?php echo (($keyword)?($keyword):''); ?>" class="tu-inpt-text" />
                        <input type="submit" class="inpt-button-tudou" value="  搜索" />

                    </div>
                </form>
            </div>
        </div>
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="shop_id" /></td>
                        <td class="w50">ID</td>
                        <td>学校ID</td>
                        <td>分类ID</td>
                        <td>商家名称</td>
                        <td>商家区域</td>
                        <td>营业执照</td>
                        <td>卫生许可证</td>
                        <td>是否打烊</td>
                        <td>营业时间段</td>
                        <td>配送费</td>
                        <td>满多钱免配送费</td>
                        <td>结算费率</td>
                        <td>起送价</td>
                        <td>卖出数</td>
                        <td>月卖出数</td>
                        <td>配送编码</td>
                        <td>排序</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_shop_id" type="checkbox" name="shop_id[]" value="<?php echo ($var["shop_id"]); ?>" /></td>
                            <td><?php echo ($var["shop_id"]); ?></td>
                            <td><?php echo ($var["school_id"]); ?></td>
                            <td><?php echo ($var["cate"]); ?></td>
                            <td><?php echo ($var["shop_name"]); ?></td>
                            <td>
                            <?php echo ($var['city']['name']); ?>  <?php echo ($var['area']['area_name']); ?>  <?php echo ($var['business']['business_name']); ?>
                            </td>
                            <td><a href="<?php echo config_weixin_img($var['pic1']);?>" class="tooltip"><img src="<?php echo config_img($var['pic1']);?>" class="w80"></a></td>
                            <td><a href="<?php echo config_weixin_img($var['pic2']);?>" class="tooltip"><img src="<?php echo config_img($var['pic2']);?>" class="w80"></a></td>
                            <td>
                            <?php if(($var["is_open"]) == "1"): ?>营业<?php else: ?>打烊<?php endif; ?>
                            </td>
                            <td><?php echo ($var["busihour"]); ?></td>
                            
                            <td>&yen;<?php echo round($var['logistics']/100,2);?>元</td>
                            <td style="color:#F00">&yen;<?php echo round($var['logistics_full']/100,2);?>元</td>
                            <td style="color:#00F"><?php echo ($var['rate']); ?>‰</td>
                            <td>&yen;<?php echo round($var['since_money']/100,2);?>元</td>
                            <td><?php echo ($var["sold_num"]); ?></td>
                            <td><?php echo ($var["month_num"]); ?></td>
                            <td><?php echo ($var["shop"]["is_ele_pei"]); ?></td>
                            <td><?php echo ($var["orderby"]); ?></td>
                            <td>
                            	<?php if(($var["audit"]) == "0"): echo BA('ele/audit',array("shop_id"=>$var["shop_id"]),'审核','act','tu-dou-btn'); endif; ?>
                                
                                
                                <?php if(($var["shop"]["is_ele_pei"]) == "0"): echo BA('ele/is_ele_pei',array("shop_id"=>$var["shop_id"],'p'=>$p),'平台配送员抢单','','tu-dou-btn-small');?>
                                <?php else: ?>
                                    <?php echo BA('ele/is_ele_pei',array("shop_id"=>$var["shop_id"],'p'=>$p),'商家自主配送模式','','tu-dou-btn-small-waring'); endif; ?> 
                            
                            
                            	<?php echo BA('eleproduct/index',array("shop_id"=>$var["shop_id"]),'菜品','','tu-dou-btn');?>
                                
                                <?php echo BA('ele/edit',array("shop_id"=>$var["shop_id"]),'编辑','','tu-dou-btn');?>
                                <?php echo BA('ele/delete',array("shop_id"=>$var["shop_id"]),'删除','act','tu-dou-btn');?>
                                <?php if(($var["is_open"]) == "0"): echo BA('ele/opened',array("shop_id"=>$var["shop_id"],'type'=>'open'),'开启店铺','act','tu-dou-btn');?>
                                <?php else: ?>
                                	<?php echo BA('ele/opened',array("shop_id"=>$var["shop_id"],'type'=>'closed'),'关闭店铺','act','tu-dou-btn'); endif; ?>
                            	<a target="_blank" class="tu-dou-btn" href="<?php echo U('shop/login',array('shop_id'=>$var['shop_id']));?>">管理商家</a>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                    <?php echo BA('ele/delete','','批量删除','list','a2');?>
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>