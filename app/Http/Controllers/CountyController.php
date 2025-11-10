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
    public function index()
    {
        $counties = Counties::all(['id', 'name']);
        return response()->json(['counties' => $counties]);
    }

    // POST /api/counties
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:counties,name',
        ]);

        $county = Counties::create(['name' => $request->name]);
        return response()->json(['county' => $county], 201);
    }

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

    // DELETE /api/counties/{id}
    public function destroy($id)
    {
        $county = Counties::findOrFail($id);
        $county->delete();

        return response()->json(['message' => 'County deleted']);
    }

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