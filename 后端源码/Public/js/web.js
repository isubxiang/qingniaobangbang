
$.ajaxSetup({
    cache: false
});
var lock = 0;

function loading() {
    layer.msg('正在加载中...');
}

//显示加载
function showLoader(msg) {
	parent.layer.load(2);
}
//关闭加载
function hideLoader(){
    lock = 0;
    $("#loader").hide();
	parent.layer.closeAll('loading');
}

function showWindow(width,hight,url,title){
	layer.open({
	  type: 2,
	  title:title,
	  area:[width,hight],
	  fixed: false, 
	  maxmin: true,
	  content: url
	});
}
function LoginSuccess() {
    $(".tudialog").remove();
    success('登录成功，正在为您跳转', 3000, "loginCallback()");
}

function loginCallback() {
    $.get(TU_ROOT + "/index.php?m=passport&a=check&mt=" + Math.random(), function (data) {
        $(".topOne").find('.left').html(data);
    }, 'html');
	location.reload();
    return true;
}

	
//图片预览
$(function(){
   var x = 10;
   var y = 20;
   $(".tooltip").mouseover(function(e){ 
      var tooltip = "<div id='tooltip'><img src='"+ this.href +"' alt='土豆预览图' height='300'/>"+"<\/div>"; //创建 div 元素
      $("body").append(tooltip);  //把它追加到文档中             
      $("#tooltip").css({
         "top": (e.pageY+y) + "px",
         "left":  (e.pageX+x)  + "px"
       }).show("fast");    //设置x坐标和y坐标，并且显示
   }).mouseout(function(){  
        $("#tooltip").remove();  //移除 
   }).mousemove(function(e){
        $("#tooltip") .css({
            "top": (e.pageY+y) + "px",
            "left":  (e.pageX+x)  + "px"
       });
   });
})



function ajaxLogin() {
    lock = 0;
    layer.closeAll();
    var boxHtml = '<div class="tudialog"></div>';
    if ($(".tudialog").length == 0) {
        $("body").append(boxHtml);
        $(".tudialog").css('height', document.body.scrollHeight + 'px');
    }
    var url = TU_ROOT + '/index.php?g=home&m=passport&a=ajaxloging&t=' + Math.random();
    var width = document.body.clientWidth
    $.get(url, function (data) {

        $(".tudialog").html('<div class="tudialog_bg"></div>' + data);
        var left = (width - 350) / 2;
        var top = $(window).scrollTop() + 100;
        $(".loginPop").css({
            'left': left + 'px',
            'top': top + 'px'
        });

        $(".tudialog").show();
    }, 'html');

}

function success(msg, timeout, callback) {
    layer.msg(msg);
    setTimeout(function () {
        lock = 0;
        $(".tumsgbox").hide();
        eval(callback);
    }, timeout ? timeout : 3000);
}


function error(msg, timeout, callback) {
    layer.msg(msg);
    setTimeout(function () {
        lock = 0;
        eval(callback);
    }, timeout ? timeout : 3000);
}


function jumpUrl(url) {
    if (url) {
        location.href = url;
    } else {
        history.back(-1);
    }
}

function yzmCode() { //更换验证码
    $(".yzm_code").click();
}



//layer begin
function bmsg(msg, url, timeout, callback) { //信息,跳转地址,时间
    layer.msg(msg);
    if (url) {
        setTimeout(function () {
            window.location.href = url;
        }, timeout ? timeout : 3000);
    } else if (url === 0) {
        setTimeout(function () {
            location.reload(true);
        }, timeout ? timeout : 3000);
    } else {
        eval(callback);
    }

}

function bopen(msg, close, style) {
    layer.open({
        type: 1,
        skin: style, //样式类名
        closeBtn: close, //不显示关闭按钮
        shift: 2,
        shadeClose: true, //开启遮罩关闭
        content: msg
    });

}


//layer end

function dialog(title, content, width, height) {
    var dialogHtml = '<div class="dialogBox" title="' + title + '"></div>';
    if ($(".dialogBox").length == 0) {
        $("body").append(dialogHtml);
    }

    $(".dialogBox").attr('title', title);
    $(".dialogBox").html(content);
    $(".dialogBox").dialog({
        zIndex: 1000,
        width: width ? width : 300,
        height: height ? height : 200,
        modal: true
    });

}

