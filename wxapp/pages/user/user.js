var util = require('../../utils/util.js');
var app = getApp();

Page({
    data: {
        switchId: 1,
        userInfo: null,
        hisRecord: '', //浏览历史
        colRecord: '' //收藏记录
    },

    onLoad: function (options) {
        this.setData({
            userInfo: app.globalData.userInfo
        });
    },
    onHide: function () {
        this.setData({
            hisRecord: null,
            colRecord: null
        })
    },
    onShow: function () {
        wx.showLoading({
            title: '加载中',
        });
        var that = this; 
        util.http(app.globalData.tzBase + 'api/radio/my/browse', 'GET', { uid: this.data.userInfo.id }, function (data) {
            console.log(data.data);
            that.setData({
                hisRecord: data.data.browse,
            });
            wx.hideLoading();
        });
        util.http(app.globalData.tzBase + 'api/radio/my/collection', 'GET', { uid: this.data.userInfo.id }, function (data) {
            console.log(data.data);
            that.setData({
                colRecord: data.data.collection,       
            });
            wx.hideLoading();
        });
    },

    userswitch: function (event) {
        var id = event.currentTarget.dataset.id;
        this.setData({
            switchId: id
        })
    },

    // startluyin: function(){
    //     var that = this;
    //     wx.startRecord({
    //         success: function (res) {
    //         var tempFilePath = res.tempFilePath
    //         console.log(tempFilePath);
    //         wx.playVoice({
    //             filePath: tempFilePath,
    //             success: function () {
    //             console.log(33333);
    //             }
    //         })
    //         wx.uploadFile({
    //             url: 'https://api.rux.cn/api/test/image', 
    //             filePath: tempFilePath,
    //             name: 'smfile',
    //             header: {
    //             "Content-Type": "multipart/form-data"
    //             },
    //             success: function (res) {
    //                 var data = res.data
    //                 //do something
    //                 console.log(data);
    //                 },fail: function (){

    //                 }
    //             })
    //         },
    //         fail: function (res) {
    //         //录音失败
    //         }
    //     })
    //     setTimeout(function () {
    //         //结束录音  
    //         wx.stopRecord()
    //     }, 10000)
    // },
    toListen: function (e) {
        var typeId = this.data.switchId;
        wx.navigateTo({
            url: '../listen/listen?id=' + e.currentTarget.dataset.id + '&type=' + typeId + '&way=1'
        });
    },
    
    onShareAppMessage: function () {
        return {
            title: '嘉仔',
            path: '/pages/radio/radio',
            success: function (res) {
                // 转发成功
                console.log(res);
            },
            fail: function (res) {
                // 转发失败
            }
        }
    }
})