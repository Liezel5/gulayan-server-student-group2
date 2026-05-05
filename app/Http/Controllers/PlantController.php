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
    //TODO : implement load all the records
    //TODO : implement pagination when loading all the records
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
    try {
      $plant->delete();

      return response()->json([
        'message' => 'Plant record deleted successfully',
        'data' => $plant
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to delete plant record',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
