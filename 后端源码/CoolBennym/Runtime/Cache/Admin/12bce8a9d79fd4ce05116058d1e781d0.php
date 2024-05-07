<?php if (!defined('THINK_PATH')) exit();?>
<?php if($CONFIG['config']['map'] == 1): ?><script type="text/javascript" src="<?php echo ($CONFIG['config']['baidu_map_api']); ?>"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<div class="top">
    <div id="r-result">请输入:<input type="text" id="suggestId" size="20" value="合肥" style="width:150px;" /></div>
    <div class="right">    
        <div class="points">经度<input type='text' name='lng' id='lng' value=""/></div>
        <div class="points">纬度<input type='text' id='lat' name='lat' value=""/></div>
        <div class="queren" onclick="showInfo();">确认</div>
    </div>       
</div>
<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
<div id="allmap" style="width: 560px; height:500px;"></div>
<style>
.top{height:30px}
.top input{height:30px;line-height:30px}
.top #r-result{float:left}
.top .right{float:right}
.top .right .points{float:left;margin-right:10px;width:110px}
.top .right .points input{display:inline-block;width:75px}
.top .right .queren{float:right;margin-left:10px;width:80px;height:30px;background:red;color:#fff;text-align:center;line-height:30px;cursor:pointer}

</style>
<script type="text/javascript">
// 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point( "<?php echo ($lng); ?>" , "<?php echo ($lat); ?>" ), 15);
    var point = new BMap.Point( "<?php echo ($lng); ?>" , "<?php echo ($lat); ?>" );
    map.centerAndZoom(point, 15);
    var marker = new BMap.Marker(point); 
    map.clearOverlays();
    map.addOverlay(marker);            
    marker.setAnimation(BMAP_ANIMATION_BOUNCE);
    function showInfo(e){
        parent.selectCallBack('data_lng', 'data_lat',document.getElementById('lng').value, document.getElementById('lat').value);
    }
    function showPoint(e){
        document.getElementById('lat').value = e.point.lat;
        document.getElementById('lng').value = e.point.lng;
        var p = new BMap.Point(e.point.lng,e.point.lat);
        var mk = new BMap.Marker(p); 
        map.clearOverlays();
        map.addOverlay(mk); 
        mk.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
    }
    map.enableScrollWheelZoom(true);
    map.addControl(new BMap.NavigationControl()); //添加默认缩放平移控件
    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
    map.addEventListener("click", showPoint);
    function G(id) {
        return document.getElementById(id);
    }

    var ac = new BMap.Autocomplete(//建立一个自动完成的对象
            {"input": "suggestId"
                , "location": map
            });

    ac.addEventListener("onhighlight", function (e) {  //鼠标放在下拉列表上的事件
        var str = "";
        var _value = e.fromitem.value;
        var value = "";
        if (e.fromitem.index > -1) {
            value = _value.province + _value.city + _value.district + _value.street + _value.business;
        }
        str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

        value = "";
        if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province + _value.city + _value.district + _value.street + _value.business;
        }
        str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
        G("searchResultPanel").innerHTML = str;
    });

    var myValue;
    ac.addEventListener("onconfirm", function (e) { 
        var _value = e.item.value;
        myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
        G("searchResultPanel").innerHTML = "onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

        setPlace();
    });
    function setPlace() {
        map.clearOverlays(); 
        function myFun() {
            var pp = local.getResults().getPoi(0).point;   
            map.centerAndZoom(pp, 18);
            map.addOverlay(new BMap.Marker(pp));  
        }
        var local = new BMap.LocalSearch(map, {
            onSearchComplete: myFun
        });
        local.search(myValue);
    }
</script><?php endif; ?>

<?php if($CONFIG['config']['map'] == 2): ?><script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?php echo ($CONFIG['config']['google_map_api']); ?>&callback=sensor" mce_src="https://maps.google.com/maps/api/js?key=<?php echo ($CONFIG['config']['google_map_api']); ?>&callback=sensor"></script>
 
    <div id="map" style="width:100%; height: 600px; border: 1px solid black;"></div>
    <div>
         座　　標：
                 <input type="text" name="lng" id="lng" style="width: 80px;"  value="-1.7989378" />，
                 <input type="text" name="lat" id="lat" style="width: 80px;"  value="52.5809411" />
         地　　址：<br />
                <textarea name="address" id="address" cols="45" rows="9">222</textarea>
    </div>
<script language="javascript" type="text/javascript">
    var map;
    var marker;
    var infowindow;
    var geocoder;
    var markersArray = [];

    function initialize() {
        //设置中心点
        var latlng = new google.maps.LatLng("<?php echo ($lat); ?>" , "<?php echo ($lng); ?>");//这里后台设置
        var myOptions = {
            zoom: 13,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
       //map = new google.maps.Map(document.getElementByIdx_x("map"), myOptions);
	    map = new google.maps.Map(document.getElementById("map"), myOptions);
        geocoder = new google.maps.Geocoder();
		
        //监听点击地图事件
        google.maps.event.addListener(map, 'click', function (event) {
            placeMarker(event.latLng);
			//alert();
			parent.selectCallBack('data_lng', 'data_lat',document.getElementById('lng').value, document.getElementById('lat').value);
        });
    }

    function placeMarker(location) {
        clearOverlays(infowindow);//清除地图中的标记
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
        markersArray.push(marker);
        //根据经纬度获取地址
        if (geocoder) {
            geocoder.geocode({ 'location': location }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        attachSecretMessage(marker, results[0].geometry.location, results[0].formatted_address);
                    }
                } else {
                    alert("Geocoder failed due to: " + status);
                }
            });
        }
    }
    //在地图上显示经纬度地址
    function attachSecretMessage(marker, piont, address) {
        var message = "<b>座標:</b>" + piont.lat() + " , " + piont.lng() + "<br />" + "<b>地址:</b>" + address;
        var infowindow = new google.maps.InfoWindow(
            {
                content: message,
                size: new google.maps.Size(50, 50)
            });
        infowindow.open(map, marker);
        if (typeof (mapClick) == "function") mapClick(piont.lng(), piont.lat(), address);
    }
    //删除所有标记阵列中消除对它们的引用
    function clearOverlays(infowindow) {
        if (markersArray && markersArray.length > 0) {
            for (var i = 0; i < markersArray.length; i++) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }
        if (infowindow) {
            infowindow.close();
        }
    }
    function setiInit() {
        // 页面加载显示默认lng lat address---begin
        var lattxt = document.getElementById("lat").value;
        var lngtxt = document.getElementById("lng").value;
        var addresstxt = document.getElementById("address").value;
        if (lattxt != '' && lngtxt != '' && addresstxt != '') {
            var latlng = new google.maps.LatLng(lattxt, lngtxt);
            marker = new google.maps.Marker({
                position: latlng,
                map: map
            });
            markersArray.push(marker);
            attachSecretMessage(marker, latlng, addresstxt);
        }
    }
    function mapClick(lng, lat, address) {
        document.getElementById("lng").value = lng;
        document.getElementById("lat").value = lat;
        document.getElementById("address").value = address;
		
		
       // parent.selectCallBack('data_lng', 'data_lat',lng, lat);
    
	
	
    }
    initialize();
    window.onload = function () {
        setiInit();
    }
</script><?php endif; ?>