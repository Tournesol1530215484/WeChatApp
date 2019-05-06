//index.js
const util= require ('../../utils/util.js')
//获取应用实例
const app = getApp()

Page({
  data: {
      imgUrls: [
          'https://images.unsplash.com/photo-1551334787-21e6bd3ab135?w=640',
          'https://images.unsplash.com/photo-1551214012-84f95e060dee?w=640',
          'https://images.unsplash.com/photo-1551446591-142875a901a1?w=640'
      ],
      Goods: [
          {
              url: "http://120.79.207.238/WeChat/ceshi/page6.jpg",
              goodsname: "红色潮流卫衣",
              price: "66.66",
              status: 0
          }
      ],
      indicatorDots: true,
      autoplay: true,
      interval: 3000,
      duration: 1000,
      indicatorcolor:"#fff",
      indicatoractivecolor:"#ff9900",
      Fill:"widthFix"

    

  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
      var that = this;
    that.loadIndex();
  },
    //获取轮播图信息
    loadIndex: function () {
        var url = app.globalData.ApiUrl +'/api/banner/getAllBanner'
        var params=''
        util.wxRequest(url, params, success => { 
            this.setData({ imgUrls: success.data })
         },data=>{},data=>{})
    },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})
