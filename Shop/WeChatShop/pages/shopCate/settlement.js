// pages/shopCate/settlement.js

const util = require('../../utils/util.js')
const app = getApp()

Page({

    /**
     * 页面的初始数据
     */
    data: {
        Addres:[],  //用户收货地址
        CarGoods:[],   //用户购物车商品
        totalprice:'',  //总价
        Carid:[],   //购物车id
    },

    /**
     * 订单提交
     */

    suborder:function(e){
        var that = this
        var uid = app.globalData.userInfo.id
        var totalprice = that.data.totalprice
        var Carid = that.data.Carid
        
        var openid = app.globalData.openid
        var token = app.globalData.token
        var Addres=that.data.Addres['id']
        var url = app.globalData.ApiUrl + '/api/Wepay/submitOrder'
        var params = { uid: uid, token: token, totalprice: totalprice, openid: openid, Carid: Carid, Addresid: Addres }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                console.log(success.data)
               // that.setData({ Addres: success.data })

            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
            }

        }, data => { }, data => { })     



    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var that=this
        var uid = options.uid
        var totalprices = options.totalprice
        var totalprice=that.data.totalprice

        var SelectGoods = options.SelectGoods
        that.FindAddres(uid)
        that.FindGoods(SelectGoods, uid)
        that.setData({ totalprice: totalprices })
        //console.log(options.uid)
        //console.log(options.SelectGoods)

       
        //找出对应的商品信息


    },
    /**
     *  找出默认收货地址
     */
    FindAddres:function(uid){
        var that=this
        var uid=uid
        console.log(uid)
        var url = app.globalData.ApiUrl + '/api/Banner/FindsAddres'
        var params = { uid: uid }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                console.log(success.data)
                that.setData({ Addres: success.data })

            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
            }

        }, data => { }, data => { })     

    },

    /**
     * 查找所有的购物车的详情商品信息
     */
    FindGoods: function (SelectGoods,uid ){
        var that = this
        var SelectGoods = SelectGoods
        var Carid = that.data.Carid
        that.setData({ Carid: SelectGoods})
        var uid=uid 
        var url = app.globalData.ApiUrl + '/api/Banner/FindGoods'
        var params = { SelectGoods: SelectGoods,uid:uid }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                console.log(success.data)
                console.log(console.log(888888888888))
                that.setData({ CarGoods: success.data })

            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
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