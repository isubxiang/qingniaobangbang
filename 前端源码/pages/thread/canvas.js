var app = getApp();

Page({
    data: {},
    onLoad: function(e) {
        var o = this;

        app.setNavigationBarColor(this);

        console.log('-----onLoad-------')
        console.log(e.qr_code)

        o.setData({
            img_height: e.img_height,
            img_width: e.img_width,
            proportion: e.proportion
        }), wx.getSystemInfo({
            success: function(t) {
                o.setData({
                    width: t.windowWidth,
                    height: t.windowHeight
                });
            }
        }),
      
      
        
         app.util.request({
          url: "app/Running/ThreadSystem",
            cachetime: "0",
            success: function(t) {
                console.log(t), o.setData({
                    system: t.data
                });
            }
        }), app.util.request({
          url: "app/Running/ThreadUrl2",
            cachetime: "0",
            success: function(t) {
                console.log(t), 
                console.log('---e.qr_code----'), 
                console.log(e.qr_code), 
                
                wx.downloadFile({
                    url: e.qr_code,
                    success: function(t) {
                        console.log(t.tempFilePath);
                        var a = t.tempFilePath;
                        o.setData({
                            qr_code: a
                        }), app.util.request({
                            url: "app/Running/ThreadPostInfo",
                            cachetime: "0",
                            data: {
                                id: e.post_info_id
                            },
                            success: function(t) {
                              console.log('===PostInfo=='), 
                                console.log(t), 

                                t.data.tz.details = t.data.tz.details.replace(/[\r\n]/g, ""), 
                                "" != t.data.tz.img ? (t.data.tz.img = t.data.tz.img.split(","), 
                                o.setData({
                                    post: t.data.tz
                                }), o.logo()) : (o.setData({
                                    post: t.data.tz
                                }), o.canvas());
                            }
                        });
                    }
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadUrl",
            cachetime: "0",
            success: function(t) {
                o.setData({
                    url: t.data
                });
            }
        });
    },
    logo: function(t) {
        var e = this, a = e.data;
        console.log(a), wx.downloadFile({
            url: a.post.img[0],
            success: function(t) {
                console.log(t.tempFilePath);
                var a = t.tempFilePath;
                e.setData({
                    logo: a
                }), e.canvas();
            }
        });
    },
    canvas: function(t) {
        var a = this, e = a.data, o = e.logo, i = (e.width, e.height, e.width - 40);
        a.setData({
            text_width: i
        });
        var n = e.proportion, s = a.data.post.details.split("");
        console.log(s);
        for (var l = [], c = (wx.createCanvasContext("firstCanvas"), l = [], 0), r = s.length; c < r; c += i / 14) l.push(s.slice(c, c + i / 14));
        for (var h in console.log(l), l) l[h] = l[h].join("");
        var d = 180 + 30 * l.length;
        if (null != o) var u = 200 + 30 * l.length + i * n; else u = 200 + 30 * l.length;
        console.log(u), a.setData({
            canvas_height: u,
            text_height: d,
            row: l
        }), a.save_canvas(t);
    },
    save_canvas: function() {
        var a = this, 
        e = a.data, 
        o = e.logo, 
        i = e.width, 
        n = e.proportion, 
        s = e.width - 40, 
        l = e.canvas_height,
        c = wx.createCanvasContext("firstCanvas");

        console.log("-------------------"),

        c.setFillStyle("#fff"), c.rect(0, 0, i, l), 
        c.fill(), 
        c.drawImage(e.qr_code, i - 120, 20, 100, 100), 
        c.fillStyle = "#999", 
        c.setFontSize(14), 
        c.fillText("长按识别二维码,查看详情", 
        i - 110, 150, 80, 80), 
        c.fillStyle = "#000",
        c.setFontSize(18),
        c.fillText(a.data.post.type_name, 20, 60), 
        c.fillStyle = "#999", 
        c.setFontSize(14), 
        c.fillText(app.ormatDate(e.post.sh_time) + "发布", 20, 90), 
        c.fillText(e.post.views + "人浏览", 20, 110);

        var r = a.data.post.details;
        console.log(r);
        r.split("");
        c.setFontSize(14), 
        c.setFillStyle("#000");

        console.log("================");


        for (var h = e.row, d = 0; d < h.length; d++) console.log(h[d]), c.fillText(h[d], 20, 180 + 30 * d, s);
        null != o && c.drawImage(o, 20, 180 + 30 * d, s, s * n), c.draw();
    },
    totemp: function(t) {
        var a = this.data.width, e = this.data.canvas_height;
        wx.canvasToTempFilePath({
            x: 0,
            y: 0,
            width: a,
            height: e,
            canvasId: "firstCanvas",
            success: function(t) {
                console.log(t.tempFilePath), wx.saveImageToPhotosAlbum({
                    filePath: t.tempFilePath,
                    success: function(t) {
                        console.log(t), wx.showToast({
                            title: "保存成功"
                        }), setTimeout(function() {
                            wx.navigateBack({
                                delta: 2
                            });
                        }, 1500);
                    },
                    fail: function(t) {},
                    complete: function(t) {}
                });
            }
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});