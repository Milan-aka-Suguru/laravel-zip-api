<?php

namespace App\Http\Controllers;
use App\Models\Towns;
use Illuminate\Http\Request;

class TownController extends Controller
{
    // GET /api/towns
    public function index()
    {
        $towns = Towns::with('county:id,name')->get(['id', 'name', 'zip_code', 'county_id']);
        return response()->json(['towns' => $towns]);
    }

    // POST /api/towns
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'zip_code' => 'required|string|unique:towns,zip_code',
            'county_id' => 'required|exists:counties,id',
        ]);

        $town = Towns::create($request->only(['name', 'zip_code', 'county_id']));
        return response()->json(['town' => $town], 201);
    }

    // PUT or PATCH /api/towns/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'zip_code' => 'required|string|unique:towns,zip_code,' . $id,
            'county_id' => 'required|exists:counties,id',
        ]);

        $town = Towns::findOrFail($id);
        $town->update($request->only(['name', 'zip_code', 'county_id']));

        return response()->json(['town' => $town]);
    }

    // DELETE /api/towns/{id}
    public function destroy($id)
    {
        $town = Towns::findOrFail($id);
        $town->delete();

        return response()->json(['message' => 'Town deleted']);
    }

    // GET /api/towns/show?name=Kaposvár or ?id=5 or ?zip_code=7400
    public function show(Request $request)
    {
        $query = Towns::query();
    
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }
    
        if ($request->has('name')) {
            $query->where('name', $request->name);
        }
    
        if ($request->has('zip_code')) {
            $query->where('zip_code', $request->zip_code);
        }
    
        $towns = $query->get();
    
        if ($towns->isEmpty()) {
            return response()->json(['message' => 'No towns found'], 404);
        }
    
        return response()->json(['towns' => $towns]);
    }
    

}
