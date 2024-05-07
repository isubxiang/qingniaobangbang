function e(e, t, n) {
    return t in e ? Object.defineProperty(e, t, {
        value: n,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[t] = n, e;
}

var t, n = getApp(), o = n.require("utils/util.js"), s = n.require("utils/api.js"), i = (n.require("utils/onfire.js"), 
[]);

Page({
    data: (t = {
        msg: "",
        isRebind: !1,
        inputMobile: !1,
        dialogType: "none",
        mobile: "",
        smsCodeInputFocus: !1,
        smsCodeSentTick: -1,
        smsCode: i
    }, e(t, "smsCodeInputFocus", !1), e(t, "success", !1), t),
    onLoad: function(e) {
        var t = !!e.rebind, n = e.normalMsg ? "在弹出窗口中，你将授权“校园跑腿”获取微信手机号。" : "为了便于赏金猎人与你取得联系，需要绑定手机后才能继续操作。";
        this.setData({
            isRebind: t,
            msg: n
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onGetPhoneNumber: function(e) {
		
        var t = this;
		var m = wx.getStorageSync("settings");
      	console.log(m.session_key);
	  
        e.errMsg ? o.toast(e.errMsg) : e.detail.encryptedData && s.userBindWechatMobile({
			session_key: m.session_key,
            data: e.detail.encryptedData,
            iv: e.detail.iv
        }, function(e) {
            n.globalData.userInfo.Mobile = e, t.setData({
                success: !0
            });
        });
    },
    onSwitchOtherTap: function() {
        this.setData({
            inputMobile: !0
        });
    },
    onBackSwitchTap: function() {
        this.setData({
            inputMobile: !1
        });
    },
    onInputNextTap: function() {
        this.setData({
            dialogType: "smsCode"
        });
    },
    onMobileInput: function(e) {
        this.setData({
            mobile: e.detail.value
        });
    },
    onSmsCodeInput: function(e) {
        var t = this, o = e.detail.value;
        i.splice(0, i.length);
        for (var a = 0; a < o.length; ++a) i.push(o.charAt(a));
        4 == o.length ? (t.setData({
            smsCode: i,
            smsCodeInputFocus: !1
        }), setTimeout(function() {
            s.userBindMobile({
                mobile: t.data.mobile,
                smsCode: o
            }, function() {
                n.globalData.userInfo.Mobile = t.data.mobile, t.setData({
                    dialogType: "none",
                    success: !0
                });
            });
        }, 300)) : t.setData({
            smsCode: i
        });
    },
    onSmsCodeBlur: function() {
        this.setData({
            smsCodeInputFocus: !1
        });
    },
    onSmsCodeTap: function() {
        this.data.smsCodeInputFocus || this.setData({
            smsCodeInputFocus: !0
        });
    },
    onDialogClose: function(e) {
        this.setData({
            dialogType: "none",
            smsCodeInputFocus: !1
        });
    }
});