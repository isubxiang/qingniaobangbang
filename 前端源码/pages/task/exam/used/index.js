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
s = require("../../../../utils/onfire.js"), 
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
        }), this.loadExamUsed(!1);
        var e = this;
        s.on("topicupload", function(a) {
            var t = a.paperId;
            e.reLoadPaperSta(t);
        });
    },
    onUnload: function() {
        s.un("topicupload");
    },
    reLoadPaperSta: function(t) {
        for (var s = this.data.examList, i = s.length, o = -1, r = this, n = 0; n < i; n++) s[n].paperId == t && (o = n);
        -1 != o && e.examPaperSta({
            paperId: t,
            showLoading: !1
        }, function(e) {
            if (0 == e.code) {
                var t = e.result, s = "examList[" + o + "]";
                r.setData(a({}, s, t));
            }
        });
    },
    loadExamUsed: function(a) {
        var t = this;
        t.setData({
            loadProcess: !0
        });
        var s = {
            pageIndex: this.data.pageIndex,
            pageSize: i.globalData.pageSize
        };
        e.examUsed(s, function(e) {
            var s = e.rows;
            if (a) {
                if (s.length >= 1) {
                    var i = t.data.scoreList.concat(s);
                    t.setData({
                        examList: i,
                        loadProcess: !1,
                        reqInit: !0
                    });
                }
            } else t.setData({
                examList: s,
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
        }), a.loadExamUsed(!0));
    },
    chooseExam: function(e) {
        var t = e.currentTarget.dataset.id, s = e.currentTarget.dataset.index, i = this.data.examList[s].expand;
        0 == this.data.examList[s].subList.length && this.loadPaperByExam(t, s);
        var o = "examList[" + s + "].expand";
        this.setData(a({}, o, !i));
    },
    loadPaperByExam: function(s, i) {
        var o = "examList[" + i + "].subList", r = this;
        e.examPapersByExam({
            examId: s
        }, function(e) {
            var t = e.result;
            r.setData(a({}, o, t));
        }, function(a) {
            t.dialog.alert({
                title: "",
                content: a.message,
                success: function(a) {
                    r.loadPaperByExam(s, i);
                }
            });
        });
    },
    chooseItem: function(a) {
        var e = "../topic/index?paper=" + a.currentTarget.dataset.id;
        wx.navigateTo({
            url: e
        });
    }
});