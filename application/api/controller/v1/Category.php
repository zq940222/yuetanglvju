<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/4
 * Time: 9:53
 */

namespace app\api\controller\v1;

use app\api\model\ProductCategory;
use app\lib\exception\CategoryException;

class Category
{
    public function getAllCategories()
    {
        $categories = ProductCategory::getCategoryByTree();

        if (!$categories) {
            throw new CategoryException();
        }

        return $categories;
    }
}