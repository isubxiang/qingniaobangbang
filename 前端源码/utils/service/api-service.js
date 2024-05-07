

var app = getApp();

var t = require("./http/http-service.js"), s = {
    
	//siteInfo: require("siteinfo.js"),
	
	basePath: app.globalData.siteUrl,
	
    _getUrl: function(t) {
        return 0 == t.indexOf("/") ? this.basePath + t : t;
    },
    _httpPost: function(s, o, i, n) {
        var e = this._getUrl(s);
        t.post(e, o, i, n);
    },
    httpPost: function(s, o, i, n) {
        var e = this._getUrl(s);
        t.post(e, o, i, n);
    },
    _httpGet: function(s, o, i, n) {
        var e = this._getUrl(s);
        t.get(e, o, i, n);
    },
    _uploadFile: function(s, o, i, n, e) {
        var h = this._getUrl(s);
        t.uploadFile(h, o, i, n, e);
    },
    _downFile: function(s, o, i) {
        t.downloadFile(s, o, i);
    },
    getNoticeList: function(t, s) {
        this._httpGet("/App/Running/notice/listcur", {
            showLoading: !1
        }, t, s);
    },
    getOrderList: function(t, s) {
        this._httpPost("/App/Running/book/listcur", {
            bookType: -1
        }, t, s);
    },
    listPoster: function(t, s) {
        this._httpGet("/App/Running/config/listposter", {
            showLoading: !1
        }, t, s);
    },
    listCatType: function(t, s) {
        this._httpGet("/App/Running/cat/listnew", {
            showLoading: !1
        }, t, s);
    },
    getCatType: function(t, s, o) {
        this._httpPost("/App/Running/cat/info", {
            id: t,
            showLoading: !1
        }, s, o);
    },
    listBusiCar: function(t, s, o) {
        this._httpPost("/App/Running/busi/listcar", t, s, o);
    },
    listMyBusiCar: function(t, s, o) {
        this._httpPost("/App/Running/busi/listmycar", t, s, o);
    },
    delCar: function(t, s, o) {
        this._httpPost("/App/Running/busi/cancar", {
            id: t
        }, s, o);
    },
    getMyDetInfo: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    getPubDetInfo: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    finishTask: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    plusTask: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    catchTask: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    listGame: function(t, s) {
        this._httpGet("/App/Running/game/list", {
            showLoading: !1
        }, t, s);
    },
    markFlag: function(t, s, o) {
        this._httpPost("/App/Running/busi/catchmark", t, s, o);
    },
    catchRemark: function(t, s, o) {
        this._httpPost("/App/Running/busi/catchremark", t, s, o);
    },
    listNotice: function(t, s) {
        this._httpPost("/App/Running/uni/listnotice", {
            showLoading: !1
        }, t, s);
    },
    listBusiNotice: function(t, s, o) {
        this._httpPost("/App/Running/busi/notices", {
            showLoading: !1,
            busiId: t
        }, s, o);
    },
    regainSessionKey: function(t, s, o) {
        var i = {
            code: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/user/regainsessionkey", i, s, o);
    },
    loginBySessionKey: function(t, s, o, i) {
        var n = getApp().getSystemInfo(), e = {
            code: s,
            showLoading: !1,
            systemInfo: JSON.stringify(n),
            qrScene: getApp().getQrScene()
        };
        this._httpPost(t, e, o, i);
    },
    updateHead: function(t, s, o) {
        this._httpPost("/App/Running/user/updatehead", t, s, o);
    },
    decodePhone: function(t, s, o, i) {
        var n = getApp().getSystemInfo(), e = {
            iv: t,
            encryptedData: s,
            systemInfo: JSON.stringify(n)
        };
        this._httpPost("/App/Running/user/decodephone", e, o, i);
    },
    sendRegCode: function(t, s, o, i) {
        var n = {
            phoneNum: s
        };
        this._httpPost(t, n, o, i);
    },
    sendUpdatePhoneCode: function(t, s, o) {
        var i = {
            phoneNum: t
        };
        this._httpPost("/App/Running/user/updatephone", i, s, o);
    },
    getSchoolById: function(t, s, o) {
        this._httpPost("/App/Running/uni/get", {
            id: t
        }, s, o);
    },
    getUniRule: function(t, s, o) {
        this._httpPost("/App/Running/uni/grule", t, s, o);
    },
    getNoticeInfo: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    unFinishFlag: function(t, s) {
        this._httpPost("/App/Running/user/unfinish", {
            showLoading: !1
        }, t, s);
    },
    getSchoolList: function(t, s) {
        this._httpGet("/App/Running/uni/listall", {}, t, s);
    },
    loadUserInfo: function(t, s) {
        this._httpGet("/App/Running/user/info", {
            showLoading: !1
        }, t, s);
    },
    loadModifyInfo: function(t, s) {
        this._httpGet("/App/Running/user/loadmodify", {}, t, s);
    },
    submitModify: function(t, s, o) {
        this._httpPost("/App/Running/user/submitmodifyn", t, s, o);
    },
    jumpGameInfo: function(t, s) {
        this._httpGet("/App/Running/user/jumpgame", {}, t, s);
    },
    jumpPosterInfo: function(t, s) {
        this._httpGet("/App/Running/user/jumpposter", {}, t, s);
    },
    qrInfo: function(t, s) {
        this._httpGet("/App/Running/user/qrinfo", {}, t, s);
    },
    downFileInfo: function(t, s, o) {
        this._downFile(t, s, o);
    },
    loadVerifyInfo: function(t, s) {
        this._httpGet("/App/Running/user/verifyinfo", {}, t, s);
    },
    getMonthSta: function(t, s, o) {
        this._httpPost("/App/Running/user/monthsta", t, s, o);
    },
    getFundList: function(t, s, o) {
        this._httpPost("/App/Running/user/fundlist", t, s, o);
    },
    fundSta: function(t, s, o) {
        this._httpPost("/App/Running/user/fundsta", t, s, o);
    },
    getSunList: function(t, s, o) {
        this._httpPost("/App/Running/user/sunlist", t, s, o);
    },
    loadPersonSta: function(t, s) {
        this._httpPost("/App/Running/user/levelsta", {}, t, s);
    },
    getPersonList: function(t, s, o) {
        this._httpPost("/App/Running/user/level", t, s, o);
    },
    getBusiList: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    getReportGroup: function(t, s, o, i) {
        this._httpGet(t, s, o, i);
    },
    listWithdraw: function(t, s, o) {
        this._httpPost("/App/Running/user/listwithdraw", t, s, o);
    },
    drawApply: function(t, s, o, i) {
        this._httpPost("/App/Running/user/drawapply", {
            drawAmount: t,
            formId: s
        }, o, i);
    },
    submitRecruit: function(t, s, o) {
        this._httpPost("/App/Running/busi/recruit", t, s, o);
    },
    submitSecond: function(t, s, o) {
        this._httpPost("/App/Running/busi/second", t, s, o);
    },
    submitReport: function(t, s, o) {
        this._httpPost("/App/Running/busi/report", t, s, o);
    },
    submitReq: function(t, s, o) {
        this._httpPost("/App/Running/busi/req", t, s, o);
    },
    submitCar: function(t, s, o) {
        this._httpPost("/App/Running/busi/car", t, s, o);
    },
    submitPart: function(t, s, o) {
        this._httpPost("/App/Running/busi/part", t, s, o);
    },
    submitPick: function(t, s, o) {
        this._httpPost("/App/Running/busi/pick", t, s, o);
    },
    submitMail: function(t, s, o) {
        this._httpPost("/App/Running/busi/mail", t, s, o);
    },
    submitExamMem: function(t, s, o) {
        this._httpPost("/App/Running/exam/rank/gen", t, s, o);
    },
    submitSuper: function(t, s, o) {
        this._httpPost("/App/Running/busi/super", t, s, o);
    },
    checkLogin: function(t, s) {
        this._httpGet("/App/Running/user/checklogin", {
            showLoading: !1
        }, t, s);
    },
    login: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    loginByKey: function(t, s, o) {
        this._httpPost("/App/Running/user/loginbykey", t, s, o);
    },
    listAddress: function(t, s) {
        this._httpGet("/App/Running/address/list", {}, t, s);
    },
    addAddress: function(t, s, o) {
        this._httpPost("/App/Running/address/add", t, s, o);
    },
    updateAddress: function(t, s, o) {
        this._httpPost("/App/Running/address/update", t, s, o);
    },
    delAddress: function(t, s, o) {
        this._httpPost("/App/Running/address/del", t, s, o);
    },
    updateCardData: function(t, s, o) {
        this._httpPost("/App/Running/user/updatecard", t, s, o);
    },
    updateCardFile: function(t, s, o, i) {
        this._uploadFile("/Running/user/updatecard", t, s, o, i);
    },
    uploadImageFile: function(t, s, o, i) {
        this._uploadFile("/Running/attach/upload", t, s, o, i);
    },
    delImageFile: function(t, s, o) {
        this._httpPost("/App/Running/attach/del", t, s, o);
    },
    updateStuData: function(t, s, o) {
        this._httpPost("/App/Running/user/updatestu", t, s, o);
    },
    rechargeOrder: function(t, s, o) {
        var i = {
            amount: t
        };
        this._httpPost("/App/Running/pay/recharge", i, s, o);
    },
    cancelPayOrder: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/pay/cancel", i, s, o);
    },
    getPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/payResult", i, s, o);
    },
    getExamPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/exam/pay/result", i, s, o);
    },
    updateStuFile: function(t, s, o, i) {
        this._uploadFile("/Running/user/updatestu", t, s, o, i);
    },
    signday: function(t, s) {
        this._httpGet("/App/Running/user/signdayn", {
            showLoading: !1
        }, t, s);
    },
    newMem: function(t, s) {
        this._httpGet("/App/Running/user/newmem", {
            showLoading: !1
        }, t, s);
    },
    listOrderIndex: function(t, s, o) {
        this._httpPost("/App/Running/order/listall", t, s, o);
    },
    listUniAddress: function(t, s) {
        this._httpGet("/App/Running/uni/listaddress", {}, t, s);
    },
    listUniCarAddress: function(t, s) {
        this._httpGet("/App/Running/uni/listcaraddress", {}, t, s);
    },
    listUniBountyTwo: function(t, s) {
        this._httpGet("/App/Running/uni/listtwo", {}, t, s);
    },
    listOrderByState: function(t, s, o) {
        this._httpPost("/App/Running/order/listbystate", t, s, o);
    },
    listUniBountyTop: function(t, s) {
        this._httpGet("/App/Running/uni/listtop", {
            showLoading: !1
        }, t, s);
    },
    loadOrderInfo: function(t, s, o) {
        this._httpPost("/App/Running/order/info", {
            id: t
        }, s, o);
    },
    submitFeedBack: function(t, s, o) {
        this._httpPost("/App/Running/uni/feedback", t, s, o);
    },
    submitComment: function(t, s, o) {
        this._httpPost("/App/Running/busi/comment", t, s, o);
    },
    listCouponTpl: function(t, s) {
        this._httpGet("/App/Running/uni/listcoupon", {}, t, s);
    },
    listCoupon: function(t, s, o) {
        this._httpPost("/App/Running/coupon/list", t, s, o);
    },
    loadCouponCalInfo: function(t, s, o) {
        this._httpPost("/App/Running/coupon/calinfo", t, s, o);
    },
    acceptCouponTpl: function(t, s, o) {
        this._httpPost("/App/Running/uni/acceptcoupon", {
            id: t
        }, s, o);
    },
    getUserInfo: function(t, s) {
        this._httpGet("/App/Running/user/info", {}, t, s);
    },
    submitFormId: function(t, s, o) {
        t.showLoading = !1, this._httpPost("/App/Running/busi/formidn", t, s, o);
    },
    getNotifyInfo: function(t, s) {
        this._httpPost("/App/Running/user/notifyinfo", {}, t, s);
    },
    getNotifyUrl: function(t, s) {
        this._httpPost("/App/Running/user/notifyurl", {}, t, s);
    },
    submitNotify: function(t, s, o) {
        this._httpPost("/App/Running/user/notifyset", t, s, o);
    },
    examRankList: function(t, s, o) {
        this._httpGet("/App/Running/exam/rank/list", t, s, o);
    },
    examTopTypeList: function(t, s) {
        this._httpGet("/App/Running/exam/type/top", {}, t, s);
    },
    examChildTypeList: function(t, s, o) {
        this._httpPost("/App/Running/exam/type/child", {
            pId: t
        }, s, o);
    },
    examTotalPerson: function(t, s) {
        this._httpGet("/App/Running/exam/index/total", {
            showLoading: !1
        }, t, s);
    },
    examQuestRatio: function(t, s, o) {
        t.showLoading = !1, this._httpPost("/App/Running/exam/type/ratio", t, s, o);
    },
    examRankPerson: function(t, s) {
        this._httpGet("/App/Running/exam/rank/person", {
            showLoading: !1
        }, t, s);
    },
    examRegionYearPaper: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/regionyearn", {
            busiId: t
        }, s, o);
    },
    examRegionYearQuest: function(t, s, o) {
        this._httpPost("/App/Running/exam/index/regionyear", {
            busiId: t
        }, s, o);
    },
    examTypeInfo: function(t, s, o) {
        this._httpPost("/App/Running/exam/type/info", t, s, o);
    },
    examPapers: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/list", t, s, o);
    },
    examPapersByExam: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/listexam", t, s, o);
    },
    examQuests: function(t, s, o) {
        this._httpPost("/App/Running/exam/quest/list", t, s, o);
    },
    examQuestsAnswer: function(t, s, o) {
        this._httpPost("/App/Running/exam/quest/listanswer", t, s, o);
    },
    examIndex: function(t, s, o) {
        this._httpPost("/App/Running/exam/index/list", t, s, o);
    },
    examBuy: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/listbuy", t, s, o);
    },
    examPaperSta: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/singlesta", t, s, o);
    },
    examUsed: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/listused", t, s, o);
    },
    examErr: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/listerr", t, s, o);
    },
    examCheckAuth: function(t, s, o) {
        this._httpPost("/App/Running/exam/rank/checkauth", t, s, o);
    },
    examSubAnswer: function(t, s, o) {
        this._httpPost("/App/Running/exam/quest/submit", t, s, o);
    },
    examBatchSubAnswer: function(t, s, o) {
        this._httpPost("/App/Running/exam/quest/batchsubmit", t, s, o);
    },
    examReset: function(t, s, o) {
        this._httpPost("/App/Running/exam/quest/reset", t, s, o);
    },
    examErrDel: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/delerr", t, s, o);
    },
    examErrDelAll: function(t, s, o) {
        this._httpPost("/App/Running/exam/paper/delallerr", t, s, o);
    },
    shopType: function(t, s) {
        this._httpGet("/App/Running/shop/list", {}, t, s);
    },
    shopInfo: function(t, s, o) {
        this._httpPost("/App/Running/shop/info", t, s, o);
    },
    shopList: function(t, s) {
        this._httpGet("/App/Running/shop/listpic", {}, t, s);
    },
    shopEnable: function(t, s, o) {
        this._httpPost("/App/Running/shop/enable", {
            shopId: t
        }, s, o);
    },
    loadIsbn: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/isbn", t, s, o);
    },
    shopGoodsFilter: function(t, s, o, i) {
        this._httpPost(t, s, o, i);
    },
    shopGoodsDet: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/det", t, s, o);
    },
    shopGoodsTeamDet: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/teamdet", t, s, o);
    },
    shopGoodsBeatDet: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/beatdet", t, s, o);
    },
    beatHelps: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/beathelps", t, s, o);
    },
    submitJoin: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/submitjoin", t, s, o);
    },
    beatPrice: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/beatprice", t, s, o);
    },
    shopBeatSubmit: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/beatsubmit", t, s, o);
    },
    shopBeatMiniSubmit: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/beatmsubmit", t, s, o);
    },
    beatPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/shop/pay/beatresult", i, s, o);
    },
    miniPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/shop/pay/beatmresult", i, s, o);
    },
    shopSkuPrice: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/price", t, s, o);
    },
    shopSubmit: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/submit", t, s, o);
    },
    shopTeamSubmit: function(t, s, o) {
        this._httpPost("/App/Running/shop/goods/teampsubmit", t, s, o);
    },
    shopPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/shop/pay/result", i, s, o);
    },
    teamPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/shop/pay/teamresult", i, s, o);
    },
    shopGapPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/shop/pay/gapresult", i, s, o);
    },
    taskPlusPayResult: function(t, s, o) {
        var i = {
            tradeNo: t,
            showLoading: !1
        };
        this._httpPost("/App/Running/busi/pay/result", i, s, o);
    },
    shopMy: function(t, s, o) {
        this._httpPost("/App/Running/shop/pay/listmy", t, s, o);
    },
    submitGap: function(t, s, o) {
        this._httpPost("/App/Running/shop/pay/submitgap", t, s, o);
    },
    shopOrderDet: function(t, s, o) {
        var i = {
            tradeNo: t
        };
        this._httpPost("/App/Running/shop/pay/orderdet", i, s, o);
    },
    shopOrderGap: function(t, s, o) {
        var i = {
            tradeNo: t
        };
        this._httpPost("/App/Running/shop/pay/ordergap", i, s, o);
    },
    shopBackApply: function(t, s, o) {
        var i = {
            tradeNo: t
        };
        this._httpPost("/App/Running/shop/pay/backapply", i, s, o);
    },
    shopPoster: function(t, s) {
        this._httpGet("/App/Running/shop/poster", {
            showLoading: !1
        }, t, s);
    }
};

module.exports = s;