<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarEstimate;

class EstimateController extends Controller
{
    /**
     * 견적 데이터를 반환하는 API
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEstimate(Request $request)
    {
        // 1. 요청 데이터 유효성 검사
        $validator = Validator::make($request->all(), [
            // 공통
            'trimCode' => 'required|integer',
            'corpCode' => 'required|array',
            'corpCode.*' => 'string',
            'fee' => 'required|integer',
            'period' => 'required|integer|in:36,48,60', // 36, 48, 60 중 하나
            'deposit' => 'required|integer|in:0,10,20,30,40', // 0, 10, 20, 30, 40 중 하나
            'prepaid' => 'required|integer|in:0,10,20,30,40', // 0, 10, 20, 30, 40 중 하나
            'optionPrice' => 'required|integer|min:0', // 0 이상
            'discount' => 'required|integer',
            'factory_trans_fee' => 'required|integer',
            'distance' => 'required|string|in:1만km,2만km,3만km', // 특정 문자열만 허용
            'prodType' => 'required|string|in:R,L', // 'R' 또는 'L'만 허용

            // 렌트
            'trans_arr' => 'required|string',
            'tint_f' => 'required|string|in:미포함,포함',
            'tint_s' => 'required|string|in:미포함,포함',
            'blackbox' => 'required|string|in:미포함,포함',
            'ins_age' => 'required|string|in:26세,21세',
            'ins_add' => 'required|string|in:1억,2억,3억,5억', // 예시 값
            'maintenance' => 'required|string|in:정비제외,정비포함', // 예시 값
            'em' => 'required|string|in:10만,20만,30만,50만',

            // 리스
            'cartax' => 'required|string|in:포함,제외',
            'bond' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '유효하지 않은 요청 데이터입니다.',
                'errors' => $validator->errors()
            ], 400);
        }

        // 2. JWT 토큰 및 OAuth2.0 Bearer 스키마 확인
        // 라우트 미들웨어에서 처리되므로, 여기서는 별도 코드가 필요하지 않습니다.

        // 3. 비즈니스 로직
        // - 요청 데이터를 기반으로 MySQL에서 견적 데이터를 조회
        // - 예시: CarEstimate 모델을 사용하여 데이터 필터링
        $estimates = CarEstimate::where('trim_code', $request->input('trimCode'))
                                 ->whereIn('corp_code', $request->input('corpCode'))
                                 ->get();

        // 4. 응답 데이터 구조화
        $responseData = [
            'success' => true,
            'data' => [
                'count' => $estimates->count(),
                'subsidy' => 0, // 예시 값
                'fuel_type' => 'HEV', // 예시 값
                'items' => $estimates->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'code' => $item->code,
                        'payment' => $item->payment,
                        // ... (데이터베이스 필드에 맞춰 필요한 데이터를 매핑)
                    ];
                })->toArray()
            ]
        ];

        return response()->json($responseData, 200);
    }
}