<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"F:\programs\yuetanglvju\public/../application/admin\view\filtrate\lists.html";i:1531360450;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <span style="font-size:18px;">分类列表</span>
    <a href="<?php echo url('filtrate/add'); ?>" class="layui-btn layui-btn-sm" style="float:right;">添加分类</a>
</blockquote>
<table class="layui-table" lay-filter="table">
    <thead>
    <tr>
        <th lay-data="{checkbox:true}">ID</th>
        <th lay-data="{field:'name'}">名称</th>
        <th lay-data="{field:'sort'}">排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($cateList) || $cateList instanceof \think\Collection || $cateList instanceof \think\Paginator): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
    <tr>
        <td><?php echo $item['id']; ?></td>
        <td>
            <?php if($item['parent_id']>0): ?>--<?php endif; ?>
            <?php echo $item['name']; ?>
        </td>

        <td><?php echo $item['sort']; ?></td>
        <td>
            <a href="<?php echo url('edit', 'id='.$item['id']); ?>" class="layui-btn layui-btn-sm"><i class="layui-icon">&#xe642;</i></a><a  cid="<?php echo $item['id']; ?>" class="delete layui-btn layui-btn-sm layui-btn-danger" data-method="confirmTrans"><i class="layui-icon">&#xe640;</i></a>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>

</body>
<script src="/static/layui.js"></script>

<script>
    layui.use(['layer'], function(){
        var layer = layui.layer;
        var $ = layui.jquery;
        $('.delete').bind('click', function() {
            var id=$(this).attr('cid');
            layer.confirm('确认删除此分类以及所有子分类吗?', function(index){
                $.post('<?php echo url("delete"); ?>', {id:id}, function(data) {
                    if (data.code){
                        layer.close(index);
                        layer.msg('已删除');
                        window.location.reload()
                    }else{
                        layer.msg('删除失败');
                    }
                });
            });
        });
    });
</script>

</html>
