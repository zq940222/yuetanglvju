<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"F:\programs\yuetanglvju\public/../application/admin\view\push\add.html";i:1533715224;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="required"  placeholder="请输入标题" class="layui-input">
        </div>

    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea name="content" required lay-verify="required" placeholder="请输入" class="layui-textarea"></textarea>
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
    layui.use('form', function(){
        var form = layui.form, $ = layui.jquery;

        //监听提交
        form.on('submit(*)', function(data){
            $.ajax({
                url:'add',
                type:'post',
                data:data.field,
                success:function (data) {
                    var code = data.code.toString();
                    var startChar = code.charAt(0);
                    if (startChar == '2')
                    {
                        layer.alert(data.msg, {icon: 6},function () {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert(data.msg, {icon: 7});
                    }
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