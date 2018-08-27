<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"F:\programs\yuetanglvju\public/../application/admin\view\statistics\index.html";i:1533802938;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <span style="vertical-align: inherit;">欢迎管理员：</span>
    <span class="x-red"><?php echo session('admin.username'); ?></span>
    <span style="vertical-align: inherit;">！当前时间：<?php echo date('Y-m-d H:i:s'); ?></span>
</blockquote>
<fieldset class="layui-elem-field">
    <legend>数据统计</legend>
    <div class="layui-field-box">
        <div class="layui-container">
            <div class="layui-row">
                <div class="layui-col-md2" style="padding: 20px;" align="center">
                    <div class="layui-card" style="border: rgba(71,120,209,0.13) 1px solid;">
                        <div class="layui-card-header layui-bg-gray">入驻酒店数量</div>
                        <div class="layui-card-body">
                            <span style="font-size: large;color: green"><?php echo $data['hotelCount']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md2" style="padding: 20px" align="center">
                    <div class="layui-card" style="border: rgba(71,120,209,0.13) 1px solid;">
                        <div class="layui-card-header layui-bg-gray">注册用户数</div>
                        <div class="layui-card-body">
                            <span style="font-size: large;color: green"><?php echo $data['userCount']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md2" style="padding: 20px" align="center">
                    <div class="layui-card" style="border: rgba(71,120,209,0.13) 1px solid;">
                        <div class="layui-card-header layui-bg-gray">商品数量</div>
                        <div class="layui-card-body">
                            <span style="font-size: large;color: green"><?php echo $data['productCount']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md2" style="padding: 20px" align="center">
                    <div class="layui-card" style="border: rgba(71,120,209,0.13) 1px solid;">
                        <div class="layui-card-header layui-bg-gray">酒店订单数量</div>
                        <div class="layui-card-body">
                            <span style="font-size: large;color: green"><?php echo $data['hotelOrderCount']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md2" style="padding: 20px" align="center">
                    <div class="layui-card" style="border: rgba(71,120,209,0.13) 1px solid;">
                        <div class="layui-card-header layui-bg-gray">产品订单数量</div>
                        <div class="layui-card-body">
                            <span style="font-size: large;color: green"><?php echo $data['productOrderCount']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend>系统信息</legend>
    <div class="layui-field-box">
        <table class="layui-table">
            <tbody><tr>
                <td class="gray_bg">服务器操作系统:</td>
                <td><?php echo $sys_info['os']; ?></td>
                <td class="gray_bg">服务器域名/IP:</td>
                <td><?php echo $sys_info['domain']; ?> [ <?php echo $sys_info['ip']; ?> ]</td>
            </tr>
            <tr>
                <td class="gray_bg">服务器环境:</td>
                <td><?php echo $sys_info['web_server']; ?></td>
                <td class="gray_bg">PHP 版本:</td>
                <td><?php echo $sys_info['phpv']; ?></td>
            </tr>
            <tr>
                <td class="gray_bg">Mysql 版本:</td>
                <td><?php echo $sys_info['mysql_version']; ?></td>
                <td class="gray_bg">GD 版本:</td>
                <td><?php echo $sys_info['gdinfo']; ?></td>
            </tr>
            <tr>
                <td class="gray_bg">文件上传限制:</td>
                <td><?php echo $sys_info['fileupload']; ?></td>
                <td class="gray_bg">最大占用内存:</td>
                <td><?php echo $sys_info['memory_limit']; ?></td>
            </tr>
            <tr>
                <td class="gray_bg">最大执行时间:</td>
                <td><?php echo $sys_info['max_ex_time']; ?></td>
                <td class="gray_bg">安全模式:</td>
                <td><?php echo $sys_info['safe_mode']; ?></td>
            </tr>
            <tr>
                <td class="gray_bg">Zlib支持:</td>
                <td><?php echo $sys_info['zlib']; ?></td>
                <td class="gray_bg">Curl支持:</td>
                <td><?php echo $sys_info['curl']; ?></td>
            </tr>
            </tbody></table>
    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend>开发团队</legend>
    <div class="layui-field-box">
        <table class="layui-table">
            <tbody>
            <tr>
                <th>版权所有</th>
                <td>杭州牧马人科技有限公司</td>
            </tr>
            <tr>
                <th>开发者</th>
                <td>张强(229230041@qq.com)</td></tr>
            </tbody>
        </table>
    </div>
</fieldset>




</body>
<script src="/static/layui.js"></script>

</html>
