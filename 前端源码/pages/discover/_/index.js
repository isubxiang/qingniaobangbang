var n = getApp().require("utils/onfire.js");

Page({
    data: {},
    onLoad: function(n) {
        wx.setNavigationBarTitle({
            title: "发现"
        });
    },
    onReady: function() {},
    onShow: function() {
        n.fire("hideIndexDialog");
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onTabItemTap: function() {}
});