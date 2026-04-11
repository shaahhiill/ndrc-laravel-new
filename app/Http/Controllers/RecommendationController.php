<?php

namespace App\Http\Controllers;

use App\Services\SmartOrderingService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $smartOrderService;

    public function __construct(SmartOrderingService $service)
    {
        $this->smartOrderService = $service;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'retailer') {
            return redirect('/')->with('error', 'Only retailers can access smart recommendations.');
        }

        $recommendations = $this->smartOrderService->getRecommendations($user);
        $trends = $this->smartOrderService->getTrendData($user);

        return view('retailer.smart-orders', compact('recommendations', 'trends'));
    }
}
