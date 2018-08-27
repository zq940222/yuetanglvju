<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"F:\programs\yuetanglvju\public/../application/admin\view\user_withdraw\edit.html";i:1533895478;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <form class="layui-form layui-form-pane" action="" method="post" enctype="multipart/form-data">
        <table class="layui-table">
            <tr>
                <td>用户ID</td>
                <td><?php echo $data['user_id']; ?></td>
            </tr>
            <tr>
                <td>用户昵称</td>
                <td><?php echo $data['nickname']; ?></td>
            </tr>
            <tr>
                <td>申请提现金额</td>
                <td><?php echo $data['money']; ?></td>
            </tr>
            <tr>
                <td>支付宝账号</td>
                <td><?php echo $data['ali_account_num']; ?></td>
            </tr>
            <tr>
                <td>支付宝账户名</td>
                <td><?php echo $data['ali_account']; ?></td>
            </tr>
            <tr>
                <td>申请时间</td>
                <td><?php echo $data['create_time']; ?></td>
            </tr>
            <tr>
                <td>处理</td>
                <td>
                    <input type="radio" name="status" value="1" title="待审核" <?php if($data['status']==1): ?>checked<?php endif; ?>>
                    <input type="radio" name="status" value="2" title="审核通过" <?php if($data['status']==2): ?>checked<?php endif; ?>>
                    <input type="radio" name="status" value="-1" title="审核失败" <?php if($data['status']==-1): ?>checked<?php endif; ?>>
                </td>
            </tr>
            <tr>
                <td>备注</td>
                <td>
                    <textarea name="remark" required lay-verify="required" placeholder="请输入" class="layui-textarea"><?php echo $data['remark']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>提现流程</td>
                <td>
                    1:用户申请提现
                    2:管理员审核通过
                    3:进入财务待转款列表
                    4:财务管理员给用户转账(转帐时自动扣除用户平台余额)
                </td>
            </tr>
        </table>
        <input type="hidden" value="<?php echo $data['id']; ?>" name="id">
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
    layui.use(['form'],function () {
        var form = layui.form,$ = layui.jquery;

        //监听提交
        form.on('submit(*)', function(data){
            console.log(data.field);
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
    })
</script>

</html>
