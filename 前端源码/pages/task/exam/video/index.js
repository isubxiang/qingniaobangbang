function e(e, a, t) {
    return a in e ? Object.defineProperty(e, a, {
        value: t,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[a] = t, e;
}

var a = require("../../../../utils/service/api-service.js"), 
t = require("../../../../utils/common-util.js"), 
i = getApp(), 
o = "",
h = (i.require("utils/util.js"), i.require("utils/api.js"));

Page({
    data: {
        examList: [],
        pageIndex: 1,
        loadProcess: !1,
        loadingComplete: !1,
        scrollHeight: 600,
        reqInit: !1,
        regionDialog: !1,
        yearDialog: !1,
        yearList: [],
        regionList: [],
        selectRegion: "",
        selectRegionName: "",
        selectYear: ""
    },
    onLoad: function(e) {
        o = e.busiId, this.setData({
            scrollHeight: i.getSystemHeight() - 40
        });
    },
    loadRegionYear: function(e) {
        var i = this;
        a.examRegionYear(e, function(e) {
            if (0 == e.code) {
                var a = e.result, o = a.years, n = a.regions, s = n[0], r = o[0];
                i.setData({
                    yearList: o,
                    regionList: n,
                    selectRegionName: s.regionName,
                    selectRegion: s.id,
                    selectYear: r
                }), i.loadExam(!1);
            } else t.dialog.alert({
                title: "",
                content: e.message,
                success: function(e) {
                    wx.navigateBack({
                        delta: 1
                    });
                }
            });
        }, function(e) {
            t.dialog.alert({
                title: "",
                content: e.message,
                success: function(e) {
                    i.loadRegionYear();
                }
            });
        });
    },
    buyExam: function(e) {
        var i = e.currentTarget.dataset.id;
        a.examCheckAuth({
            pId: "",
            id: i
        }, function(e) {
            if (0 == e.code) t.dialog.alertMsg("您已拥有此资源，无需购买"); else if (1 == e.code) t.dialog.alertMsg(e.message); else if (2 == e.code) dialog.confirm({
                title: "",
                content: e.message,
                cancelText: "取消",
                confirmText: "去续费",
                cancelColor: "#353535",
                confirmColor: "#3bbb8d",
                complete: function(e) {
                    if (!e.cancel) {
                        var a = "../member/index?id=" + i + "&type=0";
                        wx.navigateTo({
                            url: a
                        });
                    }
                }
            }); else if (3 == e.code) {
                var a = "../member/index?id=" + i + "&type=0";
                wx.navigateTo({
                    url: a
                });
            }
        }, function(e) {
            t.dialog.alertMsg("获取数据失败，请重试");
        });
    },
    chooseExam: function(a) {
        var t = a.currentTarget.dataset.id, i = a.currentTarget.dataset.index, o = this.data.examList[i].expand;
        0 == this.data.examList[i].subList.length && this.loadPaperByExam(t, i);
        var n = "examList[" + i + "].expand";
        this.setData(e({}, n, !o));
    },
    choosePaper: function(e) {
        var i = e.currentTarget.dataset.pid, o = e.currentTarget.dataset.id;
        a.examCheckAuth({
            pId: i,
            id: o
        }, function(e) {
            if (0 == e.code) {
                var a = "../topic/index?paper=" + o;
                wx.navigateTo({
                    url: a
                });
            } else 1 == e.code ? t.dialog.alertMsg(e.message) : 2 == e.code ? dialog.confirm({
                title: "",
                content: e.message,
                cancelText: "取消",
                confirmText: "去续费",
                cancelColor: "#353535",
                confirmColor: "#3bbb8d",
                complete: function(e) {
                    if (!e.cancel) {
                        wx.navigateTo({
                            url: "../member/index"
                        });
                    }
                }
            }) : 3 == e.code && dialog.confirm({
                title: "",
                content: "",
                cancelText: "取消",
                confirmText: "购买试卷",
                cancelColor: "#3bbb8d",
                confirmColor: "#353535",
                complete: function(e) {
                    if (!e.cancel) {
                        var a = "../member/index?id=" + o + "&type=1";
                        wx.navigateTo({
                            url: a
                        });
                    }
                }
            });
        }, function(e) {
            t.dialog.alertMsg("获取数据失败，请重试");
        });
    },
    loadPaperByExam: function(i, o) {
        var n = "examList[" + o + "].subList", s = this;
        a.examPapersByExam({
            examId: i
        }, function(a) {
            var t = a.result;
            s.setData(e({}, n, t));
        }, function(e) {
            t.dialog.alert({
                title: "",
                content: e.message,
                success: function(e) {
                    s.loadPaperByExam(i, o);
                }
            });
        });
    },
    chooseRegionItem: function(e) {
        var a = e.currentTarget.dataset.id, t = e.currentTarget.dataset.name;
        this.setData({
            selectRegionName: t,
            regionDialog: !1,
            selectRegion: a,
            pageIndex: 1
        }), this.loadExam(!1);
    },
    chooseYearItem: function(e) {
        var a = e.currentTarget.dataset.id;
        this.setData({
            yearDialog: !1,
            selectYear: a
        }), this.loadExam(!1);
    },
    chooseRegion: function() {
        this.setData({
            regionDialog: !this.data.regionDialog
        });
    },
    chooseYear: function() {
        this.setData({
            yearDialog: !this.data.yearDialog
        });
    },
    loadExam: function(e) {
        var t = this;
        t.setData({
            loadProcess: !0
        });
        var n = {
            busiId: o,
            regionId: t.data.selectRegion,
            year: t.data.selectYear,
            pageIndex: this.data.pageIndex,
            pageSize: i.globalData.pageSize
        };
        a.examIndex(n, function(a) {
            var i = a.rows;
            if (e) {
                if (i.length >= 1) {
                    var o = t.data.scoreList.concat(i);
                    t.setData({
                        examList: o,
                        loadProcess: !1,
                        reqInit: !0
                    });
                }
            } else t.setData({
                examList: i,
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
        }), e.loadExam(!0));
    }
});