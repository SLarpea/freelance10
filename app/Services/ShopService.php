<?php

namespace App\Services;

use App\Models\Shop;
use App\Repositories\ModelRepository;

class ShopService
{
    private $modelRepo;
    private $model;

    public function __construct()
    {
        $this->modelRepo = new ModelRepository;
        $this->model = new Shop();
    }

    public function getData($filters)
    {
        $data = [
            'shops' => Shop::when(isset($filters['search']), function ($query) use ($filters) {
                $query->where('name', 'LIKE', '%' . $filters['search'] . '%');
            })
                ->orderByDesc('created_at')
                ->paginate($filters['per_page'] ?? 10)
                ->withQueryString()
        ];
        return $data;
    }

    public function create($request)
    {
        $data = $request->validated();
        $data = array_merge($data, $request->except(['name']));
        // dd($data);
        $this->modelRepo->create($this->model, $data);
    }

    public function update($request)
    {
        $data = $request->except(['id']);
        $shop = Shop::findOrFail($request->id);
        $this->modelRepo->update($shop, $data);
    }

    public function changeStatus($request)
    {
        $shop = $this->model->findOrFail($request->id);
        $shop->status = ($shop->status == 0) ? 1 : 0;
        $shop->save();
    }

    public function remove($request)
    {
        $this->modelRepo->delete($this->model, $request->ids);
    }
}