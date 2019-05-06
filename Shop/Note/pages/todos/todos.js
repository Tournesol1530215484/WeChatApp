// pages/todos/todos.js
Page({

    /**
     * 页面的初始数据
     */
    data: {
        addinput:"",
        todos:[
            // { content:'早上六点起床打灰机',status:true },
            // { content: '早上七点吃饭', status: false },
            // { content: '早上十点工作', status: false },
            // { content: '中午十二点午休', status: false },
            // { content: '晚上五点下班', status: false }
        ],
        contens:0,
        startStatus:false,
        show:false
    },

    // 获取input输入框数据,并赋值给addinput
    inputHadnle:function(e){
        // console.log(e)
        this.setData({ addinput: e.detail.value})
    },
    //添加数据到dodos里面
    addHadnle:function(e){
        var addinput=this.data.addinput
        if (!addinput) {
            wx: wx.showToast({
                title: '请不要输入空值',
                icon: '',
                image: '../../images/waring.png',
                duration: 2000,
                // mask: true,
                // success: function(res) {},
                // fail: function(res) {},
                // complete: function(res) {},
            })
            return
        }
        var todos=this.data.todos  
        var contens = ++this.data.contens      
        todos.push({ content:this.data.addinput,status:false })
        this.setData({ todos: todos, addinput: '', contens: contens,show:true})
    },

    //添加待办事项
    iconLeft:function(e){
        var id = e.currentTarget.id
        var todos=this.data.todos[id]
        todos.status=!todos.status
        if (todos.status){
            this.setData({ contens: this.data.contens -1 })
        }else{
            this.setData({ contens: this.data.contens + 1 })
        }
        this.setData({ todos: this.data.todos,show:true})
    },
    //移除事件
    iconright:function(e){
        console.log(e);
        var id = e.currentTarget.id
        var todos = this.data.todos
        todos.splice(id,1)

        //计算contens
        var contens=0
        for(var i=0;i<todos.length;i++){
            if(todos[i].status){
                ++contens
            }
        }
        if (todos.length<=0){
            this.setData({ show:false })
        }
        this.setData({ todos: todos, contens: contens})
    },

    //全选
    selectHadnle:function(e){
        var todos = this.data.todos;
        if (this.data.startStatus==false){  //全部勾选
            todos.forEach(item=>{
                item.status=true
            })
            this.setData({ todos: todos, startStatus: true, contens:todos.length })
        }else{
            todos.forEach(item => {
                item.status = false
            })
            this.setData({ todos: todos, startStatus: false, contens: 0 })
        }
    },

    //清空

    clearHadnle:function(e){
        var todos = this.data.todos;
        this.setData({ todos: null, contens: 0, startStatus: false,show:false})
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {

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