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
  public function index(Request $request)
  {
    try {
      $perPage = $request->query('per_page', 15);
      $perPage = min((int) $perPage, 100); // Limit max per_page to 100

      $plants = PlantModel::paginate($perPage);

      return response()->json([
        'message' => 'Plant records retrieved successfully',
        'data' => $plants->items(),
        'pagination' => [
          'total' => $plants->total(),
          'per_page' => $plants->perPage(),
          'current_page' => $plants->currentPage(),
          'last_page' => $plants->lastPage(),
          'from' => $plants->firstItem(),
          'to' => $plants->lastItem(),
        ],
        'links' => [
          'first' => $plants->url(1),
          'last' => $plants->url($plants->lastPage()),
          'prev' => $plants->previousPageUrl(),
          'next' => $plants->nextPageUrl(),
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to retrieve plant records',
        'error' => $e->getMessage()
      ], 500);
    }
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
