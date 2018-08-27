<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:76:"F:\programs\yuetanglvju\public/../application/admin\view\integral\lists.html";i:1532480890;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
</head>
<body>

<table class="layui-table" lay-filter="table">
    <thead>
    <tr>
        <th lay-data="{field:'integral_ratio'}">获取积分百分比(%)</th>
        <th lay-data="{field:'royalty_ratio'}">抽成百分比(%)</th>
        <th lay-data="{field:'update_time'}">上次修改时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <?php echo $data['integral_ratio']; ?>
        </td>
        <td>
            <?php echo $data['royalty_ratio']; ?>
        </td>
        <td><?php echo $data['update_time']; ?></td>
        <td>
            <a href="<?php echo url('edit', 'id='.$data['id']); ?>" class="layui-btn layui-btn-sm"><i class="layui-icon">&#xe642;</i></a>
        </td>
    </tr>
    </tbody>
</table>

</body>
<script src="/static/layui.js"></script>

</html>
