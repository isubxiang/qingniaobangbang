var o = getApp();

Page({
    data: {
        src: null
    },
    onLoad: function(e) {
        e.title && wx.setNavigationBarTitle({
            title: decodeURIComponent(e.title)
        });
        var n = decodeURIComponent(e.url);
        n.indexOf("://") < 0 && (n = o.globalData.siteUrl + n), 

        console.log('==onLoad==');
        console.log(n);

        console.log(e);
        
        
        this.setData({
            src: n
        }), (e.headerForeColor || e.headerBgColor) && wx.setNavigationBarColor({
            frontColor: e.headerForeColor ? decodeURIComponent(e.headerForeColor) : "#ffffff",
            backgroundColor: e.headerBgColor ? decodeURIComponent(e.headerBgColor) : "#06c1ae",
            animation: {
                duration: 400,
                timingFunc: "easeIn"
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},

    //卸载页面赋值
    onUnload: function() {
        var that = this
        var pages = getCurrentPages();
        var currPage = pages[pages.length - 1];   //当前页面
        var prevPage = pages[pages.length - 2];  //上一个页面

        console.log(that.data);

        prevPage.setData({
          file: that.data.file,
          fileName:'已选择文件',
        });

        
    },


  
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onMessage: function(o) {
         "previewImage" == o.detail.cmd && wx.previewImage(o.detail.data);

         
          console.log(o.detail.data);

          this.setData({
            file: o.detail.data,
          });


          
    }



});