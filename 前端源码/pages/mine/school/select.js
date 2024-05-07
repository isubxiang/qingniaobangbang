var o = getApp(), n = (o.require("utils/util.js"), o.require("utils/api.js")), t = o.require("utils/onfire.js"), e = [];

Page({
    data: {
        school_id: 0,
        school_name: null,
        model: e
    },
    onLoad: function(a) {

       

        var i = this, l = function(o) {
            n.homeNearestSchool({
                longitude: o.longitude,
                latitude: o.latitude
            }, function(o) {
                i.setData({
                    school_id: o ? o.Id : 0,
                    school_name: o ? o.Name : "没有找到学校，重新定位"
                });
            });
        };


        o.globalData.locationInfo ? l(o.globalData.locationInfo) : t.on("location", function(o) {
            l(o);
        }), n.schoolList(function(o) {
            e.splice(0, e.length);
            for (var n in o) e.push(o[n]), i.setData({
                model: e
            });
        });

        var setting = wx.getStorageSync("setting");
        var titleName = setting.titleName;

    },



    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onItemTap: function(o) {
        var n = this, a = null;
        if (o.currentTarget.dataset.id) a = {
            Id: n.data.school_id,
            Name: n.data.school_name
        }; else {
            var i = o.currentTarget.dataset.index;
            a = e[i];
        }
        t.fire("SchoolSelect", a), wx.navigateBack();
    },
    onCreateTap: function() {
        wx.showModal({
            title: "很抱歉",
            content: "暂时还不支持创建学校。\r\n请返回至主界面，在“我的”栏目中，点击“在线客服”咨询。",
            showCancel: !1
        });
    }
});