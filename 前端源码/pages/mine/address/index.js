var e = getApp(), t = (e.require("utils/util.js"), e.require("utils/api.js")), n = e.require("utils/onfire.js"), a = [];

Page({
    data: {
        selectMode: !1,
        model: a
    },
    onLoad: function(e) {
        var t = this;
        n.on("addressChange", function(n) {
            t.loadData(e);
        }), t.loadData(e);
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {
        n.un("addressChange");
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    loadData: function(e) {
        var n = this;
        n.setData({
            selectMode: !!e.selectMode
        }), t.userAddressList(function(e) {
            a.splice(0, a.length);
            for (var t in e) a.push(e[t]);
            n.setData({
                model: a
            });
        });
    },
    onShareAppMessage: function() {},
    onItemTap: function(e) {
        var t = e.currentTarget.dataset.id;
        this.data.selectMode || wx.navigateTo({
            url: "edit?id=" + t
        });
    }
});