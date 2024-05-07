getApp().require("utils/util.js").buildComponent({
    data: {
        _popupAnimation: {},
        _display: !1,
        hidden: !0,
        height: 200,
        maskOpacity: .5,
        allowMaskTappedClose: !0,
        showCancelButton: !0,
        showConfirmButton: !0,
        showTitle: !0,
        title: ""
    },
    methods: {
        _onSetHidden: function() {
            var t = this, i = wx.createAnimation({
                duration: 200,
                timingFunction: "linear",
                delay: 0
            });
            t.animation = i, t.data.hidden ? (i.translateX(0).step(), t.setData({
                _popupAnimation: i.export()
            }), setTimeout(function() {
                t.setData({
                    _display: !1
                });
            }, 250)) : (t.setData({
                _display: !0
            }), setTimeout(function() {
                i.translateY(-t.data.height).step(), t.setData({
                    _popupAnimation: i.export()
                });
            }, 50));
        },
        _onCancel: function() {
            this.triggerEvent("cancel", this);
        },
        _onMaskCancel: function() {
            this.data.allowMaskTappedClose && this.triggerEvent("cancel", this);
        },
        _onConfirm: function() {
            this.triggerEvent("confirm", this);
        },
        _onBeforeScroll: function() {}
    }
});