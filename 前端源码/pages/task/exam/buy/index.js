function a(a, e, t) {
    return e in a ? Object.defineProperty(a, e, {
        value: t,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : a[e] = t, a;
}

var e = require("../../../../utils/service/api-service.js"), 
t = require("../../../../utils/common-util.js"), 
i = getApp(),
h = (i.require("utils/util.js"), i.require("utils/api.js"));

Page({
    data: {
        examList: [],
        pageIndex: 1,
        loadProcess: !1,
        loadingComplete: !1,
        scrollHeight: 600,
        reqInit: !1
    },
    onLoad: function(a) {
        this.setData({
            scrollHeight: i.getSystemHeight()
        }), this.loadExamBuy(!1);
    },
    loadExamBuy: function(a) {
        var t = this;
        t.setData({
            loadProcess: !0
        });
        var s = {
            pageIndex: this.data.pageIndex,
            pageSize: i.globalData.pageSize
        };
        e.examBuy(s, function(e) {
            var i = e.rows;
            if (a) {
                if (i.length >= 1) {
                    var s = t.data.scoreList.concat(i);
                    t.setData({
                        examList: s,
                        loadProcess: !1,
                        reqInit: !0
                    });
                }
            } else t.setData({
                examList: i,
                loadProcess: !1,
                reqInit: !0
            });
            0 == e.pages || t.data.pageIndex == e.pages ? t.setData({
                loadingComplete: !0,
                loadProcess: !1,
                reqInit: !0
            }) : t.data.loadingComplete && t.setData({
                loadingComplete: !1
            });
        }, function(a) {});
    },
    scrollLower: function() {
        var a = this;
        a.data.loadProcess || a.data.loadingComplete || (a.setData({
            pageIndex: a.data.pageIndex + 1
        }), a.loadExamBuy(!0));
    },
    chooseExam: function(e) {
        var t = e.currentTarget.dataset.id, i = e.currentTarget.dataset.index, s = this.data.examList[i].expand;
        0 == this.data.examList[i].subList.length && this.loadPaperByExam(t, i);
        var r = "examList[" + i + "].expand";
        this.setData(a({}, r, !s));
    },
    loadPaperByExam: function(i, s) {
        var r = "examList[" + s + "].subList", o = this;
        e.examPapersByExam({
            examId: i
        }, function(e) {
            var t = e.result;
            o.setData(a({}, r, t));
        }, function(a) {
            t.dialog.alert({
                title: "",
                content: a.message,
                success: function(a) {
                    o.loadPaperByExam(i, s);
                }
            });
        });
    },
    chooseItem: function(a) {
        var e = a.currentTarget.dataset.id;
        if ("1" == a.currentTarget.dataset.type) {
            var t = "../topic/index?paper=" + e;
            wx.navigateTo({
                url: t
            });
        }
    },
    choosePaper: function(a) {
        var e = "../topic/index?paper=" + a.currentTarget.dataset.id;
        wx.navigateTo({
            url: e
        });
    }
});