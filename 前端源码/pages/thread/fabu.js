var app = getApp();

Page({
    data: {
        index: 0,
        base: !1
    },
    onLoad: function(a) {
      this.reload();
    },
    reload: function(a) {
        var t = this, e = wx.getStorageSync("System");
        console.log(e);
        var n = wx.getStorageSync("url");
        t.setData({
            url: n,
            pt_name: e.pt_name,
            System: wx.getStorageSync("System")
        }), app.util.request({
            url: "app/Running/ThreadType",
            cachetime: "0",
            success: function(a) {
                console.log(a);
                var e = a.data;
                t.setData({
                    nav: e
                });
            }
        });
    },



    formid_one: function(a) {
        console.log("搜集第一个formid"), console.log(a), app.util.request({
            url: "app/Running/SaveFormid",
            cachetime: "0",
            data: {
                user_id: wx.getStorageSync("users").id,
                form_id: a.detail.formId,
                openid: wx.getStorageSync("openid")
            },
            success: function(a) {}
        });
    },
    bindPickerChange: function(a) {
        console.log(a);
        var e = this, t = e.data.id, n = a.detail.value, o = e.data.nav[e.data.index].array[n];
        for (var i in e.data.nav[e.data.index].array) if (o == e.data.nav[e.data.index].arrays[i].name) var d = e.data.nav[e.data.index].arrays[i].id, s = e.data.nav[e.data.index].arrays[i].type_id, r = e.data.nav[e.data.index].money;
        console.log(e.data.nav[e.data.index]), wx.navigateTo({
            url: "edit?info=" + o + "&id=" + t + "&type_id=" + d + "&money=" + r + "&type2_id=" + s
        });
    },
    edit: function(a) {
        var e = this;
        console.log(a);
        var t = a.currentTarget.dataset.index, n = a.currentTarget.dataset.id, o = e.data.nav[t].money + "," + e.data.nav[t].money2, i = [], d = wx.getStorageSync("users").id;
        console.log(o), app.util.request({
            url: "app/Running/ThreadFtXz",
            cachetime: "0",
            data: {
                user_id: d
            },
            success: function(a) {
                console.log(a, d), "今天发帖次数已经超限!" == a.data ? wx.showModal({
                    title: "提示",
                    content: "今天发帖次数已经超限!"
                }) : app.util.request({
                    url: "app/Running/ThreadType2",
                    cachetime: "0",
                    data: {
                        id: n
                    },
                    success: function(a) {
                        console.log(a), 0 != a.data.length ? (a.data.map(function(a) {
                            var e;
                            e = a.name, i.push(e);
                        }), console.log(i), e.setData({
                            array: i,
                            arrays: a.data,
                            base: !0,
                            type_id: n,
                            money: o
                        })) : wx.navigateTo({
                            url: "edit?id=" + e.data.id + "&type_id=" + n + "&money=" + o + "&type2_id=0"
                        });
                    }
                });
            }
        });
    },
    cancel: function(a) {
        this.setData({
            base: !1
        });
    },
    selected: function(a) {
        var e = this, t = e.data.arrays, n = a.currentTarget.id, o = e.data.type_id, i = t[n].id, d = t[n].name, s = e.data.money;
        e.setData({
            base: !1
        }), wx.navigateTo({
            url: "edit?type2_id=" + i + "&type_id=" + o + "&money=" + s + "&info=" + d
        });
    },

    toIndex: function(){
      wx.navigateTo({
        url: "index"
      });
    },

    onReady: function() {},
    onShow: function() {
      
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});