<?php

namespace App\Http\Controllers;

use App\Http\Requests\PanelRequest;
use App\Models\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Services\PanelService;
use Inertia\Inertia;

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
        $panels = Panel::orderBy('updated_at', 'desc')->get()->map(function ($panel) {
            return [
                ...$panel->toArray(),
                'user_panels' => $panel->UserPanels->toArray(),
            ];
        });
        return Inertia::render('Admin/Panels', ['panels' => $panels]);
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
    public function store(PanelRequest $request)
    {
        try {
            $data = $request->only(['name', 'domain', 'icon']);
            $this->panelService->create($data);
            return redirect()->to(route('panels.index'));
        } catch (\Throwable $th) {
            return redirect()->to(route('panels.index'));
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
            $data = $request->only(['name', 'domain', 'icon', 'status']);
            $panel = $this->panelService->update($id, $data);
            return redirect()->to(route('panels.index'));
        } catch (\Throwable $th) {
            return redirect()->to(route('panels.index'));
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

    public function upload(Request $request)
    {
        if ($request->has('icon')) {
            $save = $request->file('icon')->store('icons', ['disk' => 'public']);
            return ['status' => true, 'url' => asset('storage/' . $save), 'pathFile' => $save];
        }
        return ['status' => false];
    }

    public function remove_upload(Request $request)
    {
        if ($request->has('pathFile') && $request->pathFile) {
            Storage::delete($request->pathFile);
            $parsedUrl = parse_url($request->pathFile);
            $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : $request->pathFile;
            Storage::delete(str_replace('/storage/', '', $path));
        }

        return response()->json(['status' => 'OK']);
    }

    public function delete_many(Request $request)
    {
        $this->panelService->delete_many($request->ids);
        return redirect()->to(route('panels.index'));
    }
}
