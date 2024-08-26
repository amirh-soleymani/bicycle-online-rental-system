<?php

namespace App\Http\Controllers;

use App\Http\Requests\BicycleRentRequest;
use App\Http\Requests\BicycleSearchRequest;
use App\Http\Resources\BicycleResource;
use App\Http\Resources\RentResource;
use App\Models\Bicycle;
use App\Models\Rent;
use App\Services\BicycleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

class RentController extends Controller
{
    public function __construct(
        protected BicycleService $bicycleService
    ){
    }

    public function bicycleSearch(BicycleSearchRequest $bicycleSearchRequest)
    {
        $pickupDate = $bicycleSearchRequest->input('pickup_date');
        $returnDate = $bicycleSearchRequest->input('return_date');

        $bicycles = Bicycle::whereDoesntHave('rent',function ($query) use ($pickupDate, $returnDate) {

            $query->where(function ($q) use ($pickupDate, $returnDate){
                $q->where(function ($q1) use ($pickupDate, $returnDate) {
                    $q1->where('pickup_date', '<', $pickupDate)
                        ->where('return_date', '>', $pickupDate);
                })
                    ->orWhere(function ($q2) use ($pickupDate, $returnDate) {
                        $q2->where('pickup_date', '<', $returnDate)
                            ->where('return_date', '>', $returnDate);
                    })
                    ->orWhere(function ($q3) use ($pickupDate, $returnDate) {
                        $q3->where('pickup_date', '>=', $pickupDate)
                            ->where('return_date', '<=', $returnDate);
                    });
            });
        })->get();

        return Response::successResponse('Done', $bicycles);
    }

    public function rent(BicycleRentRequest $bicycleRentRequest)
    {
        $this->authorize('rent-bicycle');

        $pickupDate = $bicycleRentRequest->input('pickup_date');
        $returnDate = $bicycleRentRequest->input('return_date');
        $bicycleId = $bicycleRentRequest->input('bicycle_id');

        $bicycleCheck = Bicycle::where('id', $bicycleId)->whereDoesntHave('rent',function ($query) use ($pickupDate, $returnDate) {
            $query->where(function ($q) use ($pickupDate, $returnDate){
                $q->where(function ($q1) use ($pickupDate, $returnDate) {
                    $q1->where('pickup_date', '<', $pickupDate)
                        ->where('return_date', '>', $pickupDate);
                })
                    ->orWhere(function ($q2) use ($pickupDate, $returnDate) {
                        $q2->where('pickup_date', '<', $returnDate)
                            ->where('return_date', '>', $returnDate);
                    })
                    ->orWhere(function ($q3) use ($pickupDate, $returnDate) {
                        $q3->where('pickup_date', '>=', $pickupDate)
                            ->where('return_date', '<=', $returnDate);
                    });
            });
        })->first();

        if (!$bicycleCheck) {
            return Response::errorResponse('You Cannot Reserve Bicycle Now', [], 400);
        }

        $rent = Rent::create([
            'bicycle_id' => $bicycleId,
            'pickup_date' => $pickupDate,
            'return_date' => $returnDate,
            'user_id' => auth()->guard('api')->user()->id,
        ]);

        return Response::successResponse('Bicycle Reserved Successfully', RentResource::make($rent));
    }

    public function rentReportAdmin()
    {
        $this->authorize('admin-report');

        $rentsReportAdmin = Rent::all();

        return Response::successResponse('Done', RentResource::collection($rentsReportAdmin));
    }

    public function rentReportUser()
    {
        $this->authorize('member-report');

        $rentReportUser = Rent::where('user_id', auth()->guard('api')->user()->id)
            ->get();

        return Response::successResponse('Done', RentResource::collection($rentReportUser));

    }
}
