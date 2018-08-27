<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:83:"F:\programs\yuetanglvju\public/../application/admin\view\product_category\edit.html";i:1533709171;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <span style="font-size:18px;">编辑分类</span>
    <a href="<?php echo url('lists'); ?>" class="layui-btn layui-btn-sm" style="float:right;">返回列表</a>
</blockquote>
<form class="layui-form layui-form-pane" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
    <div class="layui-form-item">
        <label class="layui-form-label">分类名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" required placeholder="请输入分类名称" class="layui-input" value="<?php echo $category['name']; ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">分类排序</label>
        <div class="layui-input-block">
            <input type="text" name="sort" class="layui-input" lay-verify="number" value="<?php echo $category['sort']; ?>">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属分类</label>
        <div class="layui-input-inline">
            <select name="parent_id" lay-filter="parent_id" lay-search="">
                <option value="0"> 一级分类 </option>
                <?php if(is_array($cateList) || $cateList instanceof \think\Collection || $cateList instanceof \think\Paginator): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $item['id']; ?>" data-grade="<?php echo $item['grade']; ?>" <?php if($category['parent_id']==$item['id']): ?>selected<?php endif; ?>>
                <?php if($item['grade']==2): ?>----<?php elseif($item['grade'] == 3): ?>--------<?php endif; ?><?php echo $item['name']; ?>
                </option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="layui-form-mid layui-word-aux" id="showCateMsg">默认为一级分类</div>
        <input type="hidden" name="grade" value="<?php echo $category['grade']; ?>" id="grade">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图标</label>
        <div class="layui-input-inline" style="width:80%;">
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="upload">单图片上传</button>
                <div class="layui-upload-list" style="padding-left:110px;">
                    <img class="layui-upload-img" src="<?php echo $category['image']['url']; ?>" id="showImg" width="80">
                    <input type="hidden" name="image" id="path">
                </div>
            </div>
            <div class="layui-form-mid layui-word-aux">一级分类和二级分类不需要传图标</div>
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
    layui.use(['form','upload'], function(){
        var form = layui.form,upload = layui.upload, $ = layui.jquery;
        form.on('select(parent_id)', function(data) {
            if(data.value>0) {
                var grade = $(data.elem).find(':selected').data('grade');
                var catename=$(data.elem).find(':selected').text();
                $('#showCateMsg').html(catename+'的子分类');
                $("#grade").val(grade+1);
            } else {
                $('#showCateMsg').html('一级分类');
                $("#grade").val(1);
            }
        });
        //监听提交
        form.on('submit(*)', function(data){
            console.log(data.field)
            $.ajax({
                url:'<?php echo url("edit"); ?>',
                type:'post',
                data:data.field,
                success:function () {
                    layer.alert('编辑成功', {icon: 6},function () {
                        location.href="../../lists"
                    });
                },
                error:function (error) {
                    layer.msg(error.responseJSON.msg);
                }
            })
            return false;
        });
        upload.render({
            elem: '#upload'
            ,url: '<?php echo url("upload/upload"); ?>'
            ,multiple: false
            ,size: 1024
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#showImg').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg('上传失败');
                }
                //上传成功
                $('#path').val(res.data.src); // 将上传后的图片路径赋值给隐藏域
            }
        });
    });
</script>

</html>
