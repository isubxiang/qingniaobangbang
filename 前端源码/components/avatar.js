getApp().require("utils/util.js").buildComponent({
    data: {
        src: "",
        size: 16,
        color: "#fff",
        opacity: 1,
        _showDefault: !1
    },
    methods: {
        _onError: function() {
            this.setData({
                _showDefault: !0
            });
        }
    }
});