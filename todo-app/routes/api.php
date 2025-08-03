<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tasks', fn()=> Task::all());

Route::post('/tasks', function (Request $request) {
    $request->validate(['title'=>'required|string|max:255']);
    return Task::create(['title'=> $request->title, 'completed'=> false]);
});


Route::put('/tasks/{id}', function (Request $request, $id) {
    $task = Task::find($id);
    if (!$task) return response()->json(['error'=>'Not found'], 404);
    $request->validate(['title'=>'required', 'completed' => 'required|boolean']);
    $task->update($request->only(['title', 'completed']));
    return $task;
});

Route::delete('/tasks/${id}',function($id){
    $task = Task::find($id);
    if(!$task) return response()->json(['error'=>'Not found'], 404);
    $task->delete();
    return response()->json(['message'=>'Deleted']);
});
