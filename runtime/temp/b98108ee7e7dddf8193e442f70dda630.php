<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"F:\programs\yuetanglvju\public/../application/admin\view\hotel\detail.html";i:1533286288;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
</head>
<body>

<fieldset class="layui-elem-field">
    <legend>基本信息</legend>
    <div class="layui-field-box">
        <table class="layui-table">
            <tbody>
            <tr>
                <th>酒店名称</th>
                <td><?php echo $hotel['name']; ?></td>
            </tr>
            <tr>
                <th>头部展示图片</th>
                <td><img src="<?php echo $hotel['image']['url']; ?>" alt=""></td>
            </tr>
            <tr>
                <th>起价</th>
                <td><?php echo $hotel['min_price']; ?></td>
            </tr>
            <tr>
                <th>地址</th>
                <td>
                    <?php echo !empty($hotel['province']['name'])?$hotel['province']['name']:''; ?>,<?php echo !empty($hotel['city']['name'])?$hotel['city']['name']:''; ?>,<?php echo !empty($hotel['district']['name'])?$hotel['district']['name']:''; ?>,<?php echo $hotel['detail_address']; ?>
                </td>
            </tr>
            <tr>
                <th>备注</th>
                <td><?php echo $hotel['remark']; ?></td>
            </tr>
            <tr>
                <th>景区</th>
                <td><?php echo $hotel['scenic_area']['name']; ?></td>
            </tr>
            <tr>
                <th>联系电话</th>
                <td><?php echo $hotel['phone']; ?></td>
            </tr>
            <tr>
                <th>营业执照</th>
                <td><img src="<?php echo $hotel['business_license']['url']; ?>" alt=""></td>
            </tr>
        </table>

    </div>
    <form class="layui-form">
        <input type="hidden" name="id" value="<?php echo $hotel['id']; ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">操作</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="0" title="申请中" checked>
                <input type="radio" name="status" value="1" title="同意">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</fieldset>


</body>
<script src="/static/layui.js"></script>

<script>
    layui.use(['layer', 'table', 'laydate', 'form'], function(){
        var layer = layui.layer, table = layui.table, $ = layui.jquery, laydate = layui.laydate, form = layui.form;
        //监听提交
        form.on('submit(*)', function (data) {

            $.ajax({
                url: 'detail',
                type: 'post',
                data: data.field,
                success: function () {
                    layer.alert('操作成功', {icon: 6}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                },
                error: function (error) {
                    layer.msg(error.responseJSON.msg);
                }
            })
            return false;
        });
    });
</script>

</html>
