/**
 * Created by Administrator on 2017-09-14.
 */
var num = 0;
var api = {
    "jiekou1": "http://www.wmxz.wang/video.php?url=",
}

function Clo() {
    document.getElementById("video_iframe").src = "http://ww3.sinaimg.cn/large/006Bo8Q9jw1fadd8rydyrj30m80dwgv9.jpg";
}

function Tip() {
    //alert("提交视频地址后，按All+数字1-6选择解析接口");
    document.getElementById('link').focus();
}

function Keytest(event) {
    if (event.keyCode == 13) {
        //alert("提交视频地址后，按Alt+数字1-6选择解析接口");
        document.getElementById('link').focus();
    }
    if (event.keyCode == 18) {
        num = 1
    }

    if (num == 1) {
        if (event.keyCode == 49 | event.keyCode == 97) {
            var link = document.getElementById('link').value;
            document.getElementById("video_iframe").src = 'http://www.wmxz.wang/video.php?url=' + link;
            num = 0;
        }

        if (event.keyCode == 50 | event.keyCode == 98) {
            var link = document.getElementById('link').value;
            document.getElementById("video_iframe").src = 'http://mt2t.com/yun?url=' + link;
            num = 0;
        }

        if (event.keyCode == 51 | event.keyCode == 99) {
            var link = document.getElementById('link').value;
            document.getElementById("video_iframe").src = 'http://vip.sdyhy.cn/ckflv/?url=' + link;
            num = 0;
        }

        if (event.keyCode == 52 | event.keyCode == 100) {
            var link = document.getElementById('link').value;
            document.getElementById("video_iframe").src = 'http://player.gakui.top/?url=' + link;
            num = 0;
        }

        if (event.keyCode == 53 | event.keyCode == 101) {
            var link = document.getElementById('link').value;
            document.getElementById("video_iframe").src = 'http://www.vipjiexi.com/yun.php?url=' + link;
            num = 0;
        }

        if (event.keyCode == 54 | event.keyCode == 102) {
            var link = document.getElementById('link').value;
            document.getElementById("video_iframe").src = 'http://www.xiguaso.com/api/index.php?url=' + link;
            num = 0;
        }
    }

}