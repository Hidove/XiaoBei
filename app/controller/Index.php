<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function user()
    {
        return View::fetch();
    }
}
