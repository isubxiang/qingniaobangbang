var t = getApp(), a = t.require("utils/util.js"), e = t.require("utils/api.js"), n = t.require("utils/onfire.js"), o = t.require("libs/bmap-wx.js"), i = null, l = [ "学校附近", "全城" ], u = 0, s = "", r = [];

Page({
    data: {
        queryString: "",
        regions: l,
        regionIndex: u,
        inputValue: "",
        model: r
    },
    onLoad: function(a) {
        if (i = new o.BMapWX({
            ak: t.baiduMapAK
        }), a.query) {
            var e = decodeURIComponent(a.query);
            this.setData({
                queryString: e,
                inputValue: e
            });
        } else this.loadData();
    },
    onReady: function() {
        t.globalData.schoolInfo && t.globalData.schoolInfo.Region && (l[1] = t.globalData.schoolInfo.Region + "市内", 
        //console.log(l);
        this.setData({
            regions: l
        }));
    },
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    loadData: function(e) {
        var n = this;
        s = e, e && e.length ? i.suggestion({
            query: (0 == u && t.globalData.schoolInfo ? t.globalData.schoolInfo.Name + " " : "") + s,
            region: t.globalData.schoolInfo && t.globalData.schoolInfo.Region || "",
            city_limit: !0,
            fail: function(t) {
                a.toast(t.errMsg);
            },
            success: function(t) {
                r.splice(0, r.length);
                for (var a = 0; a < t.result.length; a++) {
                    var e = t.result[a];
                    
                    e.location && r.push({
                        Name: e.name,
                        Longitude: e.location.lng,
                        Latitude: e.location.lat
                    });
                }
                n.setData({
                    model: r
                });
            }
        }) : (r.splice(0, r.length), n.setData({
            model: r
        }));
    },
    onRegionTap: function(t) {
        var a = this;
        wx.showActionSheet({
            itemList: l,
            success: function(t) {
                u = t.tapIndex, a.setData({
                    regionIndex: t.tapIndex
                }), s && s.length && a.loadData(s);
            }
        });
    },
    onQueryInput: function(t) {
        this.setData({
            queryString: t.detail.value,
            inputValue: t.detail.value
        }), this.loadData(t.detail.value);
    },
    onItemTap: function(t) {
        var a = t.currentTarget.dataset.index;
        n.fire("addressSearch", {
            Query: s,
            IsOutSide: u > 0,
            Address: r[a]
        }), wx.navigateBack();
    },
    onInputBlur: function(t) {
        this.setData({
            inputValue: t.detail.value
        });
    },
    onFormSubmit: function(t) {
        e.userSaveFormId({
            formId: t.detail.formId
        });
        var a = this;
        n.fire("selectAddress", {
            Name: a.data.inputValue,
            IsOutSide: u > 0
        }), wx.navigateBack({
            delta: 2
        });
    }
});