<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarEstimate extends Model
{
    use HasFactory;

    /**
     * 모델과 연결된 테이블
     *
     * @var string
     */
    protected $table = 'car_estimates';

    /**
     * 대량 할당이 가능한 속성
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'payment',
        'takeover',
        'totalAmount',
        'distance',
        'em',
        'ins',
        'penalty',
        'deposit',
        'pre_exp',
        'subsidy',
        'version',
        'trim_code',
        'corp_code'
    ];

}
