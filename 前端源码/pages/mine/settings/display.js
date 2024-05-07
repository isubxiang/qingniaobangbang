var e = getApp(), t = e.require("utils/enums.js");

Page({
    data: {
        orderListDefault: ""
    },
    onLoad: function(a) {
        var o = t.OrderListTypes, n = o[o.length - 1], r = e.getSettings();
        r.orderListDefaultDelivery ? n = o[r.orderListDefaultDelivery - 1] : e.globalData.userInfo && e.globalData.userInfo.Type > 0 && ((1 | e.globalData.userInfo.Type) == e.globalData.userInfo.Type ? n = o[0] : (2 | e.globalData.userInfo.Type) == e.globalData.userInfo.Type && (n = o[1])), 
        this.setData({
            orderListDefault: n.Name
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onOrderListDefaultTap: function(a) {
        var o = this;
        wx.showActionSheet({
            itemList: t.getNames(t.OrderListTypes),
            success: function(a) {
                var n = t.OrderListTypes[a.tapIndex], r = e.getSettings();
                n.Value != r.orderListDefaultDelivery && (r.orderListDefaultDelivery = n.Value, 
                r.save(), o.setData({
                    orderListDefault: n.Name
                }));
            }
        });
    }
});