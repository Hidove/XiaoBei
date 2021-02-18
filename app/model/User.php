<?php
declare (strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    protected $autoWriteTimestamp = true;

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }
}
