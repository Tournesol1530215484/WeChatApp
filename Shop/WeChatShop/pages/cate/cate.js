// pages/cate/cate.js
const util = require('../../utils/util.js')
const app=getApp();
Page({

    /**
     * 页面的初始数据
     */
    data: {
        Cate:[],
        CateInfo: [],
        CateSonInfo:[],
        Starst:1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
       
        //var that=this
        //var Star = that.data.Starst
         //加载顶级栏目
        this.getTopCate()
        //加载当前栏目的信息
        this.getLoadCate()
        //加载当前栏目的子栏目信息
        this.getChildCate()
    },

    //加载顶级栏目
        getTopCate:function(){
            //var Star = that.data.Starst
            var url = app.globalData.ApiUrl+'/api/banner/gettopCate'
            var params=''
            util.wxRequest(url, params, success => {
                this.setData({ Cate: success.data })
            }, data => { }, data => { })
        },

    //加载当前栏目
        getLoadCate:function(){
            var Star = this.data.Starst
            var url = app.globalData.ApiUrl +'/api/banner/getCurrentCate'
            var params = { id: Star};
            util.wxRequest(url, params, success => {
                console.log(success.data)
                this.setData({ CateInfo: success.data })
            }, data => { }, data => { })
        },    

    //加载当前栏目的子栏目
    getChildCate: function () {
        var Star = this.data.Starst
        var url = app.globalData.ApiUrl +'/api/banner/getCateChild'
        var params = { pid: Star}
        util.wxRequest(url, params, success => {
            this.setData({ CateSonInfo: success.data })
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

    },

    /**
     * 点击切换左侧栏目
     */

    ChangCate:function(e){
        // console.log(e.currentTarget.dataset.id)
        this.setData({
            Starst: e.currentTarget.dataset.id
        })
        var that = this
        var Star = that.data.Starst
        
        //加载当前栏目的信息
        this.getLoadCate()

        //加载当前栏目的子栏目信息
        this.getChildCate()
    },

    /**
     *所属栏目的 商品列表
     */

    goodslist:function(e){
        var gid = e.currentTarget.dataset.gid
        wx.navigateTo({
            url: '../goodslist/goodslist?gid='+gid
        })
        console.log(e.currentTarget.dataset.gid)
    }
})







