// pages/about/collection/collection.js

const util = require('../../../utils/util.js')
const app = getApp();

Page({

    /**
     * 页面的初始数据
     */
    data: {
        Goods:[],
        Emptycollection:true,
    },

    /**
     * 空收藏进行跳转
     */
    jumpIndex: function () {
        wx.switchTab({
            url: '../../index/index'
        })
    },

    /**
     * 点击移除商品收藏
     */
    Rmcollection:function(e){
        var that=this
        var gid = e.currentTarget.dataset.gid
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/Rmcollection'
        var params = { uid: uid, token: token,gid:gid }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                console.log(success.data)
                that.setData({ Emptycollection: success.data})
                that.onShow()
            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 3000
                })
            }

        }, data => { }, data => { })
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        console.log()
    },

    /**
     * 获取该用户的所有收藏
     */
    getAllCollection:function(){


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
        var that=this
        var Goods=that.data.Goods
        var Emptycollection = that.data.Emptycollection
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/getAllCollection'
        var params = { uid: uid, token: token }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                that.setData({ Goods: success.data, Emptycollection:false})
            } else if (success.code == 201){
                that.setData({ Goods: success.data, Emptycollection: true })
            }else{
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 3000
                })
            }
        
        }, data => { }, data => { })
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