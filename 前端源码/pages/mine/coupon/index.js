var i = getApp(), n = (i.require("utils/util.js"), i.require("utils/api.js")), t = i.require("utils/onfire.js"), e = [];

Page({
    data: {
        invalid: 0,
        model: e
    },
    onLoad: function(i) {

      console.log('=onLoad=');
      console.log(i);

        this.setData({
            invalid: !!i.invalid
        }), i.invalid && wx.setNavigationBarTitle({
            title: "无效红包"
        }), this.loadData(i);
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},


    onUnload: function() {

      console.log('=onUnload=');
      console.log(e);
      
     
        var i = [];
        for (var n in e) e[n].isSelected && i.push(e[n]);

      console.log('=onUnload==========================push=');
      console.log(e);

        t.fire("CouponSelect", i), t.un("CouponLoad");
   
    },


    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},

    unique:function (arr) {
        var result = [], isRepeated;
        for(var i = 0, len = arr.length; i<len; i++) {
      isRepeated = false;
      for (var j = 0, len = result.length; j < len; j++) {
        if (arr[i] == result[j]) {
          isRepeated = true;
          break;
        }
      }
      if (!isRepeated) {
        result.push(arr[i]);
      }
    }
    return result;
    },


    loadData: function(i) {

        console.log('=loadData=');
        console.log(i);

        var o = this, 

        //执行A函数
        a = function(n) {

          console.log('=function(n)=');
          console.log(n);


          console.log('=e以前保留的e=');
          console.log(e.length);

          var length = e.length;

          //if (e.length > 0){
            //var e = o.unique(e);
         // }

          console.log('=unique后的e=');
          console.log(e);

          console.log('=iiiiiii=');
          console.log(i);

            for (var t in n)

              console.log(n[t].invalid);
              console.log(n[t].Id);

          i.money && (n[t].invalid = i.money < n[t].MoneyLimit, !n[t].invalid && i.ids && i.ids.split(",").indexOf(n[t].Id) >= 0 && (n[t].isSelected = !0)), 


            //e.push(n[t]);

            //e.push(n[t]);

            e = n;

            console.log('=o.setData赋值后的=');
            console.log(e);
       
         
            o.setData({
                model:n,
                e:n
            });
        };

        console.log('i.list');
        console.log(i.list);

        console.log('t.one');
        console.log(t.one);


        n.couponList({
          valid: !i.invalid
        }, function (i) {
          console.log('=执行1=');
          console.log(i);
          a(i);
        });

        /*
        i.list ? t.one("CouponLoad", function(i) {
            console.log('=执行2=');
            console.log(i);
            a(i);
        }) : n.couponList({
            valid: !i.invalid
        }, function(i) {
            console.log('=执行1=');
            console.log(i);
            a(i);
        });
        */
    },

    onItemTap: function(i) {
        var n = this, t = i.currentTarget.dataset.index;
        console.log(i); 
        console.log('=onItemTap=');
        console.log(t);
        console.log(e);
        console.log(e[t]);


        e[t].invalid || (e[t].isSelected = !e[t].isSelected, n.setData({
            model: e
        }));
    }
});