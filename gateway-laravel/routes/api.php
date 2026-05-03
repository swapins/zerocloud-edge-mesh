
use App\Http\Controllers\Api\MeshController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/mesh')->group(function () {
    Route::post('/register', [MeshController::class, 'register']);
    Route::post('/sync', [MeshController::class, 'sync']);
});

use App\Http\Controllers\Api\MeshController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/mesh')->group(function () {
    Route::post('/register', [MeshController::class, 'register']);
    Route::post('/sync', [MeshController::class, 'sync']);
});
