<?php

namespace Modules\Core\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\app\Services\UserService;

class CoreController extends Controller
{
    protected UserService $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('core::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $this->UserService->updateFcmToken(
            auth()->id(),
            $request->fcm_token
        );

        return response()->json([
            'success' => true,
        ]);
    }
}
