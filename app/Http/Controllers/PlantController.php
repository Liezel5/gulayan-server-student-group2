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
    $validated = $request->validate([
      'name' => 'sometimes|string|max:255',
      'variety' => 'sometimes|string|max:255',
      'notes' => 'sometimes|string',
      'date_planted' => 'sometimes|date',
      'seedling_count' => 'sometimes|integer|min:0',
      'batch_name' => 'sometimes|string|max:255',
      'starting_fund' => 'sometimes|numeric|min:0',
      'seedling_source' => 'sometimes|string|max:255',
    ]);

    $plantController->update($validated);

    return response()->json([
      'message' => 'Plant record updated successfully',
      'data' => $plantController
    ], 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(PlantModel $plant)
  {
    //TODO : implement delete record functionality
  }
}
