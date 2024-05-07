var e = getApp(), a = e.require("utils/util.js"), t = e.require("utils/api.js"), n = e.require("utils/onfire.js"), r = e.require("libs/bmap-wx.js"), s = null, d = null, i = null, o = null, u = null, l = null, c = null, g = [];

Page({
    data: {
        screenHeight: 0,
        showUser: !1,
        showCommon: !1,
        showNearest: !1,
        locationInfo: null,
        markers: g,
        tabIndex: 0,
        selectedIndex: -1,
        queryMode: !1,
        queryString: null,
        queryAddress: null,
        addresses: []
    },
    onLoad: function(t) {
        var g = this;
        s = [], d = [], i = [], t.tabs || (t.tabs = "user,common,nearest"), g.setData({
            screenHeight: e.globalData.systemInfo.windowHeight,
            tabIndex: t.index || 0,
            showUser: !!t.tabs && t.tabs.indexOf("user") >= 0,
            showCommon: !!t.tabs && t.tabs.indexOf("common") >= 0,
            showNearest: !!t.tabs && t.tabs.indexOf("nearest") >= 0
        }), t.longitude && t.latitude && t.address && (l = {
            longitude: t.longitude,
            latitude: t.latitude,
            address: t.address
        }), 

        console.log('--e.globalData--');
        console.log(e.globalData);
        console.log(e.globalData.locationInfo);


        e.globalData.locationInfo ? (g.setData({
            locationInfo: e.globalData.locationInfo
        }), g.loadData(t)) : (g.loadData(t), n.on("location", function(e) {

            console.log("location");
            console.log(e);
            
            g.setData({
                locationInfo: e
            });
        })), (c = new r.BMapWX({
            ak: e.baiduMapAK
        })).regeocoding({
            fail: function(e) {
                a.toast(e.errMsg);
            },
            success: function(e) {
                o = {
                    Id: -1,
                    Name: "当前位置",
                    Address: e.wxMarkerData[0].address,
                    Longitude: e.wxMarkerData[0].longitude,
                    Latitude: e.wxMarkerData[0].longitude
                };
            }
        }), n.on("addressChange", function(e) {
            var n = a.extend({
                refresh: !0
            }, t, {
                index: g.data.tabIndex || 0
            });
            g.loadData(n);
        }), n.on("addressSearch", function(e) {
            g.setData({
                queryMode: !0,
                queryString: e.Query
            }), c.regeocoding({
                location: e.Address.Latitude + "," + e.Address.Longitude,
                fail: function(e) {
                    a.toast(e.errMsg);
                },
                success: function(a) {
                    u = {
                        Id: 0,
                        Name: e.Address.Name,
                        Address: a.wxMarkerData[0].address,
                        Longitude: a.wxMarkerData[0].longitude,
                        Latitude: a.wxMarkerData[0].latitude,
                        IsOutSide: e.IsOutSide
                    }, g.setData({
                        queryAddress: u
                    }), g.updateMarkers([ u ]);
                }
            });
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {
        n.un("addressChange"), n.un("addressSearch");
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    loadData: function(e) {
        var n = this;
        0 == e.index ? d.length ? n.updateMarkers(d) : t.commonAddressList(function(e) {
            d.splice(0, d.length);
            for (var a in e) d.push(e[a]);
            n.updateMarkers(d);
        }) : 2 == e.index ? i.length ? n.updateMarkers(i) : (i.push(o), c.search({
            query: "生活服务",
            fail: function(e) {
                a.toast(e.errMsg);
            },
            success: function(e) {
                for (var a in e.wxMarkerData) {
                    var t = e.wxMarkerData[a];
                    i.push({
                        Id: t.id,
                        Name: t.title,
                        Address: t.address,
                        Longitude: t.longitude,
                        Latitude: t.latitude
                    });
                }
                n.updateMarkers(i);
            }
        })) : !s.length || e.refresh ? t.userAddressList(function(e) {
            s.splice(0, s.length);
            for (var a in e) s.push(e[a]);
            n.updateMarkers(s);
        }) : n.updateMarkers(s);
    },
    updateMarkers: function(e) {
        var a = this;
        g.splice(0, g.length);
        var t = -1;
        for (var n in e) {
            var r = e[n];
            g.push({
                id: r.Id,
                width: 32,
                height: 32,
                latitude: r.Latitude,
                longitude: r.Longitude,
                iconPath: "/assets/img/marker_blue.png",
                iconTapPath: "/assets/img/marker_blue.png"
            }), l && l.longitude == r.Longitude && l.latitude == r.Latitude && l.address == r.Address && (t = n);
        }
        a.setData({
            addresses: e,
            markers: g,
            selectedIndex: t
        });
    },
    onTabTap: function(e) {
        var a = this, t = e.currentTarget.dataset.index;
        t != a.data.tabIndex && (a.loadData({
            index: t
        }), a.setData({
            tabIndex: t
        }));
    },
    onItemTap: function(e) {
        var t = this, r = e.currentTarget.dataset.index, o = null;
        o = 1 == t.data.tabIndex ? a.extend({
            self: !0
        }, s[r]) : 0 == t.data.tabIndex ? d[r] : i[r], n.fire("selectAddress", o), wx.navigateBack();
    },
    onMakerTap: function(e) {
        for (var a = this, t = e.markerId, n = -1, r = 0; r < g.length; r++) g[r].id == t ? (n = r, 
        g[r].iconPath = "/assets/img/marker_red.png") : g[r].iconPath = "/assets/img/marker_blue.png";
        a.setData({
            selectedIndex: n,
            markers: g
        });
    },
    onQueryTap: function() {
        var e = this;
        wx.navigateTo({
            url: "search" + (e.data.queryString && e.data.queryString.length ? "?query=" + encodeURIComponent(e.data.queryString) : "")
        });
    },
    onQuerySelectTap: function(e) {
        t.userSaveFormId({
            formId: e.detail.formId
        }), n.fire("selectAddress", u), wx.navigateBack();
    },
    onQueryCancelTap: function() {
        var e = this;
        0 == e.data.tabIndex ? e.updateMarkers(d) : e.updateMarkers(i), this.setData({
            queryMode: !1,
            queryString: null
        });
    },
    onItemEditTap: function(e) {
        var a = e.currentTarget.dataset.index;
        wx.navigateTo({
            url: "../../mine/address/edit?id=" + s[a].Id
        });
    },
    onAddAddressTap: function(e) {
        t.userSaveFormId({
            formId: e.detail.formId
        }), wx.navigateTo({
            url: "../../mine/address/edit"
        });
    }
});