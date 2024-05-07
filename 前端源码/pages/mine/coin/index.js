var n = getApp(), o = (n.require("utils/util.js"), n.require("utils/api.js")), e = 0, t = -1, a = {
    Logs: []
};

Page({
    data: {
        lastPage: !1,
        model: a
    },
    onLoad: function(n) {
        this.loadData();
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.data.lastPage || this.loadData({
            pageIndex: ++e
        });
    },
    onShareAppMessage: function() {},
    loadData: function(i) {
        var s = this;
        o.userCoin(i, function(o) {
            if (i && i.pageIndex) for (var u in o) a.Logs.push(o[u]); else a = o;
            o.Logs.length < n.pageSize && (t = a.Logs.length), s.setData({
                lastPage: t >= 0 && t <= (e + 1) * n.pageSize,
                model: a
            });
        });
    },
    onRuleTap: function() {
        wx.navigateTo({
            url: "../../common/content/web?url=" + encodeURIComponent("/wap/news/detail/article_id/" + a.RuleArticleId)
        });
    }
});