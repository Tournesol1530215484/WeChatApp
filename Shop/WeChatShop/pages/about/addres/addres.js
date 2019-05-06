// pages/about/addres/addres.js

const util = require('../../../utils/util.js')
const app = getApp();

Page({

    /**
     * 页面的初始数据
     */
    data: {
        Addres:[],

    },

    /**
     * 添加新的收货地址
     */
    addAddres:function(){
        wx.navigateTo({
            url: './addaddres',
        })

    },
    /**
     * 删除收货地址
     */
    delddres:function(e){
        var aid = e.currentTarget.dataset.adid
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/Deladdres'
        var params = { uid: uid, token: token, aid: aid }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                wx.showToast({
                    title: success.msg,
                    icon: success,
                    duration: 3000
                })
                wx.navigateTo({
                    url: './addres',
                })
            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 3000
                })
            }

        }, data => { }, data => { })

        console.log(e)
    },

    /**
     * 修改收货地址
     */
    editaddres: function (e) {
        console.log(e.currentTarget.dataset.adid)
        var aid = e.currentTarget.dataset.adid
        wx.navigateTo({
            url: "./editaddres?id="+aid,

        })
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var that=this
        that.LisAddres()
    },

    /**
     * 查询所有的收货地址
     */
    LisAddres:function(){
        var that=this
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/Lisaddres'
        var params = { uid: uid, token: token }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                // console.log(success)
                that.setData({ Addres: success.data})
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