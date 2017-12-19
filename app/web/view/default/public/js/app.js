mui.init({
    swipeBack:true //启用右滑关闭功能
});
//获得slider插件对象
var gallery = mui('.mui-slider');
gallery.slider({
    interval:3000//自动轮播周期，若为0则不自动播放，默认为0；
});
