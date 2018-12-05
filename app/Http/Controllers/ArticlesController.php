<?php
namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:article-list');
        $this->middleware('permission:article-create', ['only' => ['create','store']]);
        $this->middleware('permission:article-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:article-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $articles = Article::latest()->paginate(5);
        return view('articles.index', compact('articles'));
//        return view('articles.index', compact('articles'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);


        Article::create($request->all());


        return redirect()->route('products.index')
            ->with('success','Product created successfully.');
    }

    public function show(Article $article)
    {
        return view('articles.show',compact('article'));
    }

    public function edit(Article $product)
    {
        return view('articles.edit',compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);


        $article->update($request->all());


        return redirect()->route('articles.index')
            ->with('success','Article updated successfully');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
    }
}
