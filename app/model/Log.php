<?php
declare (strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Log extends Model
{
    protected $autoWriteTimestamp = true;
    protected $readonly = ['create_time'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
