// pages/about/addres/editaddres.js


const util = require('../../../utils/util.js')
const app = getApp();

Page({

    /**
     * 页面的初始数据
     */
    data: {
        Addres:'',
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var that=this
        console.log(options.id)
        var id = options.id
        var url = app.globalData.ApiUrl + '/api/Banner/Findaddres'
        var params = {id:id }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                // console.log(success)
                that.setData({ Addres: success.data })
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
     * 添加收货地址
     */
    formSubmit(e) {
        console.log('form发生了submit事件，携带数据为：', e.detail.value)
        var that = this
        var date = e.detail.value
        var id = date.id
        var consignee = date.consignee
        var addres = date.addres
        var phone = date.phone
        var check = date.check
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/Editaddres'
        var params = {  uid:uid,token: token, id:id,consignee: consignee, addres: addres, phone: phone, check: check }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                wx.showToast({
                    title: success.msg,
                    icon: 'success',
                    duration: 2000
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