<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticle;
use App\Http\Requests\UpdateArticle;
use App\Models\Articles;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArticleController extends Controller
{

    private function returnResponse()
    {
        $articles = Articles::with(['users', 'category'])
            ->whereNot('status','deleted')
            ->orderBy('id','desc')
            ->paginate(4);
        $categories = Categories::whereNot('status','deleted')->get();

        return response()->json([
            'articles' => $articles->items(),
            'categories' => $categories,
            'pagination' => [
                'total' => $articles->total(),
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
                'pages' => $articles->toArray()['links'],
            ],
        ]);
    }
    public function index(){
        $articles = Articles::with(['users', 'category'])
            ->whereNot('status','deleted')
            ->orderBy('id','desc')
            ->paginate(4);

        $categories = Categories::whereNot('status','deleted')->get();

        return Inertia::render('Articles', [
            'articles' => $articles->items(),
            'categories' => $categories,
            'pagination' => [
                'total' => $articles->total(),
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'prev' => $articles->previousPageUrl(),
                'next' => $articles->nextPageUrl(),
                'pages' => $articles->toArray()['links'],
            ],
        ]);
    }

    public function destroy($id){


        $article = Articles::find($id);

        if ($article) {
            $article->update(['status' => 'deleted']);
        }
        return $this->returnResponse();
    }

    public function store(StoreArticle $request){

        $validatedData=$request->all();
        $validatedData['user_id'] = Auth::id();

        $imagePath = $request->file('image')->store('uploads', 'public');
        $validatedData['image'] = $imagePath;
         Articles::create($validatedData);
         return $this->returnResponse();
    }


    public function update(UpdateArticle $request){

        $validatedData = $request->all();
        $article = Articles::findOrFail($request->input('id'));
        if($request->file('image')){
            $imagePath = $request->file('image')->store('uploads', 'public');
            $validatedData['image'] = $imagePath;
        }
        $article->update($validatedData);
        return $this->returnResponse();
    }
}
