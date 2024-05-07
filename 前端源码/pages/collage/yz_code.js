var app = getApp();

Page({
    data: {},
    onLoad: function(t) {
        var e = this;
        app.setNavigationBarColor(e);
        var n = decodeURIComponent(t.scene);
        app.getUserInfo(function(o) {
            console.log(o), e.setData({
                userInfo: o,
                order_id: n,
                storeid: t.storeid
            });
        });
    },
    add_market: function(o) {
        var t = this, e = t.data.userInfo.id, n = t.data.storeid, a = t.data.order_id;
        app.util.request({
            url: "app/Running/GroupVerification",
            cachetime: "0",
            data: {
                order_id: a,
                user_id: e,
                storeid: n
            },
            success: function(o) {
                console.log(o), "核销成功" == o.data ? wx.showToast({
                    title: "核销成功"
                }) : wx.showToast({
                    title: "核销失败"
                }), setTimeout(function() {
                    wx.navigateBack({});
                }, 1e3);
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});