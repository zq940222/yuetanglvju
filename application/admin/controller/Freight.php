<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/18
 * Time: 11:12
 */

namespace app\admin\controller;


use app\admin\model\FreightTemplate;
use app\admin\model\Region;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;
use think\Db;

class Freight extends BaseController
{
    public function lists()
    {
        $FreightTemplate = new FreightTemplate();
        $template_list = $FreightTemplate->with(['freightConfig'])->select();
        $this->assign('template_list', $template_list);
        return $this->fetch();
    }

    public function info()
    {
        return $this->fetch();
    }

    public function edit()
    {
        $template_id = input('template_id');
        $FreightTemplate = new FreightTemplate();
        $freightTemplate = $FreightTemplate->with(['freightConfig','freightConfig.freightRegion','freightConfig.freightRegion.region'])->find($template_id);
        $this->assign('freightTemplate', $freightTemplate);
        return $this->fetch();
    }

    /**
     *  保存运费模板
     * @throws \think\Exception
     */
    public function save()
    {
        $template_id = input('template_id/d');
        $template_name = input('template_name/s');
        $is_enable_default = input('is_enable_default/d');
//        $config_list = input('config_list/a', []);
        $area_ids = input('area_ids/a',[]);
        $config_id = input('config_id/a',[]);
        $continue_money = input('continue_money/a',[]);
        $continue_unit = input('continue_unit/a',[]);
        $first_money = input('first_money/a',[]);
        $first_unit = input('first_unit/a',[]);
        $is_default = input('is_default/a',[]);
        if (empty($template_id)) {
            //添加模板
            $freightTemplate = new FreightTemplate();
        } else {
            //更新模板
            $freightTemplate = FreightTemplate::get($template_id);
        }
        $freightTemplate->template_name = $template_name;
        $freightTemplate->is_enable_default = $is_enable_default;
        $freightTemplate->save();
        //清空原始信息
        if($template_id) {
            Db::name('freight_region')->where(['template_id' => ['=', $template_id]])->delete();
            Db::name('freight_config')->where(['template_id' => ['=', $template_id]])->delete();
        }
        $config_list_count = count($is_default);
        if ($config_list_count > 0) {
            for ($i = 0; $i < $config_list_count; $i++) {
                $freight_config_data = [
                    'first_unit' => $first_unit[$i],
                    'first_money' => $first_money[$i],
                    'continue_unit' => $continue_unit[$i],
                    'continue_money' => $continue_money[$i],
                    'template_id' => $freightTemplate->id,
                    'is_default' => $is_default[$i]
                ];

                //新增配送区域
                $config_id = Db::name('freight_config')->insertGetId($freight_config_data);
                $area_id_arr = explode(',', $area_ids[$i]);
                foreach ($area_id_arr as $areaKey => $areaVal) {
                    Db::name('freight_region')->insert(['template_id'=>$freightTemplate->id,'config_id' => $config_id, 'region_id' => $areaVal]);
                }
            }
        }
        $this->checkFreightTemplate($freightTemplate->id);
        return json(new SuccessMessage(['msg' => '保存成功']));
    }

    /**
     * 删除运费模板
     * @throws \think\Exception
     */
    public function delete()
    {
        $template_id = input('template_id');
        $action = input('action');
        if (empty($template_id)) {
            throw new AdminException(['msg' => '参数错误']);
        }
        if ($action != 'confirm') {
            $goods_count = model('Product')->where(['template_id' => $template_id])->count();
            if ($goods_count > 0) {
                return json(['status' => -1, 'msg' => '已有' . $goods_count . '种商品使用该运费模板，确定删除该模板吗？继续删除将把使用该运费模板的商品设置成包邮。', 'result' => '']);
            }
        }
        model('Product')->where(['template_id' => $template_id])->update(['template_id' => 0, 'is_free_shipping' => 1]);
        model('FreightRegion')->where(['template_id' => $template_id])->delete();
        model('FreightConfig')->where(['template_id' => $template_id])->delete();
        $delete = model('FreightTemplate')->where('id',$template_id)->delete();
        if ($delete !== false) {
            return json(['status' => 1, 'msg' => '删除成功', 'result' => '']);
        } else {
            return json(['status' => 0, 'msg' => '删除失败', 'result' => '']);
        }
    }


    public function area()
    {
        $province_list = Db::name('region')->where(array('parent_id' => 100000, 'level_type' => 1))->select();
        $this->assign('province', $province_list);
        return $this->fetch();
    }

    /**
     * 检查模板，如果模板下没有配送区域配置，就删除该模板
     * @param $template_id
     */
    private function checkFreightTemplate($template_id)
    {
        $freight_config = Db::name('freight_config')->where(['template_id' => $template_id])->find();
        if (empty($freight_config)) {
            Db::name('freight_template')->where('template_id', $template_id)->delete();
        }
    }

    public function getRegion()
    {
        $id = input('post.id');
        $region = Region::getRegion($id);
        return $region;
    }

}