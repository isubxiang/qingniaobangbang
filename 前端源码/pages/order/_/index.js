var a = getApp(), t = a.require("utils/onfire.js"), e = a.require("utils/util.js"), n = a.require("utils/api.js"), i = a.require("utils/enums.js"), r = !1, o = 0, s = [ [], [], [] ], d = !1, l = 0;

Page({
    data: {
        tabAnimation: null,
        tabIndex: 0,
        isDelivery: !1,
        isMerchant: !1,
        orderListTypes: i.OrderListTypes,
        errandTypes: i.ErrandTypes,
        orderStatus: i.OrderStatus,
        orders: s,
        lastPage: !1
    },
    onLoad: function(e) {
        var n = this, r = !1, s = !1, l = 0;
        (l = a.globalData.userInfo ? a.globalData.userInfo.Type : (g = a.getSettings()).UserType) && (r = (1 | l) == l, 
        s = (2 | l) == l);
        var u = i.OrderListTypes.length - 1, g = a.getSettings();
        g.orderListDefaultDelivery ? u = g.orderListDefaultDelivery - 1 : s ? u = 0 : r && (u = 1), 

        console.log('====onLoad===');
        console.log(i.OrderListTypes);

        console.log(r);

        var r = 1;//临时先这样用

        s || r ? (n.setData({
            isDelivery: r,
            isMerchant: s
        }), n.onTabChanged({
            tabIndex: u,
            animate: !1
        })) : (n.setData({
            tabIndex: u,
            isDelivery: r,
            isMerchant: s
        }), n.loadData(e)), t.on("refreshHomeOrders", function() {
            n.loadData({
                pageIndex: o
            }), d = !0;
        });
    },
    onReady: function() {
        r = !0;
    },
    onShow: function() {
      console.log("==============onShow1111====================");
        r && (l ? d = !0 : (o = 0, this.loadData({
            pageIndex: o
        }))), t.fire("hideIndexDialog");
    },
    onHide: function() {},
    onUnload: function() {},
    onPageScroll: function(a) {
        !(l = a.scrollTop) && d && (o = 0, this.loadData({
            pageIndex: o
        }));
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.data.lastPage || this.loadData({
            pageIndex: ++o
        });
    },
    onShareAppMessage: function () {
      a.onAppShareAppMessage('');
    },
    loadData: function(t) {
        var i = this, r = i.data.tabIndex, o = e.extend({
            role: 3 - r
        }, t);
        n.orderList(o, function(t) {
            var e = i.data.tabIndex;
            o.pageIndex || s[e].splice(0, s[e].length);
            for (var n in t) s[e].push(t[n]);
            i.setData({
                lastPage: t.length < a.pageSize,
                orders: s
            });
        }), d = !1;
    },
    onPaymentTap: function(a) {
        n.orderPayment({
            id: a.currentTarget.dataset.id
        }, function(t) {
            wx.navigateTo({
                url: "info?id=" + a.currentTarget.dataset.id
            });
        });
    },

    onPing: function (e) {
      console.log('order-onPing', e);
      var uid = e.target.dataset.uid
      wx.navigateTo({
        url: "../../errand/_/ping?uid=" + uid
      });
    },


    onAgainTap: function() {},
    onTabChanged: function(t) {
        var e = this, n = parseInt(void 0 !== t.tabIndex ? t.tabIndex : t.currentTarget.dataset.index);
        if (e.data.tabIndex != n) {
            var i = wx.createAnimation({
                duration: !1 === t.animate ? 0 : 150,
                timingFunction: "linear",
                delay: 0
            });
            e.animation = i, o = 0, e.loadData({
                role: 3 - n,
                pageIndex: o
            });
            var r = a.globalData.systemInfo.windowWidth;
            1 == n ? (i.translateX(e.data.isMerchant && e.data.isDelivery ? r / 3 : 0).step(), 
            e.setData({
                tabAnimation: i.export(),
                tabIndex: n
            })) : 2 == n ? (i.translateX(e.data.isMerchant && e.data.isDelivery ? 2 * r / 3 : r / 2).step(), 
            e.setData({
                tabAnimation: i.export(),
                tabIndex: n
            })) : (i.translateX(0).step(), e.setData({
                tabAnimation: i.export(),
                tabIndex: n
            }));
        }
    }
});