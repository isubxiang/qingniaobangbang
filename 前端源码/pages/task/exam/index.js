var t = require("../../../utils/service/api-service.js"), 
e = require("../../../utils/common-util.js"), 
a = require("../../../utils/onfire.js"), 
n = "", 
o = getApp(),
h = (o.require("utils/util.js"), o.require("utils/api.js"));

Page({
    data: {
        noticeList: [],
        marqueeDistance: 0,
        typeDialog: !1,
        centerFlag: !1,
        topArr: [],
        subArr: [],
        subMap: {},
        selectTopItem: "",
        selectChildItem: "",
        selectTypeInfo: {
            topName: "",
            subName: "",
            topId: "",
            subId: ""
        },
        fnList: [],
        personInit: !1,
        rankPerson: {},
        totalPerson: "",
        quest: {
            answerNum: 0,
            rightRatio: "0%"
        },
        servantShow: !1
    },
    toNotice: function(t) {
        var e = "../../common/notice?id=" + t.currentTarget.dataset.id + "&type=1";
        wx.navigateTo({
            url: e
        });
    },
    scrollNotice: function() {
        o.scrollNotice(this, o.getSystemWidth());
    },
    listNotice: function() {
        var t = this;
        o.listBusiNotice(this, n, function() {
            t.scrollNotice();
        });
    },
    onLoad: function(t) {
        n = t.id, this.loadTopType(), this.loadTotalPerson(), this.loadRankPerson();
        var e = this;
        this.setData({
            servantShow: o.isWx()
        }), a.on("exammemup", function(t) {
            e.loadRankPerson();
        }), a.on("topicupload", function(t) {
            e.loadQuestRatio(e.data.selectChildItem);
        }), this.loadTypeInfo();
    },
    onUnload: function() {
        a.un("exammemup"), a.un("topicupload"), this.intervalObj && null != this.intervalObj && clearInterval(this.intervalObj);
    },
    loadTypeInfo: function() {
        var e = this;
        t.getCatType(n, function(t) {
            var a = t.result;
            wx.setNavigationBarTitle({
                title: a.busiName
            }), e.listNotice();
        }, function(t) {});
    },
    chooseTop: function(t) {
        var e = t.currentTarget.dataset.tid;
        this.setData({
            selectTopItem: e,
            subArr: this.data.subMap[e]
        });
    },
    chooseSub: function(t) {
        var a = t.currentTarget.dataset.id, n = t.currentTarget.dataset.pid, o = t.currentTarget.dataset.name, i = t.currentTarget.dataset.pname, s = {
            subId: a,
            topId: n,
            subName: o,
            topName: i
        };
        e.storage.addItem("examtype", s, function() {}, function() {}), this.setData({
            selectChildItem: a,
            typeDialog: !1,
            "selectTypeInfo.subId": a,
            "selectTypeInfo.topId": n,
            "selectTypeInfo.topName": i,
            "selectTypeInfo.subName": o
        }), this.loadChildType(a), this.loadQuestRatio(a);
    },
    loadChildType: function(a) {
        var n = this;
        t.examChildTypeList(a, function(t) {
            var e = t.result;
            n.setData({
                fnList: e
            });
        }, function(t) {
            e.dialog.alert({
                title: "",
                content: t.message,
                success: function(t) {
                    n.loadChildType(a);
                }
            });
        });
    },
    loadQuestRatio: function(e) {
        var a = this;
        t.examQuestRatio({
            id: e
        }, function(t) {
            var e = t.result;
            a.setData({
                quest: e
            });
        }, function(t) {});
    },
    loadTopType: function() {
        var a = this, n = e.storage.getItemSync("examtype");
        t.examTopTypeList(function(t) {
            var o = t.result, i = "", s = "", r = [], u = [], l = o.length, c = !0;
            if (l >= 1) {
                for (var d = 0; d < l; d++) {
                    0 == d && (i = o[d].id);
                    var p = o[d].subList, f = p.length;
                    if (c) for (var h = 0; h < f; h++) n && p[h].id == n.subId && (c = !1);
                    r.push({
                        id: o[d].id,
                        typeName: o[d].typeName
                    }), u[o[d].id] = p;
                }
                c ? n = {
                    topName: "",
                    subName: "",
                    topId: "",
                    subId: ""
                } : (a.loadChildType(n.subId), a.loadQuestRatio(n.subId), i = n.topId, s = n.subId), 
                a.setData({
                    selectTopItem: i,
                    selectChildItem: s,
                    subMap: u,
                    topArr: r,
                    typeDialog: c,
                    selectTypeInfo: n,
                    subArr: u[i]
                });
            } else e.dialog.alert({
                title: "",
                content: "模块建设中...",
                success: function(t) {
                    wx.navigateBack({
                        delta: 1
                    });
                }
            });
        }, function(t) {
            e.dialog.alert({
                title: "",
                content: t.message,
                success: function(t) {
                    a.loadTopType();
                }
            });
        });
    },
    loadTotalPerson: function() {
        var e = this;
        t.examTotalPerson(function(t) {
            var a = t.result;
            e.setData({
                totalPerson: a
            });
        }, function(t) {});
    },
    loadRankPerson: function() {
        var e = this;
        t.examRankPerson(function(t) {
            var a = t.result;
            e.setData({
                rankPerson: a,
                personInit: !0
            });
        }, function(t) {});
    },
    memCenter: function() {
        if (3 != this.data.rankPerson.rankType) {
            wx.navigateTo({
                url: "./member/index"
            });
        }
    },
    preventTouchMove: function() {},
    chooseFn: function(t) {
        var e = t.currentTarget.dataset.id, a = t.currentTarget.dataset.path;
        if ("" != e && "" != a) {
            var n = "./" + a + "/index?busiId=" + e;
            wx.navigateTo({
                url: n
            });
        }
    },
    hideTypeDialog: function() {
        var t = e.storage.getItemSync("examtype");
        t && "" != t.subId && this.setData({
            typeDialog: !1
        });
    },
    changeType: function() {
        this.setData({
            typeDialog: !this.data.typeDialog
        });
    },
    showCenter: function(t) {
        this.setData({
            centerFlag: !this.data.centerFlag
        });
    },
    navClick: function(t) {
        var e = t.currentTarget.dataset.url;
        wx.navigateTo({
            url: e
        });
    }
});