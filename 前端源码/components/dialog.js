getApp().require("utils/util.js").buildComponent({
    data: {
        hidden: !0,
        maskOpacity: .8,
        closeButtonPosition: "none",
        closeButtonColor: "#fff",
        background: "transparent",
        allowMaskTappedClose: !1,
        borderRadius: 0,
        title: ""
    },
    methods: {
        _onClose: function() {
            this.triggerEvent("close", this);
        },
        _onMaskClose: function() {
            this.data.allowMaskTappedClose && this.triggerEvent("close", this);
        },
        _onBeforeScroll: function() {}
    }
});