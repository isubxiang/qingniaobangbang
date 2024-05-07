var e = getApp(), t = (e.require("utils/util.js"), e.require("utils/api.js")), i = e.require("utils/onfire.js"), l = !0, a = null;

Page({
    data: {
        multiple: l,
        placeholder: a
    },
    onLoad: function(e) {
        e.title && wx.setNavigationBarTitle({
            title: e.title
        }), !1 === e.multiple && (l = e.multiple), e.placeholder && (a = e.placeholder), 
        this.setData({
            multiple: l,
            placeholder: a,
            value: e.value || ""
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onFormSubmit: function(e) {
        t.userSaveFormId({
            formId: e.detail.formId
        });
        var l = this.data.multiple ? e.detail.value.textarea : e.detail.value.input;
        i.fire("commonInput", l), wx.navigateBack();
    }
});