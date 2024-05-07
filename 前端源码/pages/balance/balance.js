var t = require("../../utils/service/api-service.js"),  
e = require("../../utils/onfire.js"), 
a = require("../../utils/common-util.js"), 
i = getApp(),
u = (i.require("utils/util.js"), i.require("utils/api.js"));

Page({
    data: {
        userSta: {
            fundStr: "---",
            fund: 0,
            inNumStr: "--",
            outNumStr: "---",
            list: []
        },
        scoreList: [],
        pageIndex: 1,
        loadProcess: !1,
        loadingComplete: !1,
        scrollHeight: 600,
        reqInit: !1,
        currentMonth: "",
        busiType: -1,
        multiArray: [ [], [] ],
        multiIndex: [ 0, 0 ],
        filterShow: !1
    },
    showFilter: function() {
        this.setData({
            filterShow: !this.data.filterShow
        });
    },
    closeFilter: function() {
        this.setData({
            filterShow: !1
        });
    },
    preventTouchMove: function() {},
    catClick: function(t) {
        var e = t.currentTarget.dataset.state;
        this.setData({
            busiType: e,
            pageIndex: 1
        }), this.closeFilter(), this.getFundList(!1);
    },
    monthSta: function() {
        var t = "./month/index?month=" + this.data.currentMonth;
        wx.navigateTo({
            url: t
        });
    },
    onLoad: function(t) {
        var n = a.tools.formatDate(new Date(), "yyyyMM"), s = new Date().getFullYear(), r = s - 1, o = [ 0, 0 ];
        o[1] = parseInt(n.substr(4), 10) - 1;
        var u = [ [ s, r ], [ "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12" ] ];
        this.setData({
            currentMonth: n,
            multiArray: u,
            multiIndex: o,
            scrollHeight: i.getSystemHeight() - 200
        });
        var l = this;
        this.getFundList(), this.fundSta(), e.on("drawsucc", function(t) {
            l.getFundList(), l.fundSta();
        });
    },
    onUnload: function() {
        e.un("drawsucc");
    },
    onShow: function() {},
    fundSta: function() {
        var e = this;
        u.fundSta({
            month: e.data.currentMonth
        }, function(t) {
            e.setData({
                userSta: t.result
            });
        }, function(t) {});
    },
    bindMultiPickerChange: function(t) {
        var e = this.data.multiArray[0][t.detail.value[0]] + "" + this.data.multiArray[1][t.detail.value[1]];
        this.setData({
            multiIndex: t.detail.value,
            currentMonth: e
        }), this.getFundList(), this.fundSta();
    },
    getFundList: function(e) {

      console.log('======eeee=====');
      console.log(e);


        var a = this;
        a.setData({
            loadProcess: !0
        });
        var n = {
            month: a.data.currentMonth,
            busiType: a.data.busiType,
            pageIndex: this.data.pageIndex,
            pageSize: i.globalData.pageSize
        };



        u.getFundList(n, function(t) {
            var i = t.rows;

            

            console.log('======tttt=====');
            console.log(t);


            if (e) {
                if (i.length >= 1) {
                    var n = a.data.scoreList.concat(i);

                    console.log('======a.data.scoreList.concat(i)=====');
                    console.log(n);


                    a.setData({
                        scoreList: n,
                        loadProcess: !1,
                        reqInit: !0
                    });
                }
            } else a.setData({
                scoreList: i,
                loadProcess: !1,
                reqInit: !0
            });

            
            0 == t.pages || a.data.pageIndex == t.pages ? a.setData({
                loadingComplete: !0,
                loadProcess: !1,
                reqInit: !0
            }) : a.data.loadingComplete && a.setData({
                loadingComplete: !1
            });
        }, function(t) {});
    },
    scrollLower: function() {
        var t = this;
        t.data.loadProcess || t.data.loadingComplete || (t.setData({
            pageIndex: t.data.pageIndex + 1
        }), t.getFundList(!0));
    }
});