var t = require("../../../utils/service/api-service.js"), 
a = require("../../../utils/common-util.js"),
i = getApp(),
u = (i.require("utils/util.js"), i.require("utils/api.js"));


Page({
    data: {
        negFlag: 0,
        multiArray: [ [], [] ],
        multiIndex: [ 0, 0 ],
        currentMonth: "",
        listItem: [],
        totalStr: "0.00",
        reqInit: !1
    },
    onLoad: function(t) {
        var a = t.month, e = a.substring(0, 4), n = a.substr(4), i = new Date().getFullYear(), r = i - 1, s = [ 0, 0 ];
        e == r + "" && (s[0] = 1), s[1] = parseInt(n, 10) - 1;
        var l = [ [ i, r ], [ "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12" ] ];
        this.setData({
            currentMonth: t.month,
            multiArray: l,
            multiIndex: s
        }), this.getMonthSta();
    },
    catClick: function(t) {
        var a = t.currentTarget.dataset.state;
        this.setData({
            negFlag: a
        }), this.getMonthSta();
    },
    bindMultiPickerChange: function(t) {
        var a = this.data.multiArray[0][t.detail.value[0]] + "" + this.data.multiArray[1][t.detail.value[1]];
        this.setData({
            multiIndex: t.detail.value,
            currentMonth: a
        }), this.getMonthSta();
    },
    getMonthSta: function() {
        var e = this, n = {
            month: e.data.currentMonth,
            negFlag: e.data.negFlag
        };
       u.getMonthSta(n, function(t) {
            var a = t.result;
            e.setData({
                totalStr: a.totalStr,
                listItem: a.list,
                reqInit: !0
            });
        }, function(t) {
            a.dialog.alert({
                title: "",
                content: "获取数据失败，请重试",
                success: function(t) {
                    e.getMonthSta();
                }
            });
        });
    }
});