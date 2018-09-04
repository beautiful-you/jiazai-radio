// https://static.tuozhong.net/testtest/bsp.mp3   1487
// https://static.tuozhong.net/jiazaidiantaitest/bgsound.mp3   42
var util = require('../../utils/util.js');
var app = getApp();
var that; // this

Page({
    data: {
        rotateCount: 0, // 旋转次数
        play: true, // 播放状态
        animation: '',
        animationIntervalId: 0,
        report: {}, // 当前播放信息
        pre: 1, // 上一首
        next: 1, // 下一首
        audioUrl: '' // 音频路径
    },

    onLoad: function (options) {
        that = this;
        this.setData({
            audioId: options.id,
            audioType: options.type,
            audioWay: options.way,
            userInfo: app.globalData.userInfo
        });
        this.chooseInterface();
    },

    onUnload: function () {
        if (this.data.animationIntervalId != null) {
            clearInterval(this.data.animationIntervalId);
        }
        this.setData({
            animation: ''
        });
    },

    // 选择接口
    chooseInterface: function () {
        if (this.data.audioWay == 0) {
            this.getAudioInfo('api/radio/report/detail', 'GET', { report_id: this.data.audioId, type: this.data.audioType });
        } else {
            this.getAudioInfo('api/radio/my/report/see', 'GET', { uid: that.data.userInfo.id, report_id: this.data.audioId, type: this.data.audioType });
        }

        this.getCollect('api/radio/user/is/collect', 'GET', that.data.userInfo.id, this.data.audioId);
        this.getBrowse('api/radio/report/browse', 'GET', this.data.userInfo.id, this.data.audioId);
    },

    // 获得音频信息
    getAudioInfo: function (url, method, setdata) {
        util.http(app.globalData.tzBase + url, method, setdata, function (res) {
            console.log(res);
            that.setData({
                report: res.data.report,
                pre: res.data.pre,
                next: res.data.next,
                audioUrl: res.data.fileHost + res.data.report.audio,
                user: res.data.user
            });
            that.audioPlay();
        });
    },

    // 播放音频
    audioPlay: function () {
        this.setData({
            play: true
        })
        var audioUrl = this.data.audioUrl;
        wx.playBackgroundAudio({
            dataUrl: audioUrl
        });
        this.songPlay();        
        wx.onBackgroundAudioPlay(function () {
            that.songPlay();
        });
        wx.onBackgroundAudioStop(function () {
            if (that.data.animationIntervalId != null) {
                clearInterval(that.data.animationIntervalId);
            }
            that.setData({
                animation: ''
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

    // 切换音频
    otherAudio: function (e) {
        var id = 1;
        if (e.currentTarget.dataset.mes == 0) {
            id = this.data.pre;
        } else {
            id = this.data.next;
        }
        
        this.setData({
            audioId: id
        });
        if (this.data.animationIntervalId != null) {
            clearInterval(this.data.animationIntervalId);
        }
        this.setData({
            animation: ''
        });
        this.chooseInterface();
    },

    // 音频处理
    songPlay: function () {
        clearInterval(timer);
        var timer = setInterval(function () {
            wx.getBackgroundAudioPlayerState({
                success: function (res) {
                    if (res.status == 1) {
                        if(that.data.play == false){
                          that.setData({
                            play: true
                          });
                        }
                        // that.setData({
                        //     songState: {
                        //         progress: res.currentPosition / res.duration * 100, // 音频进度
                        //         currentPosition: that.timeToString(res.currentPosition), // 当前时长
                        //         duration: that.timeToString(res.duration), // 总时长
                        //         durationM: res.duration // 总时长（未处理）
                        //     }
                        // });
                    } else {
                        that.setData({
                            play: false
                        });
                        clearInterval(timer);
                    }
                }
            });
        }, 1000);
    },

    // 音频时间格式
    timeToString: function (duration) {
        var str = '';
        var minute = parseInt(duration / 60) < 10
            ? ('0' + parseInt(duration / 60))
            : (parseInt(duration / 60));
        var second = duration % 60 < 10
            ? ('0' + duration % 60)
            : (duration % 60);
        str = minute + ':' + second;
        return str;
    },

    // 浏览记录
    getBrowse: function (url, method, userid, reportid){
        var that = this;
        util.http(app.globalData.tzBase + url, method, { uid: userid, report_id: reportid }, function (data) {
            console.log(data.data);
        });
    },

    onColletionTap: function (event) {
        var reportId = this.data.audioId;
        util.http(app.globalData.tzBase + 'api/radio/report/collect', 'GET', { uid: that.data.userInfo.id, report_id: reportId }, function (data) {
            that.setData({
                collected: data.data.status
            });
            that.showToast(data.data.msg);
        });
    },

    showToast: function (msg) {
        wx.showToast({
            title: msg,
            duration: 1000,
            icon: "success"
        });
    },

    // 收藏
    getCollect: function (url, method, userid, reportid) {
        util.http(app.globalData.tzBase + url, 'GET', { uid: userid, report_id: reportid }, function (data) {
            console.log('是否收藏' + data.data.status);
            that.setData({
                collected: data.data.status
            });
        });
    },

    onShareAppMessage: function (res) {
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
