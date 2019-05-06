//引入公共方法
const test = require("../../utils/util.js")
//定义常量
const extraLine=[]
const initDate = 'this is first line\nthis is second line'
const btnType=['default','primary','warn']

// pages/ceshi/ceshi.js
Page({
    // onReady(e) {
    //     this.cxready = wx.createAudioContext('myAudio')
    //     // this.audioCtx = wx.createAudioContext('myAudio')
    // },
  /**
   * 页面的初始数据
   */
  data: {
     // name:'王彬你真帅',
    //   id:"2",
    //  condition:true,
    //   a:1,
    //   b:2,
    //   c:3,
    //   obj:{
    //     name:"王彬",
    //     age:22
    //   },
    //   arr:[1,2,3,4,5,6,7,8,9],
    //   icontype:['success','success_no_circle','info','warn','waiting','cancel','download','search','clear'],
    //   typeSize:[23,30,40,50,60],
    //   iconColor: ['red', 'orange', 'yellow', 'green', 'rgb(0,255,255)', 'blue', 'purple'],
    //   textcontent: initDate,
    //   defaultSize: 'default',
    //   primarySize: 'default',
    //   warnSize: 'default',
    //   disabled: false,
    //   plain: false,
    //   loading: false,
    //   items: [
    //       { name: 'USA', value: '美国' },
    //       { name: 'CHN', value: '中国', checked: 'true' },
    //       { name: 'BRA', value: '巴西' },
    //       { name: 'JPN', value: '日本' },
    //       { name: 'ENG', value: '英国' },
    //       { name: 'TUR', value: '法国' },
    //   ],
    //   array: ['美国', '中国', '巴西', '日本'],
    //   multiArray: [['无脊柱动物', '脊柱动物'], ['扁性动物', '线形动物', '环节动物', '软体动物', '节肢动物'], ['猪肉绦虫', '吸血虫']],
    //   data:"2019-4-2",
    //   start:"00:00",
    //   end:"24:00",
    //   time:"12:01",

    //   src: 'http://ws.stream.qqmusic.qq.com/M500001VfvsJ21xFqb.mp3?guid=ffffffff82def4af4b12b3cd9337d5e7&uin=346897220&vkey=6292F51E1E384E06DCBDC9AB7C49FD713D632D313AC4858BACB8DDD29067D3C601481D36E62053BF8DFEAF74C0A5CCFADD6471160CAF3E6A&fromtag=46',
    //   loop:true,
    //   poster:'http://y.gtimg.cn/music/photo_new/T002R300x300M000003rsKF44GyaSk.jpg?max_age=2592000',
    //   name:'此时此刻',
    //   author:'王彬',

    markers:[{
        id: 0,
        latitude: "30.465711",
        longitude: "114.279125",
        width: 50,
        height: 50,
        title:"1号标点"
    }]

      
  },

    // addlin---text模拟函数
//     addlin:function(e){
//         extraLine.push("this is a new line");
//         this.setData({
//             textcontent:initDate+'\n'+extraLine.join('\n')
//         })
//   },
//     dellin:function(e){
//         if(extraLine.length>0){
//             extraLine.pop();
//             this.setData({
//                 textcontent:initDate+'\n'+extraLine.join('\n')
//             })
//         }
//     },

//设置button

    // SetDefault:function(e){
    //         this.setData({
    //             disabled:!this.data.disabled
    //         })
    // },
    // Setwarn: function (e) {
    //     this.setData({
    //         plain:!this.data.plain
    //     })
    // },
    // Setprimary: function (e) {
    //     this.setData({
    //         loading: !this.data.loading
    //     })
    // },

//checked设置记录
    // checkboxChange:function(e){
    //     console.log("有改动，改的的值是" ,e.detail.value)
    //     this.setData({
    //         index:e.detail.value
    //     })
    // },

    // databindChange:function(e){
    //     console.log("有改动，改的的值是", e.detail.value)
    //     this.setData({
    //         data:e.detail.value
    //     })
    // },
    // timebindChange:function(e){
    //     console.log("有改动，改的的值是", e.detail.value)
    //     this.setData({
    //         time:e.detail.value
    //     })
    // },

    //设置记录函数
    // radioChange:function(e){
    //     console.log("发生了点击事件",e.detail.value);
    // } ,

    //设置记录拖动


    // bindChange:function(e){
    //     console.log("发生了拖动事件", e.detail.value);
    // },

    // //设置播放
    // audioPlay:function(e){
    //     //console.log(this.cxready)
    //     this.cxready.play()
    // },

    // //拍照片
    // takePhoto() {
    //     const ctx = wx.createCameraContext()
    //     ctx.takePhoto({
    //         quality: 'high',
    //         success: (res) => {
    //             this.setData({
    //                 src: res.tempImagePath
    //             })
    //         }
    //     })
    // },
    // error(e) {
    //     console.log(e.detail)
    // },

   
    
  
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