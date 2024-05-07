var t = require("../../../utils/service/api-service.js"), 
i = require("../../../utils/common-util.js"), 
e = getApp(),
u = (e.require("utils/util.js"), e.require("utils/api.js"));

Page({
    data: {
        catList: [],
        multiArray: [ [], [] ],
        multiIndex: [ 0, 23 ],
        selectedBusi: {},
        reqInit: !1,
        bindFlag: !1,
        notifyFlag: !1,
        wxImg: "",
        agentDesc: "",
        focusDesc: "无法收到消息，请确保关注公众号【点击查看】"
    },
    onLoad: function() {
        for (var t = [], i = [], e = 0; e <= 23; e++) e <= 9 ? (t.push("0" + e + ":00"), 
        0 != e && i.push("0" + e + ":00")) : (t.push(e + ":00"), i.push(e + ":00"));
        i.push("23:59");
        var a = [ t, i ];
        this.setData({
            multiArray: a,
            agentDesc: "微信"
        });
    },
    onShow: function() {
        this.getNotifyInfo();
    },
    selectItem: function(t) {
        var i = t.currentTarget.dataset.id, e = this.data.selectedBusi;
        console.log(i);
        console.log(e);
        e[i] = !e[i], this.setData({
            selectedBusi: e
        });
    },
    switchChange: function(t) {
        this.setData({
            notifyFlag: t.detail.value
        });
    },
    bindMultiPickerChange: function(t) {
        this.setData({
            multiIndex: t.detail.value
        });
    },


    rebuildType: function(t) {
        for (var a = {
            list: [],
            catMap: {}
        }, n = {}, s = e.globalData.appVersion, o = t.length, u = 0; u < o; u++) {
            n[t[u].id] = t[u].checked;


            console.log('t[u].ver');
            console.log(t[u]);

            var l = i.tools.versionCompare(s, t[u].ver);
            t[u].show = l >= 0;
        }

        console.log(n);

        return a.list = t, a.catMap = n, a;
    },



    getNotifyInfo: function() {
        var e = this;
        u.getNotifyInfo(function(t) {
            if (0 == t.code) {
                var a = t.result, n = 1 == a.notifyFlag, s = [ a.notifyFrom, a.notifyEnd ], o = a.catList, u = e.rebuildType(o);

              console.log('==u.catMap===');
              console.log(u.catMap);

                e.setData({
                    catList: u.list,
                    reqInit: !0,
                    wxImg: a.wxImg,
                    multiIndex: s,
                    notifyFlag: n,
                    bindFlag: a.bindFlag,
                    selectedBusi: u.catMap
                });
            } else i.dialog.alertMsg(t.message);
        }, function(t) {
            i.dialog.alertMsg("获取数据失败，请重试");
        });
    },
    previewImage: function(t) {
        var i = [ this.data.wxImg ];

        console.log(i);

        wx.previewImage({
            current: "",
            urls: i
        });
    },
    submitNotify: function() {
        var e = [], a = this.data.selectedBusi;
        for (var n in a) a[n] && e.push(n);
        var s = this.data.multiArray[0][this.data.multiIndex[0]], o = this.data.multiArray[1][this.data.multiIndex[1]];
        if (parseInt(o.replace(":", ""), 10) <= parseInt(s.replace(":", ""), 10)) i.dialog.alertMsg("开始时间不能大于等于结束时间"); else {
            var z = {
                ids: e,
                notifyFlag: this.data.notifyFlag ? 1 : 0,
                notifyFrom: s,
                notifyEnd: o
            };
          console.log('==submitNotifyuu===');
          console.log(z);

            u.submitNotify(z, function(t) {
                i.dialog.alertMsg(t.message);
            }, function(t) {});
        }
    },
    trySaveImg: function() {
        var t = this;
        wx.getSetting({
            success: function(e) {
                e.authSetting["scope.writePhotosAlbum"] ? t.saveImg() : wx.authorize({
                    scope: "scope.writePhotosAlbum",
                    success: function() {
                        t.saveImg();
                    },
                    fail: function() {
                        var t = this;
                        i.dialog.confirm({
                            title: "获取权限失败",
                            content: "是否打开设置页，允许小程序保存图片到你的相册",
                            success: function(i) {
                                i.confirm && wx.openSetting({
                                    success: function(i) {
                                        i.authSetting["scope.writePhotosAlbum"] && setTimeout(function() {
                                            t.saveImg();
                                        }, 200);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    },
    saveImg: function() {
        i.dialog.showLoading({
            title: "正在保存中...",
            mask: !0
        }), t.downFileInfo(this.data.wxImg, function(t) {
            wx.saveImageToPhotosAlbum({
                filePath: t.tempFilePath,
                success: function(t) {
                    i.dialog.hideLoading(), i.dialog.toast({
                        title: "保存成功",
                        icon: "success",
                        duration: 2e3,
                        mask: !0
                    });
                },
                fail: function(t) {
                    i.dialog.hideLoading(), i.dialog.toast({
                        title: "保存失败",
                        icon: "none",
                        duration: 2e3,
                        mask: !0
                    });
                }
            });
        }, function(t) {
            i.dialog.toast({
                title: "下载图片失败",
                icon: "none",
                duration: 2e3,
                mask: !0
            });
        });
    },
    gotoWx: function() {
        u.getNotifyUrl(function(t) {
            if (0 == t.code) {
                var e = t.result, a = "../../common/content/web?url=" + encodeURIComponent(e);
                wx.navigateTo({
                    url: a
                });
            } else i.dialog.alertMsg(t.message);
        }, function(t) {
            i.dialog.alertMsg("获取数据失败，请重试");
        });
    }
});