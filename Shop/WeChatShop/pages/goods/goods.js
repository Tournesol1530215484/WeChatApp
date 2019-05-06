// pages/goods/goods.js
const util = require('../../utils/util.js')
const app = getApp();
Page({

    /**
     * 页面的初始数据
     */
    data: {
        indicatoractivecolor:"#ff9900",
        Sales:1,    //购物选项
        SalesStatus:'disabled',     //售卖状态
        curren:0,
        height:'',
        byheight:145,
        GoodsInfo:[],
        Ogphoto:[],
        status:'-1' //加入收藏状态，-1未收藏，1收藏
    },

    //点击减少数量
    rmSales:function(e){
        var Sales=this.data.Sales
        var status=this.data.status
        if(Sales<=1){
            this.setData({ SalesStatus: 'disabled' })
        }else{
            Sales = --Sales;
            this.setData({ SalesStatus: ' ', Sales: Sales })
        }
    },

    //点击增加数量
    addSales:function(e){
        var Sales = this.data.Sales
        var status = this.data.status
        Sales = ++Sales;
        this.setData({ SalesStatus: ' ', Sales: Sales })
    },

    //详情叶与售后的切换
    chageCuren:function(e){
        var curren = this.data.curren
        if (this.data.curren == e.target.dataset.curent){
            return false;
        }else{
            curren = curren ? 0 : 1;
            this.setData({ curren: curren })
        }
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        let that = this;
        var cid=options.cid
        that.GoodsInfo(cid)
        that.IsBeCollection(cid)
        wx.getSystemInfo({
            success: function (res) {
                let clientHeight = res.windowHeight;
                let clientWidth = res.windowWidth;
                let ratio = 750 / clientWidth;
                let height = clientHeight * ratio-140;
                that.setData({
                    height: height
                });
            }
        });
    },

    /**
     * 获取商品详情
     */
    GoodsInfo:function(cid){
        console.log(cid)
        var url = app.globalData.ApiUrl + '/api/Banner/GoodsInfo'
        var params = { cid: cid }
        util.wxRequest(url, params, success => {
            console.log(success.data)
            console.log(success.data.og_photo)
            this.setData({ GoodsInfo: success.data})
            this.setData({ Ogphoto: success.data.og_photo })

        }, data => { }, data => { })
    
    },

    /**
     * 商品收藏
     */
    Collection:function(e){
        var uid = app.globalData.userInfo.id
        //var uid ='ob6IZ45BlQHeiUNBKQOSEILF51Hs'
        var goodsid = e.currentTarget.dataset.goodsid
        var token=app.globalData.token
        console.log(token)
        //var token = '1555913832'
        var url = app.globalData.ApiUrl + '/api/Banner/GoodsCollection'
        var params = { uid: uid, goodsid: goodsid,token:token}
        util.wxRequest(url, params, success => {
            if (success.code==200){
                this.setData({ status:success.data })
                wx.showToast({
                    title: success.msg,
                    icon: 'success',
                    duration: 1000
                })
            }else{
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
            }
             console.log(success)
            // this.setData9({ status:success.data })

        }, data => { }, data => { })
    },

     /**
        * 判断该商品是否已被收藏
        */
    IsBeCollection:function(id){
        var uid = app.globalData.userInfo.id
        // var uid = 'ob6IZ45BlQHeiUNBKQOSEILF51Hs'
        var url = app.globalData.ApiUrl + '/api/Banner/isCollection'
        var params = { uid: uid, goodsid: id }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                this.setData({ status: success.data })
            }
        }, data => { }, data => { })
    },

    /**
     * 加入购物车
     */

    AddtoCart:function(e){
        var uid = app.globalData.userInfo.id
        var goodsid = e.currentTarget.dataset.goodsid
        var token = app.globalData.token
        var Sales = this.data.Sales
        var url = app.globalData.ApiUrl + '/api/Banner/AddToCart'
        var params = { uid: uid, goodsid: goodsid, token: token, Sales: Sales }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
               // this.setData({ status: success.data })
                wx.showToast({
                    title: success.msg,
                    icon: 'success',
                    duration: 1000
                })
            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
            }
            console.log(success)
            // this.setData9({ status:success.data })

        }, data => { }, data => { })
        
    },

    /**
     * 立即购买(先加入购物车)
     */
    BuyNow:function(e){
        var uid = app.globalData.userInfo.id
        var goodsid = e.currentTarget.dataset.goodsid
        var token = app.globalData.token
        var Sales = this.data.Sales
        var url = app.globalData.ApiUrl + '/api/Banner/BuyNow'
        var params = { uid: uid, goodsid: goodsid, token: token, Sales: Sales }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                // this.setData({ status: success.data })
                // wx.showToast({
                //     title: success.msg,
                //     icon: 'success',
                //     duration: 1000
                // })
                wx.switchTab({
                    url: '../shopCate/shopCate',
                })

            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
            }
            console.log(success)
            // this.setData9({ status:success.data })

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