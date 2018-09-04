var util = require('../../utils/util.js');
var app = getApp();
var that;

Page({

    data: {
        play: false,
        type: false,
        rotateCount: 0
    },

    onLoad: function (options) {
        that = this;
        wx.showLoading({
            title: '加载中'
        });
        that.getAudioInfo();
    },

    onShow: function () {
        this.audioPause();
    },

    onHide: function () {
        this.audioPause();
    },

    getAudioInfo: function () {
        util.http(app.globalData.tzBase + 'api/radio/find/report', 'GET', {}, function (data) {
            wx.hideLoading();
            that.setData({
                find: data.data.report[0],
            });
            if (that.data.type) {
                that.audioPlay();
            }
            that.setData({
                type: true
            });
        });
    },

    // 播放音频
    audioPlay: function () {
        this.setData({
            play: true
        })
        var audioUrl = 'https://jiazaidata.oss-cn-shanghai.aliyuncs.com/' + this.data.find.audio;
        wx.playBackgroundAudio({
            dataUrl: audioUrl
        });
        wx.onBackgroundAudioStop(function () {
            that.setData({
                play: false
            });
        });
        var animation = wx.createAnimation({
            duration: 100,
        });
        this.data.animationIntervalId = setInterval(function () {
            that.setData({
                animation: animation.rotate(++that.data.rotateCount).step().export()
            });
        }, 100);
    },

    // 暂停音频
    audioPause: function () {
        this.setData({
            play: false
        });
        wx.getBackgroundAudioPlayerState({
            success: function (res) {
                var status = res.status;
                if (status == 1) {
                    wx.pauseBackgroundAudio();
                }
            }
        });
        if (this.data.animationIntervalId != null) {
            clearInterval(this.data.animationIntervalId);
        }
        this.setData({
            animation: ''
        });
    },

    toNext: function () {
        if (this.data.animationIntervalId != null) {
            clearInterval(this.data.animationIntervalId);
        }
        this.setData({
            animation: ''
        });
        this.getAudioInfo();
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