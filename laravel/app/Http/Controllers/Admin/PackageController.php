<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CreatePackageRequest;
use App\Http\Requests\Admin\UpdatePackageRequest;
use App\Actions\Admin\CreatePackageAction;
use App\Actions\Admin\UpdatePackageAction;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('currentVersion')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(CreatePackageRequest $request, CreatePackageAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('admin.packages.index')->with('success', 'Pacote criado com sucesso.');
    }

    public function update(UpdatePackageRequest $request, Package $package, UpdatePackageAction $action)
    {
        $action->execute($package, $request->validated());

        return redirect()->route('admin.packages.index')->with('success', 'Pacote atualizado e versionado com sucesso.');
    }
}
