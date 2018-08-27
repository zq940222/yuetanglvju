<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"F:\programs\yuetanglvju\public/../application/admin\view\index\index.html";i:1533895846;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
    <style type="text/css">
        .layui-tab-content .layui-tab-item {width:100%;height:100%;}
        .layui-tab-content iframe {width:100%;height:100%;border:none;overflow:hidden;}
    </style>
</head>
<body>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">悦棠旅居</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <!--<ul class="layui-nav layui-layout-left" lay-filter="navigation">-->
            <!--<li class="layui-nav-item"><a content="">权限管理</a></li>-->
        <!--</ul>-->
        <ul class="layui-nav layui-layout-right" lay-filter="navigation">
            <li class="layui-nav-item">
                <a content="">
                    <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
                    <?php echo session('admin.username'); ?>
                </a>
                <dl class="layui-nav-child">
                    <dd><a content="<?php echo url('admin/editPassword'); ?>">修改密码</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="<?php echo url('login/logout'); ?>">退出</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="navigation">
                <li class="layui-nav-item layui-nav-itemed">
                    <a >酒店管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('hotel/lists'); ?>">酒店列表</a></dd>
                        <dd><a content="<?php echo url('filtrate/lists'); ?>">酒店分类</a></dd>
                        <dd><a content="<?php echo url('scenicArea/lists'); ?>">景区列表</a></dd>
                        <dd><a content="<?php echo url('roomFacility/lists'); ?>">房间设施</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >商城管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('goods/lists'); ?>">商品列表</a></dd>
                        <dd><a content="<?php echo url('productCategory/lists'); ?>">商品分类</a></dd>
                        <dd><a content="<?php echo url('productType/lists'); ?>">商品模型</a></dd>
                        <dd><a content="<?php echo url('productSpec/lists'); ?>">商品规格</a></dd>
                        <dd><a content="<?php echo url('freight/lists'); ?>">运费模板</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >订单管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('order/lists'); ?>">商品订单</a></dd>
                        <dd><a content="<?php echo url('appointment/lists'); ?>">酒店订单</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >营销管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('integral/lists'); ?>">积分抽成管理</a></dd>
                        <dd><a content="<?php echo url('coupon/lists'); ?>">优惠券管理</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >用户管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('user/lists'); ?>">会员列表</a></dd>

                        <dd><a content="<?php echo url('black/lists'); ?>">黑名单</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >评价管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('hotelComment/lists'); ?>">酒店评价</a></dd>
                        <dd><a content="<?php echo url('productComment/lists'); ?>">商品评价</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >数据统计</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('statistics/hotel'); ?>">酒店数据</a></dd>
                        <dd><a content="<?php echo url('statistics/product'); ?>">商品数据</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a>财务管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('merchantsWithdrawal/lists'); ?>">酒店提现申请</a></dd>
                        <dd><a content="<?php echo url('merchantsDeposit/lists'); ?>">酒店转款列表</a></dd>
                        <dd><a content="<?php echo url('settleAccount/lists'); ?>">酒店结算记录</a></dd>
                        <dd><a content="<?php echo url('userWithdraw/lists'); ?>">会员提现申请</a></dd>
                        <dd><a content="<?php echo url('userDeposit/lists'); ?>">会员转款列表</a></dd>
                        <dd><a content="<?php echo url('expend/lists'); ?>">平台支出记录</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item">
                    <a >消息管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('advert/lists'); ?>">广告管理</a></dd>
                        <dd><a content="<?php echo url('push/lists'); ?>">历史推送</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a >版本管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('upgrade/lists'); ?>">版本升级</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item">
                    <a >权限管理</a>
                    <dl class="layui-nav-child">
                        <dd><a content="<?php echo url('admin/lists'); ?>">管理员列表</a></dd>
                        <dd><a content="<?php echo url('role/lists'); ?>">角色管理</a></dd>
                        <dd><a content="<?php echo url('authRule/lists'); ?>">权限列表</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <div class="layui-body" style="overflow:hidden;">
        <!-- 内容主体区域 -->
        <div class="layui-tab" lay-filter="tab" lay-allowClose="true" style="margin-top:0; height:100%; overflow:hidden;">
            <ul class="layui-tab-title">
                <li class="layui-this" lay-id="index">数据统计</li>
            </ul>
            <div class="layui-tab-content" style="height:87%">
                <div class="layui-tab-item layui-show">
                    <iframe src="<?php echo url('statistics/index'); ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © 悦棠旅居后台管理系统
    </div>
</div>
<script src="/static/layui.js"></script>
<script>
    //JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element, $ = layui.jquery;
        element.on('nav(navigation)', function(data) {
            var content = $(data).find('a').attr('content');
            var title = $(data).find('a').html();
            if(!$('li[lay-id="'+content+'"]').size()) {
                element.tabAdd('tab', {title:title, content:'<iframe src="'+content+'"></iframe>', id:content});
            }
            element.tabChange('tab', content);
        })

    });
</script>

</body>
</html>
