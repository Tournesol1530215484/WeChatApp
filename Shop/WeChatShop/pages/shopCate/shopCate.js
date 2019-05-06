// pages/shopCate/shopCate.js
const util = require('../../utils/util.js')
const app = getApp()
Page({

    /**
     * 页面的初始数据
     */
    data: {
        Sales: 1,    //购物选项
        SalesStatus: 'disabled', 
        windowHeight:'',
        Goods:[],   //购物车商品
        emptyCart:true, //购物车是否为空
        selectAll:false,
        totalprice:0,       //商品总价
        SelectGoods:[], //选中的商品
        
    },

    /**
     * 点击立即结算
     */
    Settlement:function(e){
        var that=this
        var Goods = that.data.Goods
        var totalprice = that.data.totalprice
       // var SelectGoods = that.data.SelectGoods
        var SelectGoods=new Array();
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        for (var i in Goods) {
            if (Goods[i]['selected'] == 1) {
                SelectGoods.push(Goods[i]['goodsid'])
               // that.setData({ SelectGoods: Goods })
            }
        }
            
        wx.navigateTo({
            url: './settlement?uid=' + uid + '&SelectGoods=' + SelectGoods + '&totalprice=' + totalprice,
        })
        console.log(e)
    },


    /**
     * 点击添加点击添加
     */
    addSales:function(e){
        var that=this
        var request='add'
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var num=e.currentTarget.dataset.num
        var index = e.currentTarget.dataset.index
        var Goods=that.data.Goods
        var goodsid = Goods[index]['goodsid']
        //var sales = that.data.Sales
        var SalesStatus = that.data.SalesStatus
        Goods[index]['num'] = ++num
        if (Goods[index]['num'] >21){
            that.setData({ SalesStatus: 'disabled' })
        }else{
            //数据同步到服务器
            var url = app.globalData.ApiUrl + '/api/Banner/Goodsnum'
            var params = { uid: uid, token: token, goodsid: goodsid,num: Goods[index]['num'] }
            util.wxRequest(url, params, success => {
                if (success.code == 200) {
                    console.log(success.data)
                    that.setData({ Goods: Goods, SalesStatus: '' })
                    that.Allprice()
                    // that.setData({ selectAll: success.data })
                    // console.log(that.data.selectAll)
                   // that.setData({ Goods: Goods, SalesStatus: '' })
                } else {
                    wx.showToast({
                        title: success.msg,
                        icon: 'error',
                        duration: 2000
                    })
                }

            }, data => { }, data => { })     
        }

        
       
    },

    /**
     * 点击进行减少
     */
    rmSales:function(e){
        var that = this
        var request='del'
        var index = e.currentTarget.dataset.index
        var num = e.currentTarget.dataset.num
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var Goods = that.data.Goods
        var goodsid = Goods[index]['goodsid']
        if (Goods[index]['num']<=1){
            that.setData({ SalesStatus: 'disabled' })
        }else{
            Goods[index]['num']=--num
            //数据同步到服务器
            var url = app.globalData.ApiUrl + '/api/Banner/Goodsnum'
            var params = { uid: uid, token: token, goodsid: goodsid, num: Goods[index]['num'] }

            util.wxRequest(url, params, success => {
                if (success.code == 200) {
                    console.log(success.data)
                    
                    // that.setData({ selectAll: success.data })
                    // console.log(that.data.selectAll)
                    that.setData({ Goods: Goods, SalesStatus: '' })
                    that.Allprice()
                } else {
                    wx.showToast({
                        title: success.msg,
                        icon: 'error',
                        duration: 2000
                    })

                }

            }, data => { }, data => { })
        }
    },


    /**
     *点击删除购物车数据
     */

    delSales:function(e){
        var that=this
        var index = e.currentTarget.dataset.index
        var num = e.currentTarget.dataset.num
        var Goods=that.data.Goods
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var goodsid=Goods[index]['goodsid']
        var emptyCart = that.data.emptyCart
        //数据同步到服务器
        var url = app.globalData.ApiUrl + '/api/Banner/delGoodsCart'
        var params = { uid: uid, token: token, goodsid: goodsid }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                wx.showToast({
                    title: success.msg,
                    icon: 'success',
                    duration: 2000
                })
                that.Allprice()
                that.onLoad()
               
            } else if (success.code == 201)  {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
               
                //that.Allprice()
                that.setData({ emptyCart:true })
                that.onLoad()
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
     * 购物车的选择---普通选择
     */

    Select:function(e){
        var that=this
        var index = e.currentTarget.dataset.index
        var selectAll = that.data.selectAll

         var Goods=that.data.Goods
        //console.log(Goods[index]['selected'])
        if (Goods[index]['selected']==1){
            Goods[index]['selected']=0
            that.setData({ Goods:Goods })
        }else{
            Goods[index]['selected'] = 1
            that.setData({ Goods: Goods })
        }
         // 同步到服务端
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var goodsid = Goods[index]['goodsid']
        var url = app.globalData.ApiUrl + '/api/Banner/ShopCartSelect'
        var params = { uid: uid, token: token, goodsid: goodsid }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                that.setData({ selectAll: success.data })
                that.Allprice()
               // console.log(that.data.selectAll)

            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
                
            }
            console.log(success)

        }, data => { }, data => { })
       
        
    },


    /**
     * 
     * 购物车选择-----全选和反选
     */
    SelectAll:function(){
        var that=this
        var Goods=that.data.Goods
        var selectAll = that.data.selectAll
        for(var i in Goods){
            if (selectAll){
                Goods[i]['selected']=1
                that.setData({ Goods: Goods })
            }else{
                Goods[i]['selected'] = 0
                that.setData({ Goods: Goods })
            }
        }
        // 同步到服务端
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/ShopCartSelectAll'
        var params = { uid: uid, token: token, selectAll: selectAll }
    
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                if (success.data=='false'){
                    that.setData({ selectAll: true })
                }else{
                    that.setData({ selectAll: false })
                }

                that.Allprice()

            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })

            }
           // console.log(success)

        }, data => { }, data => { })
     
    },

  
    
    

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var that=this
        that.ShopCart();
        wx.getSystemInfo({
            success: function (res) {
                let clientHeight = res.windowHeight,
                    clientWidth = res.windowWidth,
                    rpxR = 750 / clientWidth;
                var calc = clientHeight * rpxR;
                that.setData({
                    windowHeight: calc - 220
                });
            }
        });

        //计算总价格
        that.Allprice();
        
       
        
    },

    /**
     * 计算屏幕高度
     */

    //计算屏幕高度
    windowHeight:function(){
        
    },

    /**
   * 计算购物车商品总价
   */
    Allprice: function () {
        var that = this
        var totalprice = 0
        var Goods = that.data.Goods
        //console.log(Goods)
        for (var i = 0; i < Goods.length; i++) {
           if(Goods[i]['selected']==1){
               totalprice += Goods[i]['shop_price'] * Goods[i]['num']
           }
        }
        that.setData({ totalprice: totalprice })
    },

    /**
     * 手动修改购物车数量
     */

    Changenum:function(e){
        var that=this
        var Goods = that.data.Goods
        var num = e.detail.value
        var index = e.currentTarget.dataset.index
        var goodsid = Goods[index]['goodsid']
        //修改界面显示
        Goods[index]['num'] = num
        that.setData({ Goods: Goods })
        //同步到服务器
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var url = app.globalData.ApiUrl + '/api/Banner/ShopCartnum'
        var params = { uid: uid, token: token, goodsid: goodsid, num: num}
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                console.log(success)
                that.Allprice()
            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })

            }
            // console.log(success)

        }, data => { }, data => { })

        

        // console.log(e.detail.value)

    },



    /**
     * 获取购物车里面的商品信息
     */

    ShopCart:function(){
        var emptyCart=this.data.emptyCart
        var uid = app.globalData.userInfo.id
        var token = app.globalData.token
        var selectAll = this.data.selectAll
        var url = app.globalData.ApiUrl + '/api/Banner/ShopCart'
        var params = { uid: uid,token: token }
        util.wxRequest(url, params, success => {
            if (success.code == 200) {
                console.log(success)
                this.setData({ Goods: success.data.carlist, emptyCart:false,  selectAll: success.data.resultstatus })
            } else {
                wx.showToast({
                    title: success.msg,
                    icon: 'error',
                    duration: 2000
                })
                this.setData({ emptyCart: true })
            }
            console.log(success)
        
        }, data => { }, data => { })
    },

    /**
     * 空购物车进行跳转
     */
    jumpIndex:function(){
        wx.switchTab({
            url: '../index/index'
        })
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
       // this.Allprice()
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