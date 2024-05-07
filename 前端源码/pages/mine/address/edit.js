var e = getApp(), o = e.require("utils/util.js"), n = e.require("utils/api.js"), a = e.require("utils/onfire.js"), t = {};

Page({
    data: {
        model: t
    },
    onLoad: function(o) {
        var a = this;
      
        o.id ? n.userAddressInfo({
            id: o.id
        }, function(e) {
            t = e, a.setData({
                model: t
            });
        }) : e.globalData.schoolInfo && (t = {
            SchoolId: e.globalData.schoolInfo.Id,
            SchoolName: e.globalData.schoolInfo.Name
        }, a.setData({
            model: t
        }));
    },
    onReady: function() {},
    onShow: function() {
        var g = this;
        a.un("SchoolSelect");
        a.on("location", function (o){
          console.log('-------o-----');
          console.log(o);
        });
        g.setData({
          locationInfo: e
        });

    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onLinkmanBlur: function(e) {
        var o = this;
        t.Linkman = e.detail.value, o.setData({
            model: t
        });
    },
    onGenderChange: function(e) {
        t.Gender = e.detail.value, this.setData({
            model: t
        });
    },
    onPhoneBlur: function(e) {
        var o = this;
        t.Phone = e.detail.value, o.setData({
            model: t
        });
    },
    onSchoolTap: function() {
        var e = this;
        a.on("SchoolSelect", function(o) {
            t.SchoolId = o.Id, t.SchoolName = o.Name, e.setData({
                model: t
            });
        }), wx.navigateTo({
            url: "../school/select"
        });
    },
    onAddressChanged: function(e) {
        var o = this;
        t.Address = e.detail.value, o.setData({
            model: t
        });
    },

    onSubmit: function(e) {
        var s = e.detail.value;
        var g = this;

        console.log(g);

        var latitude = '';    
        var longitude = '';

        if(g.data.locationInfo.globalData.locationInfo) {
            var latitude = g.data.locationInfo.globalData.locationInfo.latitude;
            var longitude = g.data.locationInfo.globalData.locationInfo.longitude;
        } 
        

        !s.linkman || s.linkman.length <= 1 ? o.toast("请填写联系人姓名") : !s.phone || s.phone.length < 7 ? o.toast("请填写联系手机") : t.SchoolId ? !s.address || s.address.length < 2 ? o.toast("请填写楼名房号") : n.userAddressSave({
            Id: t.Id,
            Linkman: s.linkman,
            Phone: s.phone,
            Gender: t.Gender || 1,
            SchoolId: t.SchoolId,
            Address: s.address,
            Lat: latitude,
            Lng: longitude,
        }, function() {
            a.fire("addressChange"), wx.navigateBack();
        }) : o.toast("请选择校区");
    },


    onDelete: function() {
        t.Id && wx.showModal({
            title: "删除提醒",
            content: "确定要删除此条地址信息？",
            success: function(e) {
                e.confirm && n.userAddressDelete({
                    id: t.Id
                }, function() {
                    a.fire("addressChange"), wx.navigateBack();
                });
            }
        });
    }
});