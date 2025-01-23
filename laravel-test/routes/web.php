<?php
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;


Route::get('/', function () {
    return view('welcome');
});

    Route::middleware('api')->post('/translation', [TranslationController::class, 'store']);
    Route::put('/translations/{id}', [TranslationController::class, 'update']);
    // Route::get('/translations', [TranslationController::class, 'index']);
    Route::get('/translations/export', [TranslationController::class, 'export']);




Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken
    ], 200);
});
