// pages/about/about.js
const util = require('../../utils/util.js')
const app = getApp();
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 我的收藏
     */
    MyCollection:function(e){
        wx.navigateTo({
            url: './collection/collection',
        })

    },


    /**
     * 收货地址列表
     */

    RecAddress: function (e) {
        wx.navigateTo({
            url: './addres/addres',
        })

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        // wx.getSetting({
        //     success(res) {
        //         if (res.authSetting['scope.userInfo']) {
        //             // 已经授权，可以直接调用 getUserInfo 获取头像昵称
        //             wx.getUserInfo({
        //                 success(res) {
        //                     console.log(res.userInfo)
        //                 }
        //             })
        //         }
        //     }
        // })
    },
    bindGetUserInfo(e) {
        // 登录
    wx.login({
      success: res => {
          console.log(e.detail.userInfo)
          app.globalData.nickName = e.detail.userInfo.nickName
          app.globalData.avatarUrl = e.detail.userInfo.avatarUrl
          app.globalData.province = e.detail.userInfo.province
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
          if (res.code) {
              var that=this
              // 发起网络请求
              wx.request({
                  url: 'https://api.weixin.qq.com/sns/jscode2session',
                  data: {
                      appid:"wx98125d85c8fb1bfc",
                      secret:"30e5c27e0168e99e4a46fa38fb2cda3f",
                      js_code: res.code,
                      grant_type:"authorization_code"
                  },
                  success:function(e){
                      console.log(e.data.openid)
                      if (e.data.openid != null && e.data.openid !=undefined){
                          app.globalData.openid=e.data.openid
                          that.getUser()
                      }
                  },
                  fail:function(e){
                      console.log(e)
                  }
              })
          } else {
              console.log('登录失败！' + res.errMsg)
          }
      }
    })
        
    },
      //获取用户信息
    getUser: function () {
        var that=this
        wx.login({

            success:res=>{
                if(res.code){
                    var url = app.globalData.ApiUrl + '/api/Banner/UserInfo'
                    var params = { code: res.code, openid: app.globalData.openid, nickName: app.globalData.nickName, avatarUrl: app.globalData.avatarUrl, province:app.globalData.province}
                     wx.request(
                         util.wxRequest(url, params, success => {
                            console.log(success)
                             if (success.code==200){
                                 console.log("登陆成功");
                                wx.showToast({
                                     title: success.msg,
                                     icon: 'success',
                                     duration: 3000
                                 })
                                 //获取用户信息
                                 app.globalData.userInfo=success.data
                                 app.globalData.token = success.data.tonken_time
                                 app.globalData.loginstatus=true
                             } else if (success.code == 400){
                                 wx.showToast({
                                     title:success.msg,
                                     icon: 'loading',
                                     duration:3000
                                 })
                                 that.register()

                             }else{
                                 app.globalData.loginstatus = false
                                 wx.showToast({
                                     title: success.msg,
                                     icon: loading,
                                     duration: 3000
                                 })

                             }

                        }, data => { }, data => { })
                    )
                } else {
                    console.log('登录失败！' + res.errMsg)
                }
            }

        })
    },


    //用户注册
    register:function(){
        var that=this
        var openid = app.globalData.openid
        var url = app.globalData.ApiUrl + '/api/Banner/Register'
        var params = { openid: openid }
        util.wxRequest(url, params, success => {
             console.log(success)
             if(success.code==200){
                 //用户注册成功
                 that.globalData.userInfo=success.data
             }
            // this.setData({ TopCate: success.data.cates })
            // this.setData({ Des: success.data.description })
        }, data => { }, data => { })
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {

    }
})