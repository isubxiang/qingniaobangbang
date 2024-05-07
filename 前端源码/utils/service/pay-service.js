var i = require("./api-service.js"), o = require("../../utils/common-util.js"), a = {
    onBillSucc: null,
    onBillFail: null,
    onBillTimeoutFail: null,
    tradeNo: "",
    payType: "",
    payFn: "getPayResult",
    openPayResult: function() {
        "" != this.tradeNo && (o.dialog.showLoading({
            title: "正在获取支付..."
        }), this.getPayResultByTradeNo());
    },
    finishFundBill: function() {
        this.tradeNo = "", o.tools.callBackFunction(this.onBillSucc, this.payType);
    },
    finishBill: function() {
        "" != this.tradeNo && (this.tradeNo = "", o.tools.callBackFunction(this.onBillSucc, this.payType));
    },
    hideLoadingAndShowNoPay: function() {
        var i = this;
        o.dialog.hideLoading(), o.pay.showNoPayResultInfoPop(function() {
            i.closePay();
        }, function() {
            i.refreshResult();
        });
    },
    getPayResultByTradeNo: function() {
        var a = this;
        i[this.payFn](this.tradeNo, function(i) {
            if (0 == i.code) {
                var n = i.result;
                n && n.paid ? (o.dialog.hideLoading(), a.finishBill()) : a.hideLoadingAndShowNoPay();
            } else a.hideLoadingAndShowNoPay();
        }, function() {
            a.hideLoadingAndShowNoPay();
        });
    },
    reloadPay: function() {
        this.dealTradeNoFail(), o.tools.callBackFunction(this.onBillTimeoutFail);
    },
    refreshResult: function() {
        this.openPayResult();
    },
    closePay: function() {
        this.dealTradeNoFail(), this.tradeNo = "", o.tools.callBackFunction(this.onBillFail);
    },
    dealTradeNoFail: function() {
        i.cancelPayOrder(this.tradeNo, function() {}, function() {});
    },
    hideLoadingAndShowError: function(i) {
        var a = this;
        o.dialog.hideLoading(), o.dialog.alert({
            content: i
        }), o.tools.callBackFunction(a.onBillFail);
    },
    wxPay: function(i) {
        var a = this;
        a.tradeNo = i.tradeNo, o.pay.wxPay(i, function(i) {
            o.dialog.hideLoading();
            var n = getApp();
            n.isWx() ? n.isAndroid() || a.openPayResult() : n.isAndroid() && a.openPayResult();
        });
    }
};

module.exports = a;