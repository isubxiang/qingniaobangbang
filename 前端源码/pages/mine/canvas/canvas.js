var _data;

function _defineProperty(t, e, a) {
    return e in t ? Object.defineProperty(t, e, {
        value: a,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[e] = a, t;
}

var app = getApp();

Page({
 

    data: (_data = {
        img: "../image/gobg.png",
        wechat: "../image/wechat.png",
        quan: "../image/quan.png",
        code: "E7AI98",
        inputValue: "",
        maskHidden: !1,
        name: "",
        touxiang: ""
    }, 
           
      _defineProperty(_data, "code", "E7A93C"), _defineProperty(_data, "jjz", !0), 
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
    onLoad: function(e) {
        var a = this;
        var o = this;

      wx.setNavigationBarColor({
        frontColor: '#ffffff',
        backgroundColor: '#2fbdaa',
        animation: {
          duration: 400,
          timingFunc: 'easeIn'
        }
      })
      
        wx.setNavigationBarTitle({
            title: "海报中心"
        });
    
      var settings = wx.getStorageSync("settings");

      console.log(settings.SessionId);

      app.util.request({

        url: "app/Running/getFxCode",
        cachetime: "0",
        data: {
          fuid: settings.SessionId,
        },


         success: function (t) {

           console.log(t.data);

            var a = t.data.data.poster;
            var e = t.data.data.code;
            var text1 = t.data.data.text1;
            var text2 = t.data.data.text2;

            console.log("海报", a, "小程序码", e), 
            
            wx.downloadFile({
              url: a,
              success: function (t) {
                console.log(t), 
                o.setData({
                  logo: t.tempFilePath,
                  text1: text1,
                  text2: text2,
                }), wx.downloadFile({
                  url: e,
                  success: function (t) {
                    console.log(t), 
                    o.setData({
                      logo1: t.tempFilePath,
                      text1: text1,
                      text2: text2,
                    }), o.ctx();
                  }
                });
              }
            });
          }
        }), 
        
        wx.getUserInfo({
            success: function(t) {
                console.log(t.userInfo, "huoqudao le "), a.setData({
                    name: t.userInfo.nickName
                }), wx.downloadFile({
                    url: t.userInfo.avatarUrl,
                    success: function(t) {
                        200 === t.statusCode && (console.log(t, "reererererer"), o.setData({
                            touxiang: t.tempFilePath
                        }));
                    }
                });
            }
        }), 


        wx.getSystemInfo({
            success: function(t) {
                o.setData({
                    width: t.windowWidth,
                    height: t.windowHeight
                });
            }
        });


    },
    ctx: function(t) {
        var e = this, a = e.data, o = (a.width, a.height, wx.createCanvasContext("ctx"));
        o.drawImage(a.logo1, 0, 0, 150, 150), o.save(), o.beginPath(), o.arc(75, 75, 35, 0, 2 * Math.PI), 
        o.clip(), o.drawImage(a.logo, 35, 35, 75, 75), o.restore(), o.draw(), setTimeout(function(t) {
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
        var a = this, t = wx.createCanvasContext("mycanvas");
        t.setFillStyle("#fff"), t.fillRect(0, 0, 375, 667);
        var e = a.data.logo, o = a.data.xcxm;
        console.log(e, o, "qglogo"), t.drawImage(e, 12.5, 16, 350, 350);

        console.log('==a.data=='); 
        console.log(a.data); 

        var n = a.data.text1, i = "", s = [];


        t.setFontSize(22), t.setFillStyle("#000000");
        for (var l = 0; l < n.length; l++) t.measureText(i).width < 250 ? i += n[l] : (l--, 
        s.push(i), i = "");
        if (s.push(i), 2 < s.length) {
            var c = s.slice(0, 2), u = c[1], r = "", d = [];
            for (l = 0; l < u.length && t.measureText(r).width < 220; l++) r += u[l];
            d.push(r);
            var g = d[0] + "...";
            c.splice(1, 1, g), s = c;
        }
        for (var f = 0; f < s.length; f++) t.fillText(s[f], 25, 490 + 30 * f, 180);
        t.setStrokeStyle("#999"), t.setLineDash([ 3, 5 ], 1), t.beginPath(), t.moveTo(20, 390), 
        t.lineTo(355, 390), t.stroke(), t.drawImage(a.data.xcxm, 230, 450, 125, 125), t.setFillStyle("#666"), 
        t.setFontSize(16), t.fillText("长按识别小程序", 240, 609), t.setFillStyle("#FF3030"), t.setFontSize(24), 
        t.fillText(a.data.text2, 20, 609), t.drawImage(a.data.touxiang, 20, 406, 30, 30), 
        t.setFillStyle("#666"), t.setFontSize(14), t.fillText(a.data.name + "为你推荐", 60, 425), 
        t.draw(), setTimeout(function() {
            wx.canvasToTempFilePath({
                canvasId: "mycanvas",
                success: function(t) {
                    var e = t.tempFilePath;
                    a.setData({
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