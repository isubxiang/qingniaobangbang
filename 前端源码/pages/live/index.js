var app = getApp();

Page({

  data: {
    roomInfo: [],
    loadText: "加载中...",
    noData: false,
    loadMore: true,
    live_status_tip: {
      101: '直播中',
      102: '未开始',
      103: '已结束',
      104: '禁播',
      105: '暂停中',
      106: '异常',
      107: '已过期'
    }
  },
  page: 1,


  onLoad: function (options) {
    this.getData();
  },


  onShow: function () {
  },

  getData: function(){
    let that = this;
    wx.showLoading();
    app.util.request({
      'url': 'app/running/getRoominfo',
      'data': {
        controller: 'livevideo.get_roominfo',
        page: this.page
      },
      dataType: 'json',
      success: function(res) {
        wx.hideLoading();
        if (res.data.code == 0) {
          let list = res.data.data || [];
          let h = {};

          h.share = res.data.share;
          if (h.share && h.share.name) wx.setNavigationBarTitle({ title: h.share.name })

          h.showTabbar = res.data.showTabbar;
          if(list.length<10) h.noMore = true, h.loadMore = false;
          let roomInfo = that.data.roomInfo;
          roomInfo = roomInfo.concat(list);
          h.roomInfo = roomInfo;
          that.page++;
          that.setData(h);
        } else {
          let h = {};
          if(that.page==1) h.noData = true;
          h.showTabbar = res.data.showTabbar;
          h.loadMore = false;
          that.setData(h);
        }
      }
    })
  },




  goLive: function (e) {
    let roomid = e.currentTarget.dataset.roomid;
    let idx = e.currentTarget.dataset.idx;
    let roomInfo = this.data.roomInfo;
    if (idx >= 0 && roomInfo && roomInfo[idx] && roomInfo[idx]['has_replay']) {
      roomid && wx.navigateTo({
        url: `/pages/live/replay?room_id=${roomid}`,
      })
      return;
    }
    roomid && wx.navigateTo({
      url: `plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin?room_id=${roomid}`,
    })
  },



  onPullDownRefresh: function () {

  },

  onReachBottom: function () {
    this.data.loadMore && this.getData();
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    let share = this.data.share || '';
    let title = share.title || '';
    let imageUrl = share.img || '';
    let share_path = 'pages/live/index';
    return {
      title,
      imageUrl,
      path: share_path,
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
  }


})