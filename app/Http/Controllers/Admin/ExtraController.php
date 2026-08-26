<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExtraRequest;
use App\Http\Requests\Admin\UpdateExtraRequest;
use App\Models\Extra;

class ExtraController extends Controller
{
    public function index()
    {
        $extras = Extra::orderBy('name')->paginate(15);
        return view('admin.extras.index', compact('extras'));
    }

    public function create()
    {
        return view('admin.extras.create');
    }

    public function store(StoreExtraRequest $request)
    {
        Extra::create($request->validated());
        return redirect()->route('admin.extras.index')
            ->with('success', 'Extra creado correctamente.');
    }

    public function edit(Extra $extra)
    {
        return view('admin.extras.edit', compact('extra'));
    }

    public function update(UpdateExtraRequest $request, Extra $extra)
    {
        $extra->update($request->validated());
        return redirect()->route('admin.extras.index')
            ->with('success', 'Extra actualizado correctamente.');
    }

    public function destroy(Extra $extra)
    {
        $extra->delete();
        return redirect()->route('admin.extras.index')
            ->with('success', 'Extra eliminado correctamente.');
    }
}
