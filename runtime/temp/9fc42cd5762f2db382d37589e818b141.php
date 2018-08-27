<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"F:\programs\yuetanglvju\public/../application/admin\view\goods\edit.html";i:1533721013;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <form class="layui-form layui-form-pane" action="<?php echo url('edit'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $product['id']; ?>" name="id">
        <div class="layui-form-item">
            <label class="layui-form-label">商品名称</label>
            <div class="layui-input-inline">
                <input type="text" name="title" lay-verify="required"  placeholder="请输入商品名称" class="layui-input" value="<?php echo $product['title']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品副标题</label>
            <div class="layui-input-inline">
                <input type="text" name="subhead" lay-verify="required"  placeholder="请输入商品副标题" class="layui-input" value="<?php echo $product['subhead']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品品牌</label>
            <div class="layui-input-inline">
                <input type="text" name="brand" lay-verify="required"  placeholder="请输入商品品牌" class="layui-input" value="<?php echo $product['brand']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品分类</label>
            <div class="layui-input-inline">

                <select name="category_id" lay-verify="category" lay-filter="category" lay-search="">
                    <option value="0"> --商品分类-- </option>
                    <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($product['category_id']==$item['id']): ?>selected<?php endif; ?>>
                    <?php if($item['grade']==2): ?>----<?php elseif($item['grade'] == 3): ?>--------<?php endif; ?><?php echo $item['name']; ?>
                    </option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面图片</label>
            <div class="layui-input-inline" style="width:80%;">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="upload">单图片上传</button>
                    <div class="layui-upload-list" style="padding-left:110px;">
                        <img class="layui-upload-img" src="<?php echo $product['cover_img']['url']; ?>" id="showImg" width="80">
                        <input type="hidden" name="cover_img" id="path">
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品相册</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="upload2">多图片上传</button>
                    <div class="layui-upload-list" id="showImg2">
                        <?php if(is_array($product['image']) || $product['image'] instanceof \think\Collection || $product['image'] instanceof \think\Paginator): $i = 0; $__LIST__ = $product['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <img src="<?php echo $item['url']; ?>" alt="" width="50px">
                        <button type="button" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger del_img" data-id="<?php echo $item['id']; ?>">
                            <i class="layui-icon">&#xe640;</i> 删除
                        </button>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">商品库存</label>
            <div class="layui-input-inline">
                <input type="text" name="store" lay-verify="required|number" required placeholder="请输入商品库存" class="layui-input" value="<?php echo $product['store']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品价格</label>
            <div class="layui-input-inline">
                <input type="text" name="min_price" lay-verify="required|number" required placeholder="请输入商品价格" class="layui-input" value="<?php echo $product['min_price']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否销售</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="上架" <?php if($product['status']==1): ?>checked<?php endif; ?>>
                <input type="radio" name="status" value="0" title="下架" <?php if($product['status']==0): ?>checked<?php endif; ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span style="color: red">*</span>请编辑完整商品模型再上架商品
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否推荐</label>
            <div class="layui-input-block">
                <input type="radio" name="is_recommend" value="1" title="是" <?php if($product['is_recommend']==1): ?>checked<?php endif; ?>>
                <input type="radio" name="is_recommend" value="0" title="否" <?php if($product['is_recommend']==0): ?>checked<?php endif; ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否免运费</label>
            <div class="layui-input-inline">
                <input type="checkbox" <?php if($product['is_free_shipping']==1): ?> checked <?php endif; ?> name="is_free_shipping" lay-skin="switch" lay-filter="switch" value="1" lay-text="ON|OFF">
            </div>
            <div class="layui-input-inline">
                <select name="template_id" lay-verify="template" lay-search="" id="template" <?php if($product['is_free_shipping']==1): ?>disabled<?php endif; ?>>
                    <option value="0">-请选择运费模板-</option>
                    <?php if(is_array($tempList) || $tempList instanceof \think\Collection || $tempList instanceof \think\Paginator): $i = 0; $__LIST__ = $tempList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($item['id']==$product['template_id']): ?>selected<?php endif; ?>><?php echo $item['template_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品详情</label>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="upload3">多图片上传</button>
                    <div class="layui-upload-list" id="showImg3">
                        <?php if(is_array($product['product_detail']) || $product['product_detail'] instanceof \think\Collection || $product['product_detail'] instanceof \think\Paginator): $i = 0; $__LIST__ = $product['product_detail'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <img src="<?php echo $item['url']; ?>" alt="" width="50px">
                        <button type="button" class="layui-btn layui-btn-sm layui-btn-radius layui-btn-danger del_img" data-id="<?php echo $item['id']; ?>">
                            <i class="layui-icon">&#xe640;</i> 删除
                        </button>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
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
    layui.use(['form', 'upload'], function(){
        var form = layui.form, $ = layui.jquery, upload = layui.upload;
        $(".del_img").on('click',function () {
            $this = $(this);
            var id = $(this).attr('data-id');
            $.ajax({
                url:'del_img',
                type:'post',
                data:{id:id},
                success:function (data) {
                    if (data.errorCode == 0 ) {
                        $this.prev('img').remove()
                        $this.remove();
                        layer.msg('已删除')
                    }
                }
            })
        })
        form.on('switch(switch)',function (data) {
            if (data.elem.checked){
                $("#template").attr('disabled',true);
                form.render('select')
            }else{
                $("#template").attr('disabled',false);
                form.render('select')
            }
        })
        // 自定义验证规则
        form.verify({
            category:function(value, item) {
                if(value==0) {
                    return '请选择商品分类';
                }
            }
        });
        form.verify({
            template:function(value, item) {
                if(value == 0 && $("#template").attr('disabled')==false){
                    return '请选择运费模板';
                }
            }
        })
        //监听提交
        form.on('submit(*)', function(data) {
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
        upload.render({
            elem: '#upload2'
            ,url: '<?php echo url("upload/upload"); ?>'
            ,multiple: true
            ,size: 1024
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#showImg2').append('<img src="'+ result +'" alt="'+ file.name +'"  class="layui-upload-img" width="100px" style="margin-left:10px;">')
                });
            }
            ,done: function(res){
                //上传完毕
                $('#showImg2').append('<input type="hidden" name="image[]" value="'+res.data.src+'">');
            }
        });
        upload.render({
            elem: '#upload3'
            ,url: '<?php echo url("upload/upload"); ?>'
            ,multiple: true
            ,size: 1024
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#showImg3').append('<img src="'+ result +'" alt="'+ file.name +'"  class="layui-upload-img" width="100px" style="margin-left:10px;">')
                });
            }
            ,done: function(res){
                //上传完毕
                $('#showImg3').append('<input type="hidden" name="detail_image[]" value="'+res.data.src+'">');
            }
        });
    });

</script>

</html>
