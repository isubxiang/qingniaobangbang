function e(e, a, t) {
    return a in e ? Object.defineProperty(e, a, {
        value: t,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[a] = t, e;
}

var a = require("../../../../utils/service/api-service.js"), 
t = require("../../../../utils/onfire.js"), 
r = getApp(),
h = (r.require("utils/util.js"), r.require("utils/api.js"));

Page({
    data: {
        examList: [],
        pageIndex: 1,
        loadProcess: !1,
        loadingComplete: !1,
        scrollHeight: 600,
        reqInit: !1
    },
    onLoad: function() {
        this.setData({
            scrollHeight: r.getSystemHeight()
        }), this.loadExamErr(!1);
        var e = this;
        t.on("errmove", function(a) {
            var t = a.paperId;
            e.reCalPaper(t);
        }), t.on("errmoveall", function(a) {
            var t = a.paperId;
            e.reCalPaperAll(t);
        });
    },
    onUnload: function() {
        t.un("errmove"), t.un("errmoveall");
    },
    reCalPaper: function(a) {
        for (var t = this.data.examList, r = t.length, i = 0; i < r; i++) if (t[i].id == a) {
            i;
            var o = t[i].errCnt;
            if (o >= 2) {
                o -= 1;
                var s = "examList[" + i + "].errCnt";
                this.setData(e({}, s, o));
            } else {
                var n = "examList[" + i + "].show";
                this.setData(e({}, n, !1));
            }
            break;
        }
    },
    reCalPaperAll: function(a) {
        for (var t = this.data.examList, r = t.length, i = 0; i < r; i++) if (t[i].id == a) {
            var o = "examList[" + i + "].show";
            this.setData(e({}, o, !1));
            break;
        }
    },
    loadExamErr: function(e) {
        var t = this;
        t.setData({
            loadProcess: !0
        });
        var i = {
            pageIndex: this.data.pageIndex,
            pageSize: r.globalData.pageSize
        };
        a.examErr(i, function(a) {
            var r = a.rows;
            if (e) {
                if (r.length >= 1) {
                    var i = t.data.scoreList.concat(r);
                    t.setData({
                        examList: i,
                        loadProcess: !1,
                        reqInit: !0
                    });
                }
            } else t.setData({
                examList: r,
                loadProcess: !1,
                reqInit: !0
            });
            0 == a.pages || t.data.pageIndex == a.pages ? t.setData({
                loadingComplete: !0,
                loadProcess: !1,
                reqInit: !0
            }) : t.data.loadingComplete && t.setData({
                loadingComplete: !1
            });
        }, function(e) {});
    },
    scrollLower: function() {
        var e = this;
        e.data.loadProcess || e.data.loadingComplete || (e.setData({
            pageIndex: e.data.pageIndex + 1
        }), e.loadExamErr(!0));
    },
    chooseItem: function(e) {
        var a = "../topic/index?paper=" + e.currentTarget.dataset.id + "&err=1";
        wx.navigateTo({
            url: a
        });
    }
});