<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"F:\programs\yuetanglvju\public/../application/admin\view\hotel\add.html";i:1531128650;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
        <label class="layui-form-label">账号</label>
        <div class="layui-input-inline">
            <input type="text" name="account" lay-verify="required"  placeholder="请输入账号" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
            <span style="color: red">*</span>将会成为您唯一的登入名
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" lay-verify="required"  placeholder="请输入密码" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">酒店名</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required"  placeholder="请输入酒店名" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
    </form>
</div>

</body>
<script src="/static/layui.js"></script>

<script>
    layui.use('form', function(){
        var form = layui.form, $ = layui.jquery;

        //监听提交
        form.on('submit(*)', function(data){
            $.ajax({
                url:'add',
                type:'post',
                data:data.field,
                success:function () {
                    layer.alert('添加成功', {icon: 6},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                },
                error:function (error) {
                    layer.msg(error.responseJSON.msg);
                }
            })
            return false;
        });
    });
</script>

</html>
