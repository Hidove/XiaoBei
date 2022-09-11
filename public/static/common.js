function msg(message) {
    const params = {
        message: message,
        timeout: 3000,
        position: 'right-top',
    };
    mdui.snackbar(params)
}

const $message = {

    success: (message) => {
        mdui.snackbar({
            message: message,
            timeout: 3000,
            position: 'right-top',
        })
    },
    error: (message) => {
        mdui.alert(message)
    }
}
const $request = {
    get: (url, options) => {
        return axios.get(url, options).then(res => res.data).catch(e => $message.error(e.message))
    },
    post: (url, options) => {
        return axios.post(url, options).then(res => res.data).catch(e => $message.error(e.message))
    }
}

//日期转换
function date_chage(timestamp) {
    const date = new Date(timestamp * 1000);
    let Y = date.getFullYear() + '-';
    let M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
    let D = (date.getDate() + 1 < 10 ? '0' + date.getDate() : date.getDate()) + ' ';
    let h = (date.getHours() + 1 < 10 ? '0' + date.getHours() : date.getHours()) + ':';
    let m = (date.getMinutes() + 1 < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
    let s = (date.getSeconds() + 1 < 10 ? '0' + date.getSeconds() : date.getSeconds());
    return Y + M + D + h + m + s;
}

//获取日期
function get_date(date) {
    let Y = date.getFullYear() + '年';
    let M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
    let D = (date.getDate() + 1 < 10 ? '0' + date.getDate() : date.getDate());
    return Y + M + D;
}

function format_ago(value) {
    let theTime = parseInt(value);// 需要转换的时间秒
    let theTime1 = 0;// 分
    let theTime2 = 0;// 小时
    let theTime3 = 0;// 天
    if (theTime > 60) {
        theTime1 = parseInt(theTime / 60);
        theTime = parseInt(theTime % 60);
        if (theTime1 > 60) {
            theTime2 = parseInt(theTime1 / 60);
            theTime1 = parseInt(theTime1 % 60);
            if (theTime2 > 24) {
                //大于24小时
                theTime3 = parseInt(theTime2 / 24);
                theTime2 = parseInt(theTime2 % 24);
            }
        }
    }
    let result = '';

    if (theTime3 > 0) {
        return parseInt(theTime3) + "天";
    }
    if (theTime2 > 0) {
        return parseInt(theTime2) + "小时" + result;
    }
    if (theTime1 > 0) {
        return parseInt(theTime1) + "分" + result;
    }
    if (theTime > 0) {
        return parseInt(theTime) + "秒";
    }
}

function is_same_day(startTime, endTime) {
    const startTimeMs = new Date(startTime).setHours(0, 0, 0, 0);
    const endTimeMs = new Date(endTime).setHours(0, 0, 0, 0);
    return startTimeMs === endTimeMs
}
