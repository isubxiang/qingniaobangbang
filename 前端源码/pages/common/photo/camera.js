var t = getApp(), e = t.require("utils/onfire.js");

Page({
    data: {
        devicePosition: "back",
        hasPhotoed: !1,
        screenWidth: t.globalData.systemInfo.windowWidth,
        screenHeight: t.globalData.systemInfo.windowHeight,
        imageSrc: null,
        photoType: "idcard"
    },
    onLoad: function(t) {
        t.src && t.src.length ? this.setData({
            hasPhotoed: !0,
            imageSrc: t.src,
            photoType: t.type
        }) : this.setData({
            photoType: t.type
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onSwitch: function() {
        var t = this;
        t.setData({
            devicePosition: "back" == t.data.devicePosition ? "front" : "back"
        });
    },
    onPhoto: function() {
        var t = this;
        wx.createCameraContext().takePhoto({
            success: function(e) {
                t.data.imageSrc && t.data.imageSrc.indexOf("http://store/") >= 0 && wx.removeSavedFile({
                    filePath: t.data.imageSrc,
                    complete: function(t) {
                        console.log(t && t.errMsg);
                    }
                }), t.setData({
                    imageSrc: e.tempImagePath,
                    hasPhotoed: !0
                });
            }
        });
    },
    onConfirm: function() {


      //console.log('点击拍照');

        var t = this, a = wx.createCanvasContext("canvasId");
        a.drawImage(t.data.imageSrc, 0, 0, t.data.screenWidth, t.data.screenHeight), a.draw(), 
        wx.canvasToTempFilePath({
            canvasId: "canvasId",
            success: function(t) {

               //console.log(t);
           
                wx.saveFile({
                    tempFilePath: t.tempFilePath,
                     
                    success: function(t) {
                      console.log(t.savedFilePath);
                      e.fire("CardPhoto", t.savedFilePath), wx.navigateBack();
                    },

                    fail: function (n) {  
                      console.log(n);                  
                      wx.showToast({
                          title: '接口调用失败'+n.errMsg,
                          duration:2000,
                          mask: true,
                          icon: 'success',
                          success: function () {
                            e.fire("CardPhoto", t.savedFilePath), wx.navigateBack();
                          },//接口调用成功
                      })
                    }


                });

             
            }
        });
    },
    onRevert: function() {
        this.setData({
            imageSrc: null,
            hasPhotoed: !1
        });
    }
});