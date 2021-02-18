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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
