<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"F:\programs\yuetanglvju\public/../application/admin\view\coupon\add.html";i:1533887535;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
</head>
<body>

<div class="layui-anim layui-anim-up layui-field-box">
    <form class="layui-form" action="<?php echo url('add'); ?>" method="post" enctype="multipart/form-data">
        <div class="layui-form-item">
            <label class="layui-form-label">优惠券标题</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" placeholder="请输入优惠券标题" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">可领取时间</label>
            <div class="layui-input-block">
                <input type="text" name="date" lay-verify="required" class="layui-input" id="date" name="date" placeholder="请选择日期" readonly>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">过期天数</label>
            <div class="layui-input-inline">
                <input type="number" name="day" lay-verify="required" placeholder="领取后过期天数" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span>天</span>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">满减金额</label>
            <div class="layui-inline">
                <div class="layui-form-mid layui-word-aux">
                    <span style="color: red">满</span>
                </div>
                <div class="layui-input-inline">
                    <input type="number" name="money_off" lay-verify="required" placeholder="满多少可用" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span style="color: red">减</span>
                </div>
                <div class="layui-input-inline">
                    <input type="number" name="money" lay-verify="required" placeholder="满减金额" class="layui-input">
                </div>
            </div>


        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即发布</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>

</body>
<script src="/static/layui.js"></script>

<script>
    layui.use(['form','laydate'], function () {
        var form = layui.form,laydate = layui.laydate, $ = layui.jquery;

        //监听提交
        form.on('submit(*)', function (data) {
            $.ajax({
                url: 'add',
                type: 'post',
                data: data.field,
                success: function (data) {
                    var code = data.code.toString();
                    var startChar = code.charAt(0);
                    if (startChar == '2') {
                        layer.alert(data.msg, {icon: 6}, function () {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                    } else {
                        layer.alert(data.msg, {icon: 7});
                    }
                },
                error: function (error) {
                    layer.msg(error.responseJSON.msg);
                }
            })
            return false;
        });
        laydate.render({
            elem: '#date' //指定元素
            ,type: 'date'
            ,trigger: 'click'
            ,lang: 'cn'
            //,lang: 'en'
            ,range: true //开启日期范围，默认使用“_”分割
            ,done: function(value, date, endDate){
                console.log(value, date, endDate);
            }
            ,change: function(value, date, endDate){
                console.log(value, date, endDate);
            }
        });
    });
</script>

</html>
