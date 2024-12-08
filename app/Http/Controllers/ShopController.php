<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequestForm;
use App\Models\User;
use App\Services\ShopService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    private $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
        $this->middleware(['permission:add shops'])->only('create');
        $this->middleware(['permission:edit shops'])->only(['update', 'changeStatus']);
        $this->middleware(['permission:delete shops'])->only('remove');
    }

    public function index()
    {
        $filters = request()->all();
        $data = $this->shopService->getData($filters);
        return Inertia::render('Module/Shop', $data);
    }

    public function create(ShopRequestForm $request)
    {
        $this->shopService->create($request);
        return redirect()->route('shops.index')->with('response', 'success');
    }

    public function update(Request $request)
    {
        if ($request->has('id')) {
            $this->shopService->update($request);
            return redirect()->route('shops.index')->with('response', 'success');
        }
    }

    public function changeStatus(Request $request)
    {
        if ($request->has('id')) {
            $this->shopService->changeStatus($request);
            return redirect()->route('shops.index')->with('response', 'success');
        }
    }

    public function remove(Request $request)
    {
        if ($request->has('ids')) {
            $this->shopService->remove($request);
            return redirect()->route('shops.index')->with('response', 'success');
        }
    }
}
