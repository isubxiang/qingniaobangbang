var t = !1, e = {
    phoneCall: function(t) {
        wx.makePhoneCall({
            phoneNumber: t
        });
    },
    longCopy: function(t) {
        var e = t.currentTarget.dataset.tx;
        wx.setClipboardData({
            data: e,
            success: function(t) {
                wx.showToast({
                    title: "复制成功"
                });
            }
        });
    },
    validPhoneNum: function(t) {
        var e = {
            valid: !0,
            msg: ""
        };
        if (null == t || "" == t.toString()) return e.valid = !1, e.msg = "手机号码不能为空", e;
        var n = /^\d{11}$/, r = t.toString();
        return "1" == r.charAt(0) && 11 == r.length && n.test(r) ? e : (e.valid = !1, e.msg = "手机号码格式不正确", 
        e);
    },
    validNumber: function(t) {
        return /^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/.test(t);
    },
    constructParamStr: function(t) {
        var e, n, r = "";
        for (e in t) void 0 !== (n = t[e]) && null !== n && (r += e + "=" + n + "&");
        return r.length ? r.substr(0, r.length - 1) : r;
    },
    accAdd: function(t, e) {
        var n, r, a;
        return n = -1 != t.toString().indexOf(".") ? t.toString().split(".")[1].length : 0, 
        r = -1 != e.toString().indexOf(".") ? e.toString().split(".")[1].length : 0, a = Math.pow(10, Math.max(n, r)), 
        (this.accMul(t, a) + this.accMul(e, a)) / a;
    },
    accMul: function(t, e) {
        var n = 0, r = t.toString(), a = e.toString();
        return -1 != r.indexOf(".") && (n += r.split(".")[1].length), -1 != a.indexOf(".") && (n += a.split(".")[1].length), 
        Number(r.replace(".", "")) * Number(a.replace(".", "")) / Math.pow(10, n);
    },
    callBackFunction: function(t, e) {
        if ("function" == typeof t) {
            var n = this;
            null != e ? t.apply(n, [ e ]) : t.apply(n);
        }
    },
    combineArr: function(t, e) {
        if (!t || !t.length || !t instanceof Array || !e) return [];
        for (var n = {}, r = [], a = 0; a < t.length; a++) {
            var o = t[a];
            if (n[o[e]]) for (var i = 0; i < r.length; i++) {
                var c = r[i];
                if (c[e] == o[e]) {
                    c.data.push(o);
                    break;
                }
            } else {
                var u = {};
                u[e] = o[e], u.data = [ o ], r.push(u), n[o[e]] = o;
            }
        }
        return r;
    },
    clone: function(t) {
        return JSON.parse(JSON.stringify(t));
    },
    versionCompare: function(t, e) {
        for (var n = t.split("."), r = e.split("."), a = n.length, o = r.length, i = Math.max(a, o), c = 0; c < i; c++) {
            var u = 0, s = 0;
            if (c < n.length && (u = parseInt(n[c], 10)), c < r.length && (s = parseInt(r[c], 10)), 
            u != s) return u > s ? 1 : -1;
        }
        return 0;
    },
    formatDate: function(t, e) {
        var n = e, r = [ "日", "一", "二", "三", "四", "五", "六" ];
        return n = n.replace(/yyyy|YYYY/, t.getFullYear()), n = n.replace(/yy|YY/, t.getYear() % 100 > 9 ? (t.getYear() % 100).toString() : "0" + t.getYear() % 100), 
        n = n.replace(/MM/, t.getMonth() >= 9 ? (t.getMonth() + 1).toString() : "0" + (t.getMonth() + 1)), 
        n = n.replace(/M/g, t.getMonth() + 1), n = n.replace(/w|W/g, r[t.getDay()]), n = n.replace(/dd|DD/, t.getDate() > 9 ? t.getDate().toString() : "0" + t.getDate()), 
        n = n.replace(/d|D/g, t.getDate()), n = n.replace(/hh|HH/, t.getHours() > 9 ? t.getHours().toString() : "0" + t.getHours()), 
        n = n.replace(/h|H/g, t.getHours()), n = n.replace(/mm/, t.getMinutes() > 9 ? t.getMinutes().toString() : "0" + t.getMinutes()), 
        n = n.replace(/m/g, t.getMinutes()), n = n.replace(/ss|SS/, t.getSeconds() > 9 ? t.getSeconds().toString() : "0" + t.getSeconds()), 
        n = n.replace(/s|S/g, t.getSeconds());
    },
    leftTimeStr: function(t) {
        var e = Math.max(0, t - parseInt(new Date().getTime() / 1e3, 10)), n = 0, r = 0, a = 0, o = 0;
        return e >= 1 && (n = Math.floor(e / 86400), r = Math.floor(e / 3600) - 24 * n, 
        a = Math.floor(e / 60) - 24 * n * 60 - 60 * r, o = Math.floor(e) - 24 * n * 60 * 60 - 60 * r * 60 - 60 * a), 
        r <= 9 && (r = "0" + r), a <= 9 && (a = "0" + a), o <= 9 && (o = "0" + o), [ r, a, o ].join(":");
    },
    getDefaultTimeIndex: function(t) {
        for (var e = -1, n = 0; n < t.length; n++) {
            if ("12:00" == t[n]) {
                e = n;
                break;
            }
            if ("18:00" == t[n]) {
                e = n;
                break;
            }
        }
        return -1 == e && (e = t.length >= 2 ? 1 : 0), e;
    },
    get24Array: function() {
        for (var t = [], e = 1; e <= 24; e++) t.push(e);
        return t;
    },
    leftTimeDayStr: function(t) {
        var e = Math.max(0, t - parseInt(new Date().getTime() / 1e3, 10)), n = 0, r = 0, a = 0, o = 0;
        return e >= 1 && (n = Math.floor(e / 86400), r = Math.floor(e / 3600) - 24 * n, 
        a = Math.floor(e / 60) - 24 * n * 60 - 60 * r, o = Math.floor(e) - 24 * n * 60 * 60 - 60 * r * 60 - 60 * a), 
        r <= 9 && (r = "0" + r), a <= 9 && (a = "0" + a), o <= 9 && (o = "0" + o), [ n, "天", r, "时", a, "分", o ].join("");
    },
    initDayArray: function(t) {
        var e = [];
        t || e.push("今天");
        var n = new Date(), r = new Date(n.setDate(n.getDate() + 1)), a = this.formatDate(r, "yyyy-MM-dd"), o = new Date(n.setDate(n.getDate() + 1)), i = this.formatDate(o, "yyyy-MM-dd");
        return e.push("明天(" + a + ")"), e.push("后天(" + i + ")"), e;
    },
    initTimeArray: function(t, e) {
        for (var n = [], r = t; r <= e; r++) {
            var a = "";
            a = r <= 9 ? "0" + r + ":" : r + ":", n.push(a + "00"), r != e && n.push(a + "30");
        }
        return n;
    },
    calTime: function() {
        var t = new Date(), e = t.getHours() >= 21, n = new Date(t.setMinutes(t.getMinutes() + 30)), r = n.getMinutes();
        r % 30 != 0 && n.setMinutes(r - r % 30);
        var a = n.getHours();
        return e ? this.initTimeArray(7, 20, !1) : this.initTimeArray(a + 1, 20);
    },
    buttonClicked: function(t) {
        t.setData({
            buttonClicked: !0
        }), setTimeout(function() {
            t.setData({
                buttonClicked: !1
            });
        }, 350);
    }
}, n = {
    distance: function(t, e, n, r) {
        var a = 3.141592653589793, o = Math.cos(t * a / 180) * Math.cos(n * a / 180) * Math.cos((e - r) * a / 180) + Math.sin(t * a / 180) * Math.sin(n * a / 180);
        o > 1 && (o = 1), o < -1 && (o = -1);
        var i = 6371e3 * Math.acos(o);
        return i;
    },
    getDistance: function(t, e, n, r) {
        var a = {
            far: !1,
            desc: ""
        }, o = this.distance(t, e, n, r);
        if (o > 1e3) {
            var i = new Number(o / 1e3);
            i <= 100 ? a.desc = "约" + i.toFixed(0) + "公里" : (a.desc = "距离太远", a.far = !0);
        } else a.desc = "约" + o.toFixed(0) + "米";
        return a;
    }
}, r = {
    alertMsg: function(t) {
        if ("" != t) {
            var e = {
                title: "",
                showCancel: !1,
                confirmColor: "#3bbb8d",
                content: t
            };
            wx.showModal(e);
        }
    },
    alert: function(t) {
        var e = {
            title: "提示",
            showCancel: !1,
            confirmColor: "#3bbb8d"
        };
        if (void 0 === t.content || "" != t.content) {
            for (var n in t) e[n] = t[n];
            wx.showModal(e);
        }
    },
    confirm: function(t) {
        var e = {
            title: "提示",
            cancelText: "取消",
            confirmText: "确定",
            confirmColor: "#3bbb8d"
        };
        for (var n in t) e[n] = t[n];
        wx.showModal(e);
    },
    toastMsg: function(t) {
        if ("" != t) {
            var e = {
                title: t,
                icon: "none",
                duration: 1e3
            };
            wx.showToast(e);
        }
    },
    toast: function(t) {
        var e = {
            title: "",
            icon: "none",
            duration: 2e3
        };
        for (var n in t) e[n] = t[n];
        wx.showToast(e);
    },
    showLoading: function(e) {
        if (!t) {
            t = !0;
            var n = {
                mask: !0
            };
            for (var r in e) n[r] = e[r];
            wx.showLoading(n);
        }
    },
    hideLoading: function() {
        wx.hideLoading(), t = !1;
    },
    showActionSheet: function(t) {
        var e = {};
        for (var n in t) e[n] = t[n];
        wx.showActionSheet(t);
    }
}, a = {
    wxPay: function(t, e) {

        console.log('=wxPay=');
        console.log(t);
        
        var n = {
            timeStamp: t.timeStamp,
            nonceStr: t.nonceStr,
            package: t.packageStr,
            signType: t.signType,
            paySign: t.paySign,
            complete: e
        };
        wx.requestPayment(n);
    },
    showNoPayResultInfoPop: function(t, n) {
        r.confirm({
            title: "暂未获得支付结果",
            content: "您可继续等待(刷新结果)支付结果，如果支付失败但有扣款，请致电客服",
            cancelText: "刷新结果",
            confirmText: "关闭",
            cancelColor: "#3bbb8d",
            confirmColor: "#353535",
            complete: function(r) {
                r.confirm ? e.callBackFunction(t) : r.cancel ? e.callBackFunction(n) : e.callBackFunction(t);
            }
        });
    },
    showTimeoutPayResultInfoPop: function(t, n) {
        r.alert({
            title: "",
            content: t + "如有疑问请致电客服",
            confirmText: "关闭",
            complete: function(t) {
                e.callBackFunction(n);
            }
        });
    }
}, o = {
    arrayBufferToStr: function(t) {
        return String.fromCharCode.apply(null, new Uint8Array(t));
    },
    stringToArrayBuffer: function(t) {
        for (var e = t.length, n = new Uint8Array(e), r = 0, a = e; r < a; r++) n[r] = t.charCodeAt(r);
        return n.buffer;
    }
}, i = {
    addItem: function(t, e, n, r) {
        wx.setStorage({
            key: t,
            data: e,
            success: n,
            fail: r
        });
    },
    addItemSync: function(t, e) {
        wx.setStorageSync(t, e);
    },
    getItem: function(t, e, n) {
        return wx.getStorage({
            key: t,
            success: e,
            fail: n
        });
    },
    getItemSync: function(t) {
        return wx.getStorageSync(t);
    },
    removeItem: function(t, e, n) {
        wx.removeStorage({
            key: t,
            success: e,
            fail: n
        });
    },
    removeItemSync: function(t) {
        wx.removeStorageSync(t);
    }
}, c = {
    isNullOrEmpty: function(t) {
        return void 0 == t || null == t || "" == t;
    }
};

module.exports = {
    gps: n,
    dialog: r,
    pay: a,
    buffer: o,
    storage: i,
    strings: c,
    tools: e
};