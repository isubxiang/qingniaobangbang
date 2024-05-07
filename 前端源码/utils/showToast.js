var t = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
    return typeof t;
} : function(t) {
    return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t;
};

wx.pubShow = function(e) {
    if ("object" == (void 0 === e ? "undefined" : t(e)) && e.title) {
        e.duration && "number" == typeof e.duration || (e.duration = 1800);
        var n = getCurrentPages()[getCurrentPages().length - 1];
        e.isShow = !0, e.duration < 1e4 && setTimeout(function() {
            e.isShow = !1, e.cb && "function" == typeof e.cb && e.cb(), n.setData({
                "showToast.isShow": e.isShow
            });
        }, e.duration), n.setData({
            showToast: e
        });
    } else console.log("showToast fail:请确保传入的是对象并且title必填");
}, wx.pubHide = function() {
    var t = getCurrentPages()[getCurrentPages().length - 1];
    t.data.showToast && t.setData({
        "showToast.isShow": !1
    });
}, wx.pubLoading = function(t) {
    var e = getCurrentPages()[getCurrentPages().length - 1];
    1 == t ? e.setData({
        ing: t,
        end: !1
    }) : e.setData({
        ing: t,
        end: !0
    });
}, wx.pubNodata = function(t) {
    getCurrentPages()[getCurrentPages().length - 1].setData({
        nodata: t
    });
}, wx.pubOpenSetting = function(t, e) {
    var n = getCurrentPages()[getCurrentPages().length - 1];
    n.setData({
        OpenSettingShow: t
    }), e && n.setData({
        OpenSettingType: e
    });
};