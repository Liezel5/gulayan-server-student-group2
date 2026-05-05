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
    try {
      $validated = $request->validate([
        'name' => 'required|string|max:255',
        'variety' => 'required|string|max:255',
        'notes' => 'nullable|string',
        'date_planted' => 'required|date',
        'seedling_count' => 'required|integer|min:1',
        'batch_name' => 'required|string|max:255',
        'starting_fund' => 'required|numeric|min:0',
        'seedling_source' => 'required|string|max:255',
      ]);

      $plant = PlantModel::create($validated);

      return response()->json([
        'success' => true,
        'message' => 'Plant record created successfully',
        'data' => $plant
      ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to create plant record',
        'error' => $e->getMessage()
      ], 500);
    }
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
