<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Models\Todo;

Route::get('/', function (Request $request) {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);


    return Inertia::render('Admin/Dashboard', [
        'todos' => Todo::when($request->term, function ($query, $term) {
            $query->where('name', 'LIKE', '%'. $term .'%');
        })->paginate(2)->withQueryString(),
    ]);
})->name('home');

Route::get('/todo/add', function () {
    return Inertia::render('Admin/AddTodo');
})->name('todo.add');

Route::post('/todo/store', function (Request $request) {
    Todo::create($request->all());

    return to_route('home');
})->name('todo.store');

Route::put('/todo/done', function (Request $request) {
    Todo::find($request->id)->update([
        'is_done' => ! $request->is_done,
    ]);

    return redirect()->back();
})->name('todo.done');

Route::get('/todo/{id}/edit', function ($id) {

    return Inertia::render('Admin/EditTodo', [
        'todo' => Todo::find($id),
    ]);
})->name('todo.edit');

Route::put('/todo/{id}/update', function (Request $request, $id) {
    Todo::find($id)->update($request->all());

    return to_route('home');
})->name('todo.update');

Route::delete('/todo/{id}/delete', function ($id) {
    Todo::destroy($id);

    return to_route('home');
})->name('todo.delete');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
