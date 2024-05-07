var app = getApp();

Page({
    data: {
      yhqid:0,
      user_id:0
    },

    onLoad: function(o) {

        var i = this, a = wx.getStorageSync("users").id;
        console.log(a);
        if (a == '' || a == undefined) {
          app.getUserInfo(function (t) {
            console.log('登录核销优惠券==app.getUserInfo==');
            console.log(t),
              wx.setStorageSync("users",t),
              i.setData({
                userInfo: t,
                user_id:t.user_id
              });
          });
        }


        var userId = wx.getStorageSync("users").id;
        console.log('====userId====', userId);
  
        console.log('====ooo==', o);

        var t = decodeURIComponent(o.scene);
        var e = t;

        console.log('====onLoad-e====', e);
        var id = o.id;
        console.log('====onLoad-id====', id);

        this.setData({
            yhqid: e,
            id: id,
            hxsjid: userId
        })


    },


    hx: function() {
        var t = this.data.yhqid;
        var id = this.data.id;
        var userId = wx.getStorageSync("users").id;
        console.log('====userId====', userId);
  


         wx.showModal({
            title: "提示",
            content: "确定核销此优惠券吗？",
            success: function(o) {
                o.confirm ? (console.log("用户点击确定"), app.util.request({
                    url: "app/Running/HxCoupon",
                    cachetime: "0",
                    data: {
                        id: t>0?t:id,
                        user_id: userId,
                    },
                    success: function(o) {
                        console.log(o),
                         1 == o.data.code && (wx.showToast({
                            title: o.data.msg,
                            icon: "none",
                            duration: 1e3
                        }), setTimeout(function() {
                            wx.navigateBack({});
                        }, 1e3)), 0 == o.data.code && wx.showToast({
                            title: o.data.msg,
                            icon: "none",
                            duration: 1e3
                        }), "无核销权限" == o.data && (wx.showToast({
                            title: "无核销权限!",
                            icon: "none",
                            duration: 1e3
                        }), setTimeout(function() {
                            wx.navigateBack({});
                        }, 1e3)), "不能重复核销 " == o.data && (wx.showToast({
                            title: "不能重复核销 !",
                            icon: "none",
                            duration: 1e3
                        }), setTimeout(function() {
                            wx.navigateBack({});
                        }, 1e3));
                    }
                })) : o.cancel && (console.log("用户点击取消"), wx.reLaunch({
                    url: "pages/errand/_/index"
                }));
            }
        });
    },


    onReady: function() {},
    onShow: function() {},
    onHide: function() {
        wx.removeStorageSync("hxsjid");
    },
    onUnload: function() {
        wx.removeStorageSync("hxsjid");
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});