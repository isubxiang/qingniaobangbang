module.exports = {
    getNames: function(e) {
        for (var a = [], u = 0; u < e.length; ++u) a.push(e[u].Name);
        return a;
    },
    getValues: function(e) {
        for (var a = [], u = 0; u < e.length; ++u) a.push(e[u].Values);
        return a;
    },
    getName: function(e, a) {
        for (var u = 0; u < e.length; ++u) if (e[u].Value == a) return e[u].Name;
        return null;
    },
    getValue: function(e, a) {
        for (var u = 0; u < e.length; ++u) if (e[u].Name == value) return e[u].Value;
        return null;
    },
  MarketingTypes: [{
    Name: "折扣",
    Value: 1
  }, {
    Name: "满减",
    Value: 2
  }],


  ErrandTypes: [{
    Name: "取件",
    Value: 1
  }, {
    Name: "带饭",
    Value: 2
  }, {
    Name: "奶茶",
    Value: 3
  }, {
    Name: "辅导",
    Value: 4
  }, {
    Name: "游戏",
    Value: 5
  }, {
    Name: "搬运",
    Value: 6
  }, {
    Name: "打印",
    Value: 7
  }, {
    Name: "全能",
    Value: 1024
  }, {
    Name: "万能",
    Value: 2048
  }],


  OrderTypes: [{
    Name: "外卖",
    Value: 1
  }, {
    Name: "跑腿",
    Value: 2
  }, {
    Name: "辅导",
    Value: 4
  }, {
    Name: "游戏",
    Value: 5
  }, {
    Name: "搬运",
    Value: 6
  }, {
    Name: "打印",
    Value: 7
  }, {
    Name: "全能",
    Value: 1024
  }],


  OrderStatus: [{
    Name: "待付款",
    Value: 1
  }, {
    Name: "待处理",
    Value: 2
  }, {
    Name: "制作中",
    Value: 4
  }, {
    Name: "待配送",
    Value: 8
  }, {
    Name: "已接单",
    Value: 16
  }, {
    Name: "配送中",
    Value: 32
  }, {
    Name: "待评价",
    Value: 64
  }, {
    Name: "已完成",
    Value: 128
  }, {
    Name: "付款超时",
    Value: 256
  }, {
    Name: "用户取消",
    Value: 512
  }, {
    Name: "商家取消",
    Value: 1024
  }, {
    Name: "过期取消",
    Value: 2048
  }, {
    Name: "后台取消",
    Value: 4096
 }, {
    Name: "退款失败",
    Value: 8192
}],

  OrderListTypes: [{
    Name: "我售出的",
    Value: 1
  }, {
    Name: "我跑腿的",
    Value: 2
  }, {
    Name: "我发起的",
    Value: 3
  }],
  PaymentStatus: [{
    Name: "付款申请",
    Value: 0
  }, {
    Name: "付款成功",
    Value: 1
  }, {
    Name: "付款失败",
    Value: 2
  }, {
    Name: "已退款",
    Value: 4
  }],
  RefundStatus: [{
    Name: "退款申请",
    Value: 0
  }, {
    Name: "退款成功",
    Value: 1
  }, {
    Name: "退款失败",
    Value: 2
  }, {
    Name: "退款处理中",
    Value: 4
  }],


  ErrandOrderTypes: [{
    Name: "外卖配送",
    Value: 1
  }, {
    Name: "到店自取",
    Value: 2
  }],



  ErrandGenderLimits: [{
    Name: "不限性别",
    Value: 0
  }, {
    Name: "限男生",
    Value: 1
  }, {
    Name: "限女生",
    Value: 2
  }],


  PingListTypes: [{
    Name: "全部评价",
    Value: 1
  }, {
    Name: "他获得的评价",
    Value: 2
  }, {
    Name: "他对别人的评价",
    Value: 3
  }],



  ErrandWeights: [{
    Name: "小于5斤",
    Value: 5
  }, {
    Name: "约5-10斤",
    Value: 10
  }, {
    Name: "约10-20斤",
    Value: 20
  }, {
    Name: "约20-50斤",
    Value: 50
  }, {
    Name: "50斤以上",
    Value: 100
  }],

  StudentStatus: [{
    Name: "未认证",
    Value: 0
  }, {
    Name: "审核中",
    Value: 1
  }, {
    Name: "已认证",
    Value: 2
  }, {
    Name: "认证失败",
    Value: 3
  }]
};