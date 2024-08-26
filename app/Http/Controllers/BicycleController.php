<?php

namespace App\Http\Controllers;

use App\Http\Requests\BicycleRequest;
use App\Http\Requests\BicycleUpdateRequest;
use App\Http\Resources\BicycleResource;
use App\Models\Bicycle;
use App\Services\BicycleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class BicycleController extends Controller
{

    public function __construct(
        protected BicycleService $bicycleService
    ){
    }

    public function index()
    {
        $this->authorize('index', Bicycle::class);

        $bicycles = $this->bicycleService->index();

        return Response::successResponse('Done', BicycleResource::collection($bicycles));
    }

    public function store(BicycleRequest $bicycleRequest)
    {
        $this->authorize('store', Bicycle::class);

        $imageRequest = $bicycleRequest->file('image');
        $imageExtension = $bicycleRequest->image->getClientOriginalExtension();
        $imageName = time() . '.' . $imageExtension;
        $imageRequest->storeAs('/uploads/bicycleImages/', $imageName);
        $imagePath = '/uploads/bicycleImages/' . $imageName;

        $bicycleData = [
            'brand' => $bicycleRequest->input('brand'),
            'model' => $bicycleRequest->input('model'),
            'color' => $bicycleRequest->input('color'),
            'prod_year' => $bicycleRequest->input('prod_year'),
            'image' => $imagePath
        ];

        $bicycle = $this->bicycleService->store($bicycleData);

        return Response::successResponse('Bicycle Created Successfully.', BicycleResource::make($bicycle));
    }

    public function show($id)
    {
        $this->authorize('show', Bicycle::class);

        $bicycle = $this->bicycleService->show($id);

        return Response::successResponse('Done', BicycleResource::make($bicycle));
    }

    public function update(BicycleUpdateRequest $bicycleUpdateRequest, $id)
    {
        $this->authorize('update', Bicycle::class);

        $bicycle = $this->bicycleService->update($bicycleUpdateRequest->all(), $id);

        return Response::successResponse('Bicycle Updated Successfully!', BicycleResource::make($bicycle));
    }

    public function destroy($id)
    {
        $this->authorize('destroy', Bicycle::class);

        $this->bicycleService->destroy($id);

        return Response::successResponse('Bicycle Deleted Successfully', []);
    }
}
