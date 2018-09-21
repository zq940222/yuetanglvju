<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/12
 * Time: 19:23
 */

namespace app\admin\service;


class Product
{
    /**
     * 获取 规格的 笛卡尔积
     * @param $goods_id 商品 id
     * @param $spec_arr 笛卡尔积
     * @return string 返回表格字符串
     */
    public function getSpecInput($product_id, $spec_arr)
    {
        // 排序
        foreach ($spec_arr as $k => $v)
        {
            $spec_arr_sort[$k] = count($v);
        }
        asort($spec_arr_sort);
        foreach ($spec_arr_sort as $key =>$val)
        {
            $spec_arr2[$key] = $spec_arr[$key];
        }


        $clo_name = array_keys($spec_arr2);
        $spec_arr2 = combineDika($spec_arr2); //  获取 规格的 笛卡尔积

        $spec = model('Spec')->order('id asc')->where('id','in',$clo_name)->column('id,name'); // 规格表
        $specItem = model('SpecItem')->column('id,item,spec_id');//规格项
        $keySpecGoodsPrice = model('SpecProductPrice')->where('product_id','=',$product_id)->column('key,key_name,price,store,image');//规格项
        $str = "<table class='layui-table' id='spec_input_tab'>";
        $str .="<tr>";
        // 显示第一行的数据
        foreach ($clo_name as $k => $v)
        {
            $str .=" <td><b>{$spec[$v]}</b></td>";
        }
        $str .="<td><b>价格</b></td>
               <td><b>库存</b></td>
               <td><b>图片</b></td>
               <td><b>操作</b></td>
             </tr>";
        // 显示第二行开始
        foreach ($spec_arr2 as $k => $v)
        {
            $str .="<tr>";
            $item_key_name = [];
            foreach($v as $k2 => $v2)
            {
                $str .="<td>{$specItem[$v2]['item']}</td>";
                $item_key_name[$v2] = $spec[$specItem[$v2]['spec_id']].':'.$specItem[$v2]['item'];
            }
            ksort($item_key_name);
            $item_key = implode('_', array_keys($item_key_name));
            if(!array_key_exists($item_key,$keySpecGoodsPrice)) {
                $keySpecGoodsPrice[$item_key]["price"] = 0;
                $keySpecGoodsPrice[$item_key]["store"] = 0;
                $keySpecGoodsPrice[$item_key]["image"] = '';
                $keySpecGoodsPrice[$item_key]["src"] = '';
            }else{
                $keySpecGoodsPrice[$item_key]["src"] = config('setting.img_prefix').$keySpecGoodsPrice[$item_key]["image"];
            }
            $str .="<td><input type='text' name='item[$item_key][price]' value='{$keySpecGoodsPrice[$item_key]["price"]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
            $str .="<td><input type='text' name='item[$item_key][store]' value='{$keySpecGoodsPrice[$item_key]["store"]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";
            $str .="<td><a class='layui-btn layui-btn-sm layui-icon upload'>&#xe67c;</a><img src='{$keySpecGoodsPrice[$item_key]["src"]}' width='50px'><input type='hidden' name='item[$item_key][image]' value='{$keySpecGoodsPrice[$item_key]["image"]}'></td>";
            $str .="<td><button type='button' class='layui-btn layui-btn-radius layui-btn-danger delete_item'>无效</button></td>";
            $str .="</tr>";
        }
        $str .= "</table>";
        return $str;
    }

}