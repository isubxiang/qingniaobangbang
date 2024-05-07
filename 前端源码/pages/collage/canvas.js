var _data;

function _defineProperty(t, e, o) {
    return e in t ? Object.defineProperty(t, e, {
        value: o,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[e] = o, t;
}

var app = getApp(), util = require("../../utils/util.js"), siteinfo = require("../../siteinfo.js");

Page({
    data: (_data = {
        img: "../../images/gobg.png",
        wechat: "../../images/wechat.png",
        quan: "../../images/quan.png",
        code: "E7AI98",
        inputValue: "",
        maskHidden: !1,
        name: "",
        touxiang: ""
    }, _defineProperty(_data, "code", "E7A93C"), _defineProperty(_data, "jjz", !0), 
    _data),
    bindKeyInput: function(t) {
        this.setData({
            inputValue: t.detail.value
        });
    },
    btnclick: function() {
        var t = this.data.inputValue;
        wx.showToast({
            title: t,
            icon: "none",
            duration: 2e3
        });
    },
    onLoad: function(a) {
        app.setNavigationBarColor(this), wx.setNavigationBarTitle({
            title: "海报"
        });
        var n = this, t = wx.getStorageSync("users").id, i = siteinfo.siteroot.slice(0, siteinfo.siteroot.length - 14);
        console.log(t, a, i), app.util.request({
            url: "app/Running/GoodsInfo",
            cachetime: "0",
            data: {
                goods_id: a.id
            },
            success: function(t) {

                console.log(t.data), 
                  console.log("downloadFile"), 
                console.log(t.data.goods.logo), 


                t.data.goods.end_time = util.formatDate(t.data.goods.end_time), 
                n.setData({
                    url: a.url,
                    QgGoodInfo: t.data.goods
                }), wx.downloadFile({
                    url:  t.data.goods.logo,
                    success: function(t) {
                        console.log(t), n.setData({
                            qglogo: t.tempFilePath
                        });
                    }
                }), app.util.request({
                    url: "app/Running/StoreInfo",
                    cachetime: "0",
                    data: {
                        id: t.data.goods.store_id
                    },
                    success: function(t) {
                        console.log(t.data);
                        var e = t.data.store[0], o =  e.logo;
                        app.util.request({
                            url: "app/Running/GoodsCode",
                            cachetime: "0",
                            data: {
                                goods_id: a.id
                            },
                            success: function(t) {
                                console.log(t);
                                var e = t.data;
                                console.log("商家的logo", o, "小程序码logo1",e), wx.downloadFile({
                                    url: o,
                                    success: function(t) {
                                        console.log(t), n.setData({
                                            logo: t.tempFilePath
                                        }), wx.downloadFile({
                                            url: e,
                                            success: function(t) {
                                                console.log(t), n.setData({
                                                    logo1: t.tempFilePath
                                                }), n.ctx();
                                            }
                                        });
                                    }
                                });
                            }
                        }), n.setData({
                            sjinfo: t.data.store[0],
                            score: Number(t.data.score).toFixed(1)
                        });
                    }
                });
            }
        }), wx.getSystemInfo({
            success: function(t) {
                n.setData({
                    width: t.windowWidth,
                    height: t.windowHeight
                });
            }
        });
    },
    ctx: function(t) {
        var e = this, o = e.data, a = (o.width, o.height, wx.createCanvasContext("ctx"));
        a.drawImage(o.logo1, 0, 0, 150, 150), a.save(), a.beginPath(), a.arc(75, 75, 35, 0, 2 * Math.PI), 
        a.clip(), a.drawImage(o.logo, 35, 35, 75, 75), a.restore(), a.draw(), setTimeout(function(t) {
            wx.canvasToTempFilePath({
                x: 0,
                y: 0,
                width: 150,
                height: 150,
                canvasId: "ctx",
                success: function(t) {
                    console.log(t.tempFilePath), e.setData({
                        xcxm: t.tempFilePath
                    }), e.formSubmit();
                }
            });
        }, 500);
    },
    createNewImg: function() {
        var o = this, t = wx.createCanvasContext("mycanvas");
        t.setFillStyle("#fff"), t.fillRect(0, 0, 375, 667);
        var e = o.data.qglogo, a = o.data.xcxm;
        console.log(e, a, "qglogo");
        var n = o.data.sjinfo.store_name, i = "￥" + o.data.QgGoodInfo.pt_price, s = "￥" + o.data.QgGoodInfo.y_price, l = o.data.QgGoodInfo.pt_price + "元," + o.data.QgGoodInfo.people + "人拼团抢" + o.data.QgGoodInfo.name, c = "拼团结束日期:" + o.data.QgGoodInfo.end_time;
        t.setFontSize(24), t.setFillStyle("#000000"), t.setTextAlign("center"), t.fillText(n, 187.5, 50), 
        t.stroke(), t.drawImage(e, 40, 80, 295, 175), t.setFontSize(18), t.setFillStyle("#000000"), 
        t.setTextAlign("center"), t.fillText(l, 187.5, 290), t.setFontSize(16), t.setFillStyle("#999"), 
        t.setTextAlign("center"), t.fillText("拼团价", 80, 340), t.setFontSize(16), t.setFillStyle(o.data.color), 
        t.setTextAlign("center"), t.fillText(i, 135, 340), t.setFontSize(16), t.setFillStyle("#999"), 
        t.setTextAlign("center"), t.fillText("原价", 220, 340), t.setFontSize(16), t.setFillStyle("#999"), 
        t.setTextAlign("center"), t.fillText(s, 275, 340), t.stroke(), t.setStrokeStyle("#999"), 
        t.moveTo(200, 335), t.lineTo(315, 335), t.stroke(), t.setTextBaseline("middle"), 
        t.setStrokeStyle("#999"), t.setLineDash([ 3, 5 ], 1), t.beginPath(), t.moveTo(40, 310), 
        t.lineTo(335, 310), t.stroke(), t.drawImage(o.data.xcxm, 125, 370, 125, 125), t.setFillStyle("#000"), 
        t.setFontSize(16), t.setTextAlign("center"), t.fillText("长按二维码识别小程序参与拼团", 187.5, 529), 
        t.setFillStyle("#333"), t.setFontSize(13), t.setTextAlign("center"), t.fillText(c, 187.5, 565), 
        t.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "mycanvas",
                success: function(t) {
                    var e = t.tempFilePath;
                    o.setData({
                        imagePath: e,
                        canvasHidden: !0,
                        jjz: !1
                    });
                },
                fail: function(t) {
                    console.log(t);
                }
            });
        }, 200);
    },
    baocun: function() {
        wx.saveImageToPhotosAlbum({
            filePath: this.data.imagePath,
            success: function(t) {
                wx.showModal({
                    content: "图片已保存到相册，赶紧晒一下吧~",
                    showCancel: !1,
                    confirmText: "好的",
                    confirmColor: "#333",
                    success: function(t) {
                        wx.navigateBack({});
                    }
                });
            }
        });
    },
    formSubmit: function(t) {
        this.setData({
            maskHidden: !1
        }), wx.hideToast(), this.createNewImg(), this.setData({
            maskHidden: !0
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});