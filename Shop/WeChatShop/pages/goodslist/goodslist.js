// pages/goodslist/goodslist.js
const util = require('../../utils/util.js')
const app = getApp();
Page({

    /**
     * 页面的初始数据
     */
    data: {
        TopCate:[],
        CId:0,
        Des:'',
        page:1,
        Goods: [ ],
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var gid=options.gid
        var page = this.data.page
        this.setData({CId:gid})
         this.getBrotherCate(gid)
        this.getCateGoods(gid,page)
    },
    /**
     * 查出所有同级的子栏目
     */
    getBrotherCate: function (gid){
        var url = app.globalData.ApiUrl + '/api/Banner/getBrotherCate'
        var params = { gid: gid }
        util.wxRequest(url, params, success => {
            // console.log(success)
            this.setData({ TopCate: success.data.cates })
            this.setData({ Des: success.data.description })
        }, data => { }, data => { })

    },

    /**
     * 获取该栏目下的所有商品
     */

    getCateGoods: function (gid,page) {
        var url = app.globalData.ApiUrl + '/api/Banner/getCateGoods'
         var params = { gid: gid,page:page }
        util.wxRequest(url, params, success => {
            var Goods=this.data.Goods
            var newGoods = success.data.data
            if(newGoods.length>0){
                for(var i in newGoods){
                    Goods.push(newGoods[i])
                }
                this.setData({ Goods:Goods  })
            }else{
                wx:wx.showToast({
                    title: '没有更多数据了',
                    icon: 'none',
                })
            }
            //console.log(success.data.data)
            //this.setData({ Goods: success.data.data })
            // this.setData({ Des: success.data.description })

        }, data => { }, data => { })

    },

    /**
     * 获取商品详情
     */
    goodsInfo:function(e){
        var cid = e.currentTarget.dataset.cid
        wx.navigateTo({
             url: '../goods/goods?cid=' + cid
            //url: '../goods/goods'
        })
        console.log(e.currentTarget.dataset.cid)
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
        wx.showToast({
            title: '正在刷新数据...',
            icon: 'loading'
        })
        this.setData({Goods:[],page:1})
        this.getCateGoods(this.data.CId,1)
       // wx.stopPullDownRefresh()
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        wx.showToast({
            title: '正在加载数据...',
            icon: 'loading'
        })
        console.log(111)
        this.getCateGoods(this.data.CId, ++this.data.page)
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {

    }
})