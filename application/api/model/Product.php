<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/1
 * Time: 15:13
 */

namespace app\api\model;


use app\api\service\GoodsLogic;
use think\Cache;
use think\Db;
use think\Request;

class Product extends BaseModel
{
    protected $hidden = ['category_id','cover_img_id','is_recommend','status','create_time','update_time','delete_time'];

    public function coverImage()
    {
        return $this->belongsTo('Image','cover_img_id','id');
    }

    public function image()
    {
        return $this->belongsToMany('Image','ProductImg','img_id','product_id');
    }

    public function productDetail()
    {
        return $this->belongsToMany('Image','ProductDetailImg','img_id','product_id')->order('sort asc');
    }

    public function specProductPrice()
    {
        return $this->hasMany('SpecProductPrice','product_id','id');
    }

    public static function getProducts($page = 1, $size = 10, $keyword = '', $cateID = 0)
    {
        $where = [];
        if ($keyword) {
            $where['title|subhead|brand'] = ['like',"%$keyword%"];
        }
        if ($cateID) {
            $where['category_id'] = ['=',$cateID];
        }
        $where['status'] = ['=',1];
        $data = self::with('coverImage')
            ->order('update_time desc')
            ->where($where)
            ->paginate($size, true, ['page' => $page]);
        return $data;
    }

    public static function getProductsBySalesVolume($page = 1, $size = 10, $keyword = '', $cateID = 0)
    {
        $where = [];
        if ($keyword) {
            $where['title|subhead|brand'] = ['like',"%$keyword%"];
        }
        if ($cateID) {
            $where['category_id'] = ['=',$cateID];
        }
        $where['status'] = ['=',1];
        $data = self::with('coverImage')
            ->order('num_sold desc')
            ->where($where)
            ->paginate($size, true, ['page' => $page]);
        return $data;
    }

    public static function getProductsByPriceAsc($page = 1, $size = 10, $keyword = '', $cateID = 0)
    {
        $where = [];
        if ($keyword) {
            $where['title|subhead|brand'] = ['like',"%$keyword%"];
        }
        if ($cateID) {
            $where['category_id'] = ['=',$cateID];
        }
        $where['status'] = ['=',1];
        $data = self::with('coverImage')
            ->order('min_price asc')
            ->where($where)
            ->paginate($size, true, ['page' => $page]);
        return $data;
    }

    public static function getProductsByPriceDesc($page = 1, $size = 10, $keyword = '', $cateID = 0)
    {
        $where = [];
        if ($keyword) {
            $where['title|subhead|brand'] = ['like',"%$keyword%"];
        }
        if ($cateID) {
            $where['category_id'] = ['=',$cateID];
        }
        $where['status'] = ['=',1];
        $data = self::with('coverImage')
            ->order('min_price desc')
            ->where($where)
            ->paginate($size, true, ['page' => $page]);
        return $data;
    }

    public static function getDetail($id)
    {
        $dataArray = self::with(['coverImage','image', 'productDetail', 'specProductPrice'])
            ->find($id);

        $goodslogic = new GoodsLogic();
        $spec = $goodslogic->get_spec($id);
        $dataArray['spec'] = $spec;
        $productComment = ProductComment::with(['user', 'user.headimg'])
            ->order('create_time desc')
            ->where('product_id', '=', $id)
            ->limit(2)
            ->select();
        foreach ($productComment as &$value)
        {
            if (!$value['user']['headimg'])
            {
                $value['user']['headimg'] = '';
            }
        }
        $dataArray['product_comment'] = $productComment;
        //查询收藏
        $is_attention = 0;
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if ($vars) {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            $user_id = $vars['uid'];
            $attention = Db::name('user_product_attention')
                ->where('user_id', '=', $user_id)
                ->where('product_id', '=', $id)
                ->find();
            if ($attention) {
                $is_attention = 1;
            }
        }
        $dataArray['is_attention'] = $is_attention;
        return $dataArray;
    }
}