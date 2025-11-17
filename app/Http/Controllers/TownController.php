<?php

namespace App\Http\Controllers;
use App\Models\Towns;
use Illuminate\Http\Request;

class TownController extends Controller
{
    /**
 * @api {get} /towns Települések listázása
 * @apiName ListTowns
 * @apiGroup Település
 * @apiSuccess {Object[]} towns A települések listája
 */
    // GET /api/towns
    public function index()
    {
        $towns = Towns::with('county:id,name')->get(['id', 'name', 'zip_code', 'county_id']);
        return response()->json(['towns' => $towns]);
    }
/**
 * @api {post} /towns Új település létrehozása
 * @apiName CreateTown
 * @apiGroup Település
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiParam {String} name A település neve
 * @apiParam {Number} county_id A megye azonosítója
 * @apiSuccess {Number} id A település azonosítója
 * @apiSuccess {String} name A település neve
 */
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
/**
 * @api {put} /towns/:id Település módosítása
 * @apiName UpdateTown
 * @apiGroup Település
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiParam {String} name A település neve
 * @apiParam {Number} county_id A megye azonosítója
 * @apiSuccess {Number} id A település azonosítója
 * @apiSuccess {String} name A település neve
 */
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
/**
 * @api {delete} /towns/:id Település törlése
 * @apiName DeleteTown
 * @apiGroup Település
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiSuccess {String} message Sikeres törlés üzenete
 */
    // DELETE /api/towns/{id}
    public function destroy($id)
    {
        $town = Towns::findOrFail($id);
        $town->delete();

        return response()->json(['message' => 'Town deleted']);
    }
/**
 * @api {get} /towns/:id Település lekérése
 * @apiName GetTown
 * @apiGroup Település
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiSuccess {Number} id A település azonosítója
 * @apiSuccess {String} name A település neve
 */
    // GET /api/towns/3?Lookup-Type=id 
    public function show(Request $request, $id)
    {
        $lookupType = $request->header('Lookup-Type', 'id'); 
    
        if ($lookupType === 'name') {
            $towns = Towns::where('name','LIKE', $id)->get();
        } elseif ($lookupType === 'zip_code') {
            $towns = Towns::where('zip_code', $id)->get();
        } else {
            $towns = Towns::where('id',$id)->get();
        }
    
        if (!$towns) {
            return response()->json(['message' => 'Town not found'], 404);
        }
    
        return response()->json(['town' => $towns]);
    }
    
    

}
