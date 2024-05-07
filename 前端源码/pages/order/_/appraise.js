var e = getApp(), a = e.require("utils/onfire.js"), n = (e.require("utils/util.js"), 
e.require("utils/api.js")), r = null, i = null, t = -1, s = [], o = {};

Page({
    data: {
        score: 0,
        fromFinish: !1,
        appraiseLabels: i,
        appraiseLabelsGroup: t,
        appraiseLabelsIndex: s
    },
    onLoad: function(e) {
        o = e, i = [ [ "准时送达", "货品完好", "送货快", "颜值高", "礼貌热情", "任务保质保量" ], [ "马马乎乎吧", "包装破损了，但并无大碍" ], [ "配送超慢", "态度贼差", "联系沟通困难", "送达不通知", "未按要求完成" ] ], 
        this.setData({
            fromFinish: !!e.finish,
            appraiseLabels: i
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onStarTap: function(e) {
        var a = e.currentTarget.dataset.index;
        this.setData({
            score: parseInt(a) + 1
        });
    },
    onAppraiseContentChanged: function(e) {
        r = e.detail.value;
    },
    onAppraiseLabelTap: function(e) {
        var a = this, n = e.currentTarget.dataset.group, r = e.currentTarget.dataset.index;
        if (t < 0) t = n, s.push(r); else if (t == n) {
            var i = s.indexOf(r);
            i < 0 ? s.push(r) : (s.splice(i, 1), 0 == s.length && (t = -1));
        }
        a.setData({
            appraiseLabelsIndex: s,
            appraiseLabelsGroup: t
        });
    },
    onAppraiseSubmit: function(e) {
        n.userSaveFormId({
            formId: e.detail.formId
        });
        var u = this, p = [];
        if (t >= 0 && s.length) for (var f in s) {
            var l = s[f];
            p.push(i[t][l]);
        }
        n.orderComment({
            id: o.id,
            score: u.data.score,
            content: r || "",
            labels: p.join(",")
        }, function() {
            a.fire("orderReresh"), a.fire("refreshHomeOrders"), wx.navigateBack({
                delta: -1
            });
        });
    }
});