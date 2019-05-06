//app.js
const util = require('./utils/util.js')

App({
  onLaunch: function () {
    // 展示本地存储能力
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)

    // // 登录
    // wx.login({
    //   success: res => {
    //     // 发送 res.code 到后台换取 openId, sessionKey, unionId
    //       if (res.code) {
    //           var that=this
    //           // 发起网络请求
    //           wx.request({
    //               url: 'https://api.weixin.qq.com/sns/jscode2session',
    //               data: {
    //                   appid:"wx98125d85c8fb1bfc",
    //                   secret:"30e5c27e0168e99e4a46fa38fb2cda3f",
    //                   js_code: res.code,
    //                   grant_type:"authorization_code"
    //               },
    //               success:function(e){
    //                   console.log(e.data.openid)
    //                   if (e.data.openid != null && e.data.openid !=undefined){
    //                       that.globalData.openid=e.data.openid
    //                       that.getUser()
    //                   }
    //               },
    //               fail:function(e){
    //                   console.log(e)
    //               }
    //           })
    //       } else {
    //           console.log('登录失败！' + res.errMsg)
    //       }
    //   }
    // })
    
  },

//   //获取用户信息
//     getUser: function () {
//         var that=this
//         wx.login({

//             success:res=>{
//                 if(res.code){
//                     var url = that.globalData.ApiUrl + '/api/Banner/UserInfo'
//                     var params = { code: res.code, openid: that.globalData.openid }
//                      wx.request(
//                          util.wxRequest(url, params, success => {
//                             console.log(success)
//                              if (success.code==200){
                                 
//                                  console.log("登陆成功");
//                                  //获取用户信息
//                                  that.globalData.userInfo=success.data

//                                  that.globalData.loginstatus=true


//                              } else if (success.code == 400){
//                                  wx.showToast({
//                                      title:success.msg,
//                                      icon: 'loading',
//                                      duration:3000
//                                  })
//                                  that.register()
                                
//                              }else{
//                                  that.globalData.loginstatus = false
//                                  wx.showToast({
//                                      title: success.msg,
//                                      icon: loading,
//                                      duration: 3000
//                                  })
                                 
//                              }

//                         }, data => { }, data => { })
//                     )
//                 } else {
//                     console.log('登录失败！' + res.errMsg)
//                 }
//             }
        
//         })
//     },


    // //用户注册
    // register:function(){
    //     var that=this
    //     var openid = that.globalData.openid
    //     var url = that.globalData.ApiUrl + '/api/Banner/Register'
    //     var params = { openid: openid }
    //     util.wxRequest(url, params, success => {
    //          console.log(success)
    //          if(success.code==200){
    //              //用户注册成功
                 
    //              //that.globalData.userInfo=success.data
    //          }
    //         // this.setData({ TopCate: success.data.cates })
    //         // this.setData({ Des: success.data.description })
    //     }, data => { }, data => { })
    // },

  globalData: {
    userInfo: null,
      ApiUrl:"http://www.bigbin.top",
    openid:'',
    loginstatus:false,
      nickName:'',
      avatarUrl:'',
      province:'',
      token:''



    
  }


})