<?php

namespace App\Http\Controllers;

use App\Models\PlantModel;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PlantController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $perPage = request()->get('per_page', 10);
    $plants = PlantModel::paginate($perPage);
    
    return response()->json([
      'success' => true,
      'data' => $plants->items(),
      'pagination' => [
        'current_page' => $plants->currentPage(),
        'last_page' => $plants->lastPage(),
        'per_page' => $plants->perPage(),
        'total' => $plants->total(),
        'from' => $plants->firstItem(),
        'to' => $plants->lastItem(),
      ]
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //TODO: implement save record functionality
  }

  /**
   * Display the specified resource.
   */
  public function show(PlantModel $plantController)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, PlantModel $plantController)
  {
    //TODO : implement update record functionality
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(PlantModel $plant)
  {
    //TODO : implement delete record functionality
  }
}
