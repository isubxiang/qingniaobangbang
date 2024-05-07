var t = getApp(), n = t.require("utils/enums.js"), s = t.require("utils/onfire.js");

Page({
    data: {
        model: {},
        status: 0,
        statusName: null
    },
    onLoad: function(s) {
        this.setData({
            model: t.globalData.userInfo,
            statusName: n.getName(n.StudentStatus, s.status || 0),
            status: s.status || 0
        });
    },
    onReady: function() {},
    onShow: function() {
        var t = this;
        s.on("studentAuthApplied", function() {
            t.setData({
                statusName: n.getName(n.StudentStatus, 1),
                status: 1
            });
        });
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});