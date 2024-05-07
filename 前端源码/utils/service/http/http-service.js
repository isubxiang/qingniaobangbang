function o(o, a, e, t, n, i, l) {
    e.rand = Math.random(), e.appVersion = getApp().globalData.appVersion, wx.request({
        url: a,
        method: o,
        data: e,
        header: t,
        success: n,
        fail: i,
        complete: l
    });
}

var a = require("../../common-util.js"), e = !1, t = {
    _preparRequest: function(o) {
        var e = {
            showLoading: !1,
            headerObj: {
                "content-type": "application/x-www-form-urlencoded"
            }
        }, t = getApp().globalData.cookie;
        "" != t && (e.headerObj.cookie = t);
        var n = !0;
        return void 0 !== o.showLoading && (n = o.showLoading, delete o.showLoading), n && wx.showLoading && a.dialog.showLoading({
            title: "加载中..."
        }), e.showLoading = n, e;
    },
    post: function(t, n, i, l) {
        var c = this._preparRequest(n);
        delete n.showLoading, o("POST", t, n, c.headerObj, function(o) {
            o.header["Set-Cookie"] && (getApp().globalData.cookie = o.header["Set-Cookie"]), 
            200 == o.statusCode ? 100 == o.data.code ? (e || (e = !0, getApp().reLogin(function() {
                e = !1;
            }, function() {
                e = !1;
            })), a.tools.callBackFunction(l, o.data)) : a.tools.callBackFunction(i, o.data) : a.tools.callBackFunction(l, o.data);
        }, l, function() {
            c.showLoading && a.dialog.hideLoading();
        });
    },
    get: function(t, n, i, l) {
        var c = this._preparRequest(n);
        delete n.showLoading, o("GET", t, n, c.headerObj, function(o) {
            o.header["Set-Cookie"] && (getApp().globalData.cookie = o.header["Set-Cookie"]), 
            200 == o.statusCode ? 100 == o.data.code ? (e || (e = !0, getApp().reLogin(function() {
                e = !1;
            }, function() {
                e = !1;
            })), a.tools.callBackFunction(l, o.data)) : a.tools.callBackFunction(i, o.data) : a.tools.callBackFunction(l, o.data);
        }, l, function() {
            c.showLoading && a.dialog.hideLoading();
        });
    },
    uploadFile: function(o, t, n, i, l) {
        var c = Math.random(), d = getApp().globalData.appVersion;
        t.appVersion = d, t.rand = c;
        var s = {
            headerObj: {}
        };
        "" != getApp().globalData.cookie && (s.headerObj.cookie = getApp().globalData.cookie), 
        wx.uploadFile({
            url: o,
            filePath: n,
            name: "file",
            formData: t,
            header: s.headerObj,
            success: function(o) {
                var t = JSON.parse(o.data);
                200 == o.statusCode ? 100 == o.data.code ? (e || (e = !0, getApp().reLogin(function() {
                    e = !1;
                }, function() {
                    e = !1;
                })), a.tools.callBackFunction(l, t)) : a.tools.callBackFunction(i, t) : a.tools.callBackFunction(l, t);
            },
            fail: l
        });
    },
    downloadFile: function(o, e, t) {
        wx.downloadFile({
            url: o,
            success: function(o) {
                200 == o.statusCode ? a.tools.callBackFunction(e, o) : a.tools.callBackFunction(t);
            },
            fail: t
        });
    }
};

module.exports = t;