<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"F:\programs\yuetanglvju\public/../application/admin\view\role\edit.html";i:1533715224;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <form class="layui-form" action="<?php echo url('edit'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $group['id']; ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required"  placeholder="请输入角色名称" class="layui-input" value="<?php echo $group['title']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色描述</label>
            <div class="layui-input-block">
                <textarea name="intro" required lay-verify="required" placeholder="请输入角色描述" class="layui-textarea"><?php echo $group['intro']; ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限分配</label>
            <div class="layui-input-block">
                <?php if(is_array($rule) || $rule instanceof \think\Collection || $rule instanceof \think\Paginator): $i = 0; $__LIST__ = $rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <input type="checkbox" name="rule[]" title="<?php echo $item['title']; ?>" value="<?php echo $item['id']; ?>" lay-skin="primary" <?php if(in_array($item['id'],$group['rules'])): ?>checked<?php endif; ?>>
                <?php endforeach; endif; else: echo "" ;endif; ?>
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
                url:'edit',
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
