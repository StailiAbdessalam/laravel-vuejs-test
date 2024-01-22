<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{

    private function returnResponse()
    {
        $categories = Categories::with(['users', 'articles'])
            ->whereNot('status','deleted')
            ->orderBy('id','DESC')
            ->withCount('articles')
            ->paginate(4);

        return response()->json([
            'categories' => $categories->items(),
            'pagination' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'prev' => $categories->previousPageUrl(),
                'next' => $categories->nextPageUrl(),
                'pages' => $categories->toArray()['links'],
            ],
        ]);
    }

    public function index(){
        $categories = Categories::with(['users', 'articles'])
            ->orderBy('id','DESC')
            ->whereNot('status','deleted')
            ->withCount('articles')
            ->paginate(10);


        return Inertia::render('Categories', [
            'categories' => $categories->items(),
            'pagination' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'prev' => $categories->previousPageUrl(),
                'next' => $categories->nextPageUrl(),
                'pages' => $categories->toArray()['links'],
            ],
        ]);

    }

    public function destroy($id){

        $category = Categories::find($id);
        if ($category) {
            $category->update(['status' => 'deleted']);

            $category->articles()->update(['status' => 'deleted']);
        }
        return $this->returnResponse();
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
        ]);

        if ($request->has('id')) {
            $validatedData['id'] = $request->input('id');
        }
        $validatedData['user_id'] = Auth::id();
        $validatedData['image'] = "test";

        // Create a new category
        Categories::updateOrCreate(
            ['id' => $validatedData['id']??null],
            $validatedData
        );
        // Return the newly created category
        return $this->returnResponse();
    }
}
