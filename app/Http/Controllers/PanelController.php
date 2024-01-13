<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use Illuminate\Http\Request;
use Services\PanelService;

class PanelController extends Controller
{
    protected $panelService;

    public function __construct(PanelService $panelService)
    {
        $this->panelService = $panelService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $panels = Panel::all();
        return $panels;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->only(['name', 'domain', 'icon']);
            $panel = $this->panelService->create($data);
            return $panel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $panel = $this->panelService->get($id);
        return $panel;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $panel = $this->panelService->update($id, $request);
            return $panel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $panel = $this->panelService->delete($id);
            return $panel;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
