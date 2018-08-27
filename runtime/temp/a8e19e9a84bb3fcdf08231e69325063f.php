<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:75:"F:\programs\yuetanglvju\public/../application/admin\view\integral\edit.html";i:1533962443;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
</head>
<body>

<blockquote class="layui-elem-quote">
    <i class="layui-icon" style="font-size: 24px;">&#xe62d;</i>
    <span style="font-size:18px;">编辑积分</span>
    <a href="<?php echo url('lists'); ?>" class="layui-btn layui-btn-sm" style="float:right;">返回列表</a>
</blockquote>
<form class="layui-form layui-form-pane" action="" method="post" enctype="multipart/form-data">
    <div class="layui-form-item">
        <label class="layui-form-label">积分百分比</label>
        <div class="layui-input-inline">
            <input type="text" name="integral_ratio" lay-verify="required" required placeholder="请输入获得积分百分比" class="layui-input" value="<?php echo $data['integral_ratio']; ?>">
        </div>
        <div class="layui-form-mid layui-word-aux">
            %
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">抽成百分比</label>
        <div class="layui-input-inline">
            <input type="text" name="royalty_ratio" lay-verify="required" required placeholder="请输入抽成百分比" class="layui-input" value="<?php echo $data['royalty_ratio']; ?>">
        </div>
        <div class="layui-form-mid layui-word-aux">
            %
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

</body>
<script src="/static/layui.js"></script>

<script>
    layui.use('form', function(){
        var form = layui.form, $ = layui.jquery;
        //监听提交
        form.on('submit(*)', function(data){
            $.ajax({
                url:'',
                type:'post',
                data:data.field,
                success:function (data) {
                    var code = data.code.toString();
                    var startChar = code.charAt(0);
                    if (startChar == '2')
                    {
                        layer.alert(data.msg, {icon: 6},function () {
                            location.href="../../lists"
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
