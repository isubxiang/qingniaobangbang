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
        <li class="li1">商家</li>
        <li class="li2">商家管理</li>
        <li class="li2 li3">商家列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>后台添加商家</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('shop/create','','添加申请');?>
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('shop/index');?>">
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
                            <span>分类：</span>
                            <select id="cate_id" name="cate_id" class="select w100">
                                <option value="0">请选择...</option>
                                <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $cate_id): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option>               
                                    <?php if(is_array($cates)): foreach($cates as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><option value="<?php echo ($var2["cate_id"]); ?>"  <?php if(($var2["cate_id"]) == $cate_id): ?>selected="selected"<?php endif; ?> >&nbsp;&nbsp;<?php echo ($var2["cate_name"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
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
                        
                        <label>
                           <span>有效期：</span>
                           <select class="select w120" name="endDate">
                              <option <?php if(($endDate) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择有效期</option>
                                	<?php if(is_array($end_dates)): foreach($end_dates as $key=>$item): ?><option <?php if(($endDate) == $key): ?>selected="selected"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; ?>
                           </select>
                       </label>
                            
                            
                        <label>
                            <span>关键字:</span>
                            <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" />
                            <input type="submit" value="   搜索"  class="inpt-button-tudou" />
                        </label>
                    </div>
                    
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

            
    <form  target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50"><input type="checkbox" class="checkAll" rel="shop_id" /></td>
                    <td class="w50">ID</td>
                    <td class="w50">学校ID</td>
                    <td>商家名称</td>
                    <td>商家形象图</td>
                    <td>商家LOGO</td>
                    <td>商家电话</td>
                    <td>管理者昵称</td>
                    <td>商家地区</td>
                    <td>商铺分类</td>
                    <td>商铺等级</td>
                    <td>配送编码</td>
                    <td>入住时间</td>
                    <td class="w300">操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_shop_id" type="checkbox" name="shop_id[]" value="<?php echo ($var["shop_id"]); ?>" /></td>
                        <td><?php echo ($var["shop_id"]); ?></td>
                        <td><?php echo ($var["school_id"]); ?></td>
                        <td><?php echo ($var["shop_name"]); ?></td>
                        <td><a href="<?php echo config_weixin_img($var['photo']);?>" class="tooltip"><img src="<?php echo config_img($var['photo']);?>" class="w80"></a></td>
                        <td><a href="<?php echo config_weixin_img($var['logo']);?>" class="tooltip"><img src="<?php echo config_img($var['logo']);?>" class="w80"></a></td>
                        <td><?php echo ($var["mobile"]); ?></td>
                        <td><?php echo ($users[$var['user_id']]['nickname']); ?>【<?php echo ($var["user_id"]); ?>】</td>
                        <td>
                        <?php echo ($var['city']['name']); ?>-<?php echo ($var['area']['area_name']); ?>-<?php echo ($var['business']['business_name']); ?>
                        </td>
                        <td><?php echo ($cates[$var['cate_id']]['cate_name']); ?></td>
                        <td><?php if(!empty($var['grade_id'])): echo ($grade[$var['grade_id']]['grade_name']); else: ?>暂无等级<?php endif; ?></td>    
                        <td><?php echo ($var["is_ele_pei"]); ?></td>
                        <td><?php echo (date('Y-m-d',$var["create_time"])); ?></td>
                        
                        <td class="w300">
                
                            
                            <?php if(($var["is_ele_pei"]) == "0"): echo BA('shop/is_ele_pei',array("shop_id"=>$var["shop_id"],'p'=>$p),'平台配送员抢单','','tu-dou-btn-small');?>
                            <?php else: ?>
                                <?php echo BA('shop/is_ele_pei',array("shop_id"=>$var["shop_id"],'p'=>$p),'商家自主配送模式','','tu-dou-btn-small-waring'); endif; ?> 
                                
                            
                            <?php if(($var["audit"]) == "0"): echo BA('shop/audit',array("shop_id"=>$var["shop_id"]),'审核','act','tu-dou-btn-small'); endif; ?> 
                            
                            
                            <?php echo BA('shop/edit',array("shop_id"=>$var["shop_id"],'p'=>$p),'编辑','','tu-dou-btn-small');?>
                            <?php echo BA('shop/delete',array("shop_id"=>$var["shop_id"],'p'=>$p),'删除','act','tu-dou-btn-small-waring');?>
                        </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
            <div class="left">
                <?php echo BA('shop/delete','','批量删除','list',' a2');?>
            </div>
        </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>