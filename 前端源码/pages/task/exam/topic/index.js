function t(t, e, a) {
    return e in t ? Object.defineProperty(t, e, {
        value: a,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[e] = a, t;
}

var e = require("../../../../utils/service/api-service.js"), 
a = require("../../../../utils/common-util.js"), 
i = require("../../../../wxParse/wxParse.js"), 
s = require("../../../../utils/onfire.js"), 
r = "", 
n = 0,
i = getApp(),
h = (i.require("utils/util.js"), i.require("utils/api.js"));

Page({
    data: {
        navName: "",
        cardDialog: !1,
        reqInit: !1,
        questList: [],
        position: 0,
        questAnswer: [],
        cardList: [],
        multiArrObj: {},
        assess: {
            total: "",
            rightNum: "",
            errNum: "",
            rightRatio: ""
        },
        errFlag: 0
    },
    onLoad: function(t) {
        n = 0, r = t.paper, void 0 !== t.err && this.setData({
            errFlag: 1
        }), void 0 !== t.name ? (wx.setNavigationBarTitle({
            title: "题库"
        }), this.setData({
            navName: t.name
        })) : void 0 !== t.err && wx.setNavigationBarTitle({
            title: "错题记录"
        }), this.loadQuest(), this.batchSubmitLocalErr();
    },
    onUnload: function() {
        s.fire("topicupload", {
            paperId: r
        });
    },
    checkAnswer: function() {
        var t = this.data.multiArrObj;
        for (var e in t) if (t[e]) return !0;
        return !1;
    },
    showAnswer: function(e) {
        if (n < this.data.questList.length) {
            var a = "questList[" + n + "].showAnswer", i = this.data.questList[n].showAnswer;
            this.setData(t({}, a, !i));
        }
    },
    bindSwiperChange: function(t) {
        if (t.detail.current < this.data.questList.length) {
            var e = this.data.questList[t.detail.current];
            this.parseNode(e, t.detail.current, !0);
        }
        if (n < t.detail.current) {
            var a = this.data.questList[n];
            if ("2" == a.questType) {
                var i = [], s = this.data.multiArrObj;
                for (var r in s) s[r] && i.push(r);
                if (i.length >= 2) {
                    i.sort();
                    var o = i.join(",");
                    this.questSlide(a, n, o);
                } else 1 == i.length && this.questSlide(a, n, i[0]);
            }
            t.detail.current == this.data.questList.length && this.calAessess();
        }
        n = t.detail.current, this.setData({
            multiArrObj: {}
        });
    },
    calAessess: function() {
        for (var t = {
            total: "",
            rightNum: "",
            errNum: "",
            rightRatio: ""
        }, e = this.data.questList, i = e.length, s = 0, r = 0, n = 0, o = 0; o < i; o++) {
            var u = e[o], d = u.used, l = u.rightFlag;
            d && (s += 1, l ? r += 1 : n += 1);
        }
        var c = "0%";
        s >= 1 && (c = a.tools.accMul(100 * r, 1 / s).toFixed(2)), t.total = s, t.rightNum = r, 
        t.errNum = n, t.rightRatio = c + "%", this.setData({
            assess: t
        });
    },
    questSlide: function(e, a, i) {
        var s = this;
        if (!e.used) {
            var n = "questList[" + a + "]", o = e.id, u = e.questType, d = i == e.answer;
            if (e.rightFlag = d, e.sanswer = i, e.used = !0, "0" == u || "1" == u) {
                var l;
                this.setData((l = {
                    position: a + 1
                }, t(l, n, e), t(l, "multiArrObj", {}), l));
            } else {
                var c;
                this.setData((c = {}, t(c, n, e), t(c, "multiArrObj", {}), c));
            }
            !function(t, e, a) {
                setTimeout(function() {
                    s.updateCardCs(t, e, a);
                });
            }(e.qtypeStr, a, d), 0 == this.data.errFlag && function(t, e) {
                setTimeout(function() {
                    s.submitAnswer({
                        paperId: r,
                        questId: t,
                        answer: e,
                        showLoading: !1
                    });
                });
            }(o, i);
        }
    },
    resetPaper: function() {
        var t = this;
        a.dialog.confirm({
            title: "",
            content: "确认清除做题记录？",
            success: function(e) {
                e.confirm && t.resetSubmit();
            }
        });
    },
    examErrDel: function() {
        var t = this;
        n == this.data.questList.length ? a.dialog.confirm({
            title: "",
            content: "确认移除所有错题记录？",
            success: function(e) {
                e.confirm && t.examErrDelAllSubmit();
            }
        }) : a.dialog.confirm({
            title: "",
            content: "确认移除该错题记录？",
            success: function(e) {
                e.confirm && t.examErrDelSubmit();
            }
        });
    },
    examErrDelAllSubmit: function() {
        e.examErrDelAll({
            paperId: r
        }, function(t) {
            a.dialog.alertMsg(t.message), 0 == t.code && s.fire("errmoveall", {
                paperId: r
            });
        }, function(t) {
            a.dialog.alertMsg("移除错题记录发生异常，请重试");
        });
    },
    examErrDelSubmit: function() {
        var t = this.data.questList[n];
        e.examErrDel({
            questId: t.id
        }, function(t) {
            a.dialog.alertMsg(t.message), 0 == t.code && s.fire("errmove", {
                paperId: r
            });
        }, function(t) {
            a.dialog.alertMsg("移除错题记录发生异常，请重试");
        });
    },
    resetSubmit: function() {
        var t = this;
        0 == this.data.errFlag ? e.examReset({
            paperId: r
        }, function(e) {
            a.dialog.alertMsg(e.message), 0 == e.code && (t.loadQuest(), n = 0, t.setData({
                position: 0,
                cardDialog: !1,
                cardList: []
            }), a.storage.removeItemSync(r));
        }, function(t) {
            a.dialog.alertMsg("清除做题记录发生异常，请重试");
        }) : (t.loadQuest(), t.setData({
            position: 0,
            cardDialog: !1,
            cardList: []
        }));
    },
    chooseQuest: function(e) {
        var a = e.currentTarget.dataset.letter, i = this.data.questList[n], s = "multiArrObj." + a;
        if (!i.used) {
            var r = i.questType;
            "0" == r || "1" == r ? (this.setData(t({}, s, !this.data.multiArrObj[a])), this.questSlide(i, n, a)) : "2" == r && this.setData(t({}, s, !this.data.multiArrObj[a]));
        }
    },
    batchSubmitLocalErr: function() {
        var t = a.storage.getItemSync(r);
        if (t && Array.isArray(t) && t.length >= 1) {
            var i = {
                list: t
            };
            e.examBatchSubAnswer({
                data: JSON.stringify(i)
            }, function(t) {
                a.storage.removeItemSync(r);
            }, function(t) {});
        }
    },
    submitAnswer: function(t) {
        var i = this;
        e.examSubAnswer(t, function(t) {}, function(e) {
            var s = t.paperId, r = a.storage.getItemSync(s);
            if (r) r.push(t), a.storage.addItemSync(s, r); else {
                var n = [];
                n.push(t), a.storage.addItemSync(s, n);
            }
            setTimeout(function() {
                i.batchSubmitLocalErr();
            }, 5e3);
        });
    },
    chooseCardIndex: function(t) {
        var e = t.currentTarget.dataset.index, a = parseInt(e, 10) - 1;
        this.setData({
            position: a,
            cardDialog: !1
        });
    },
    hideCard: function() {
        this.setData({
            cardDialog: !1
        });
    },
    showCard: function() {
        if (0 == this.data.cardList.length) {
            a.dialog.showLoading({
                title: "加载中..."
            });
            for (var t = "", e = [], i = this.data.questList, s = {
                typeName: "",
                subList: []
            }, r = i.length, n = 0; n < r; n++) {
                t != i[n].qtypeStr && (t = i[n].qtypeStr, s = {
                    typeName: i[n].qtypeStr,
                    subList: []
                }, e.push(s));
                var o = "";
                i[n].used && (o = i[n].rightFlag ? "select" : "err"), s.subList.push({
                    id: n + 1,
                    css: o
                });
            }
            a.dialog.hideLoading(), this.setData({
                cardDialog: !this.data.cardDialog,
                cardList: e
            });
        } else this.setData({
            cardDialog: !this.data.cardDialog
        });
    },
    updateCardCs: function(e, a, i) {
        for (var s = 0, r = this.data.cardList, n = r.length, o = -1, u = i ? "select" : "err", d = 0; d < n; d++) {
            if (r[d].typeName == e) {
                o = d;
                break;
            }
            s += r[d].subList.length;
        }
        if (-1 != o) {
            var l = a - s;
            if (l < this.data.cardList[o].subList.length) {
                var c = "cardList[" + o + "].subList[" + l + "].css";
                this.setData(t({}, c, u));
            }
        }
    },
    parseNode: function(e, a, s) {
        var r = this;
        if (!e.parse) {
            for (var n = [ "qname", "qa", "qb", "qc", "qd", "qe", "qf", "explain" ], o = n.length, u = 0; u < o; u++) {
                var d = "p" + n[u], l = "questList[" + a + "]." + d;
                e[d] = i.wxParseReturn(l, "html", e[n[u]], r), e.parse = !0;
            }
            if (s) {
                var c = "questList[" + a + "]";
                r.setData(t({}, c, e));
            }
        }
    },
    loadQuest: function() {
        var t = this;
        a.dialog.showLoading({
            title: "加载中..."
        }), e.examQuests({
            paperId: r,
            err: this.data.errFlag,
            showLoading: !1
        }, function(e) {
            for (var s = e.result, r = Math.min(1, s.length), n = 0; n < r; n++) t.parseNode(s[n], n, !1);
            t.wxParseImgTap = i.wxParseImgTap, t.wxParseImgLoad = i.wxParseImgLoad, t.setData({
                questList: s,
                reqInit: !0
            }, function() {
                a.dialog.hideLoading();
            });
        }, function(e) {
            a.dialog.hideLoading(), a.dialog.alert({
                title: "",
                content: e.message,
                success: function(e) {
                    t.loadQuest();
                }
            });
        });
    }
});