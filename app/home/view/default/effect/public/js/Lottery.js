/**
 * Created by Administrator on 2017/9/6.
 */
var groupMap = {};

groupMap['group1'] = ['唐僧', '孙悟空', '猪八戒', '沙和尚', '白龙马', '白骨精', '牛魔王'];
groupMap['group2'] = ['宋江', '林冲', '武松', '鲁智深', '卢俊义', '李逵'];
groupMap['group3'] = ['贾宝玉', '林黛玉', '王熙凤', '薛宝钗', '史湘云', '秦可卿'];
groupMap['group4'] = ['刘备', '关羽', '张飞', '曹操', '诸葛亮', '孙权', '吕布'];

groupMap['group_all'] = [].concat(groupMap['group1'],
    groupMap['group2'],
    groupMap['group3'],
    groupMap['group4']);

var lotteryTimer = null;
var imageNode = document.getElementById('image');
var nameNode = document.getElementById('name');
var audioNode = document.getElementById('audio');

function myFunction() {
    clearInterval(lotteryTimer);
    var groups = document.getElementsByName('group');
    var name = nameNode.innerHTML;
    for (var i = 0; i < groups.length; i++) {
        if (groups[i].checked) {
            console.debug('checked group is: %s', groups[i].id);
            var group = groupMap[groups[i].id];
            var index = group.indexOf(name);
            console.debug('index is %s', index);
            group.splice(index, 1);

            console.debug('new group is: %s', groupMap[groups[i].id].toString());
            break;
        }
    }
    audioNode.pause();
    lotteryTimer = null;
}

function begin() {
    if (lotteryTimer != null) {
        return;
    }
    var groups = document.getElementsByName('group');
    var group = [];
    for (var i = 0; i < groups.length; i++) {
        if (groups[i].checked) {
            group = groupMap[groups[i].id];
        }
    }
    console.debug(group);
    audioNode.play();
    lotteryTimer = setInterval(function () {
        var index = Math.floor((Math.random() * group.length));
        var name = group[index];
        var photoIndex = Math.floor((Math.random() * 26));
        imageNode.src = 'photo/' + photoIndex + '.jpg';
        nameNode.innerHTML = name;
    }, 100);
}