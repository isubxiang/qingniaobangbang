var e = require("../../../../utils/service/api-service.js"), 
t = require("../../../../utils/common-util.js"), 
a = "", 
i = "",
u = getApp(),
h = (u.require("utils/util.js"), u.require("utils/api.js"));

Page({
    data: {
        regionDialog: !1,
        yearDialog: !1,
        yearList: [],
        regionList: [],
        selectRegion: "",
        selectRegionName: "",
        selectYear: "",
        paperList: [],
        topArr: [],
        subArr: [],
        selectTopItem: "",
        selectChildItem: "",
        subMap: {}
    },
    preventTouchMove: function() {},
    onLoad: function(e) {
        a = e.busiId, i = "", this.loadRegionYear(a), this.loadTypeInfo();
    },
    loadTypeInfo: function() {
        e.examTypeInfo({
            id: a
        }, function(e) {
            var t = e.result;
            wx.setNavigationBarTitle({
                title: t.typeName
            });
        }, function(e) {});
    },
    chooseTop: function(e) {
        var t = e.currentTarget.dataset.tid;
        this.setData({
            selectTopItem: t,
            subArr: this.data.subMap[t]
        });
    },
    chooseSub: function(e) {
        var t = e.currentTarget.dataset.id;
        i = e.currentTarget.dataset.pid;
        var a = e.currentTarget.dataset.year, o = e.currentTarget.dataset.pname;
        this.setData({
            selectChildItem: t,
            regionDialog: !1,
            selectYear: a,
            selectRegionName: o
        }), this.loadPaper(i, a);
    },
    loadRegionYear: function(a) {
        var o = this;
        e.examRegionYearPaper(a, function(e) {
            if (0 == e.code) {
                for (var a = e.result, n = "", r = "", s = "", c = "", l = [], d = [], u = a.length, g = 0; g < u; g++) {
                    var m = a[g];
                    0 == g && (n = m.id, i = m.id, r = m.regionName);
                    var p = m.subList;
                    0 == g && (s = m.id + "0", c = p[0]);
                    for (var f = p.length, h = [], v = 0; v < f; v++) h.push({
                        id: m.id + v,
                        year: p[v],
                        pname: m.regionName,
                        pid: m.id
                    });
                    l.push({
                        id: m.id,
                        regionName: m.regionName
                    }), d[m.id] = h;
                }
                o.setData({
                    selectRegionName: r,
                    selectTopItem: n,
                    selectChildItem: s,
                    selectYear: c,
                    subMap: d,
                    topArr: l,
                    subArr: d[n]
                }), o.loadPaper(n, c);
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
                    o.loadRegionYear();
                }
            });
        });
    },
    loadPaper: function(i, o) {
        var n = this, r = this;
        e.examPapers({
            busiId: a,
            regionId: i,
            year: o
        }, function(e) {
            var t = e.result;
            n.setData({
                paperList: t
            });
        }, function(e) {
            t.dialog.alert({
                title: "",
                content: e.message,
                success: function(e) {
                    r.loadPaper(i, o);
                }
            });
        });
    },
    choosePaper: function(a) {
        var i = a.currentTarget.dataset.id, o = a.currentTarget.dataset.name;
        e.examCheckAuth({
            pId: "",
            id: i
        }, function(e) {
            if (0 == e.code) {
                var a = "../topic/index?paper=" + i + "&name=" + o;
                wx.navigateTo({
                    url: a
                });
            } else 1 == e.code ? t.dialog.alertMsg(e.message) : 2 == e.code ? t.dialog.confirm({
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
            }) : 3 == e.code && t.dialog.confirm({
                title: "",
                content: e.message,
                cancelText: "取消",
                confirmText: "购买试卷",
                cancelColor: "#353535",
                confirmColor: "#3bbb8d",
                complete: function(e) {
                    if (!e.cancel) {
                        var t = "../member/index?id=" + i + "&type=1";
                        wx.navigateTo({
                            url: t
                        });
                    }
                }
            });
        }, function(e) {
            t.dialog.alertMsg("获取数据失败，请重试");
        });
    },
    chooseRegion: function() {
        this.setData({
            regionDialog: !this.data.regionDialog
        });
    }
});