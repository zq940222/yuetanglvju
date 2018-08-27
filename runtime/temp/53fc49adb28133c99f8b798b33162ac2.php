<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:79:"F:\programs\yuetanglvju\public/../application/admin\view\product_spec\edit.html";i:1531383790;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <form class="layui-form layui-form-pane" action="<?php echo url('add'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $spec['id']; ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">规格名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required"  placeholder="请输入规格名称" class="layui-input" value="<?php echo $spec['name']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所属模型</label>
            <div class="layui-input-inline">
                <select name="type_id" lay-verify="product_type" lay-filter="product_type" lay-search="">
                    <option value="0"> -请选择商品模型- </option>
                    <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): $i = 0; $__LIST__ = $typeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($spec['type_id']==$item['id']): ?>selected<?php endif; ?>> <?php echo $item['name']; ?> </option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">规格项</label>
            <textarea name="items" required lay-verify="required" placeholder="请输入规格项" class="layui-textarea"><?php echo $spec['items']; ?></textarea>
            <div class="layui-form-mid layui-word-aux" id="showCateMsg">一行为一个规格项，多个规格项用换行输入</div>
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
                success:function () {
                    layer.alert('编辑成功', {icon: 6},function () {
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