var input_array = Array();
$(document).ready(function (e) {
    
    $(".tips").click(function () {
        var tipnr = $(this).attr('rel');
        layer.tips(tipnr, $(this), {
            tips: [4, '#1ca290'],
            time: 4000
        });
    })

    $(document).on('click', '.yzm_code', function () {
        $("#" + $(this).attr('rel')).attr('src', TU_ROOT + '/index.php?g=app&m=verify&a=index&mt=' + Math.random());
    });

    $(document).on("click", "a[mini='act']", function (e) {
        e.preventDefault();
        if (!lock) {
            //loading();
            $("#x-frame").attr('src', $(this).attr('href'));
        }
    });

    $(document).on('click', "a[mini='confirm']", function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (!lock) {
            layer.confirm("您确定要" + $(this).html() + "吗？", {area: '150px', btn: ['是的', '不'], shade: false}, function () {
                $("#x-frame").attr('src', url);
            })
        }
    });

    $(document).on("click", "a[mini='buy']", function (e) { //购买的算法
        e.preventDefault();
        if (!lock) {
            loading();
            var url = $(this).attr('href');
            if (url.indexOf('?') > 0) {
                url += '&num=' + $('#' + $(this).attr('rel')).val();
            } else {
                url += '?num=' + $('#' + $(this).attr('rel')).val();
            }
            $("#x-frame").attr('src', url);
        }
    });

    $(document).on('click', "a[mini='list']", function (e) {
        e.preventDefault();
        if (!lock) {
            if (confirm("您确定要" + $(this).html())) {
                loading();
                $(this).parents('form').attr('action', $(this).attr('href')).submit();
            }
        }
    });

    $(document).on("click", "a[mini='tuan']", function (e) { //购买的算法
        e.preventDefault();
        if (!lock) {
            lock = 1;
            var url = $(this).attr('href');
            if (url.indexOf('?') > 0) {
                url += '&num=' + $('#' + $(this).attr('rel')).val();
            } else {
                url += '?num=' + $('#' + $(this).attr('rel')).val();
            }
            layer.msg("操作成功，正在跳转中...");
            setTimeout(function () {
                location.href = url;
            }, 2000)

        }
    });

    $(document).on("click", "a[mini='load']", function (e) {
        e.preventDefault();
        if (!lock) {
            loading();
            var obj = $(this);
            $.get(obj.attr('href'), function (data) {
                if (data == 0) {
                    ajaxLogin();
                } else {
                    dialog(obj.text(), data, obj.attr('w'), obj.attr('h'));

                }
                lock = 0;
                ;
            }, 'html');

        }
    });


    //全选
    $(document).on("click", ".checkAll", function (e) {
        var child = $(this).attr('rel');
        $(".child_" + child).prop('checked', $(this).prop("checked"));
    });


    $(document).on("click",".tu_closed",function(){
         $('.tudialog').hide();
    })



    $('.jq_star_show').each(function () {
        var val = $(this).attr('rel');
        var str = '';
        var num = parseInt(val / 10);
        var num2 = 5 - num;
        for (i = 0; i < num; i++) {
            str += '<img src="' + TU_PUBLIC + '/images/star1.jpg"/>';
        }
        for (i = 0; i < num2; i++) {
            str += '<img src="' + TU_PUBLIC + '/images/star2.jpg"/>';
        }
        $(this).html(str);
    });

    $(".jq_opacity_img img").mouseover(function () {
        $(this).stop().animate({
            opacity: '0.5'
        }, 300);
    }).mouseout(function () {
        $(this).stop().animate({
            opacity: '1'
        }, 300);
    });


    $(".jq_back_top").click(function (e) {
        var rel = $(this).attr('rel');
        rel = rel == undefined ? 200 : rel;
        $("html,body").animate({
            scrollTop: 0
        }, rel);
    });



});


 //全选
$(document).on("click", ".checkAll", function (e) {
    var child = $(this).attr('rel');
    $(".child_" + child).prop('checked', $(this).prop("checked"));
});
	

