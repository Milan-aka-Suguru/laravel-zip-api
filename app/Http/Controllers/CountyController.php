<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Pail\ValueObjects\Origin\Console;
use App\Models\Counties;
class CountyController extends Controller{
    /**
 * @api {get} /counties Megyék listázása
 * @apiName ListCounties
 * @apiGroup Megye
 * @apiSuccess {Object[]} counties A megyék listája
 */
    public function index()
    {
        $counties = Counties::all(['id', 'name']);
        return response()->json(['counties' => $counties]);
    }
    /**
 * @api {post} /counties Új megye létrehozása
 * @apiName CreateCounty
 * @apiGroup Megye
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiParam {String} name A megye neve
 * @apiSuccess {Number} id A megye azonosítója
 * @apiSuccess {String} name A megye neve
 */

    // POST /api/counties
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:counties,name',
        ]);

        $county = Counties::create(['name' => $request->name]);
        return response()->json(['county' => $county], 201);
    }
/**
 * @api {put} /counties/:id Megye módosítása
 * @apiName UpdateCounty
 * @apiGroup Megye
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiParam {String} name A megye neve
 * @apiSuccess {Number} id A megye azonosítója
 * @apiSuccess {String} name A megye neve
 */
    // PUT or PATCH /api/counties/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:counties,name,' . $id,
        ]);

        $county = Counties::findOrFail($id);
        $county->name = $request->name;
        $county->save();

        return response()->json(['county' => $county]);
    }
/**
 * @api {delete} /counties/:id Megye törlése
 * @apiName DeleteCounty
 * @apiGroup Megye
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiSuccess {String} message Sikeres törlés üzenete
 */
    // DELETE /api/counties/{id}
    public function destroy($id)
    {
        $county = Counties::findOrFail($id);
        $county->delete();

        return response()->json(['message' => 'County deleted']);
    }
/**
 * @api {get} /counties/:id Megye lekérése
 * @apiName GetCounty
 * @apiGroup Megye
 * @apiHeader {String} Authorization Bearer token (Sanctum)
 * @apiSuccess {Number} id A megye azonosítója
 * @apiSuccess {String} name A megye neve
 */
    public function show(Request $request, $id)
    {
        $lookupType = $request->header('Lookup-Type', 'id');         
        if ($lookupType === 'name') {
            $counties = Counties::where('name','LIKE', $id)->get();
        } else {
            $counties = Counties::where('id',$id)->get();
        }
    
        if (!$counties) {
            return response()->json(['message' => 'County not found'], 404);
        }
    
        return response()->json(['county' => $counties]);
    }
    
}