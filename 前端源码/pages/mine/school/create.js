var o = getApp(), e = (o.require("utils/util.js"), o.require("utils/api.js"), o.require("utils/onfire.js"), 
[]), n = [];

Page({
    data: {
        provinces: e,
        provinceIndex: 0,
        cities: n,
        cityIndex: 0,
        school_name: "",
        school_province: 0,
        school_provinceName: null,
        school_city: 0,
        school_cityName: null
    },
    onLoad: function(o) {},
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onNameBlur: function(o) {
        this.setData({
            school_name: o.detail.value
        });
    },
    onProvinceChange: function(o) {
        var n = e[o.detail.value];
        this.setData({
            provinceIndex: o.detail.value,
            school_province: n.Id,
            school_provinceName: n.Name
        });
    },
    onCityChange: function(o) {
        var e = n[o.detail.value];
        this.setData({
            cityIndex: o.detail.value,
            school_city: e.Id,
            school_cityName: e.Name
        });
    }
});