<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\Post;
use App\Models\HomePage\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog as meta_keywords', 'meta_description_blog as meta_description')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $postTitle = $blogCategorySlug = null;

    if ($request->filled('title')) {
      $postTitle = $request['title'];
    }
    if ($request->filled('category')) {
      $blogCategorySlug = $request['category'];
    }


    $queryResult['posts'] = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
      ->where('posts.status', '=', 1)
      ->where([
        ['post_informations.language_id', '=', $language->id],
        ['blog_categories.status', '=', 1],
      ])
      ->when($postTitle, function ($query) use($postTitle){
           return $query->where('post_informations.title', 'like', '%'.$postTitle.'%');
      })
      ->when($blogCategorySlug, function (Builder $query, $blogCategorySlug) {
        $blogCategory = BlogCategory::query()->where([
          ['slug', '=', $blogCategorySlug],
          ['status', '=', 1],
        ])->first();

        return $query->where('post_informations.blog_category_id', '=', $blogCategory->id);
      })
      ->select('posts.id', 'posts.image', 'blog_categories.name as categoryName',  'blog_categories.slug as categorySlug', 'post_informations.title', 'post_informations.tags', 'post_informations.slug', 'post_informations.author', 'post_informations.content', 'posts.created_at')
      ->orderBy('posts.serial_number', 'asc')
      ->paginate(4);

    $queryResult['categories'] = $this->getCategories($language);

    $queryResult['totalPost'] = $language->postInformation()->count();

    return view('frontend.blog.posts', $queryResult);
  }

  public function show(Request $request, $slug, $postId)
  {
    if (!$postId) {
      abort(404);
    }
    $postId = $request->id;
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $secInfo = Section::query()->first();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);
    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog_post_details', 'meta_description_blog_post_details')->first();

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $queryResult['details'] = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
      ->where('post_informations.language_id', '=', $language->id)
      ->where('posts.id', '=', $postId)
      ->select('posts.image', 'posts.created_at', 'post_informations.title',  'post_informations.content', 'post_informations.author', 'post_informations.meta_keywords', 'post_informations.meta_description', 'blog_categories.name as categoryName')
      ->firstOrFail();

      $queryResult['recentPost'] = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
        ->where('post_informations.language_id', '=', $language->id)
        ->orderBy('posts.created_at', 'desc')
        ->limit(3)
        ->select('posts.image', 'posts.id', 'posts.created_at', 'post_informations.title',  'post_informations.author', 'post_informations.slug',   'blog_categories.name as categoryName')
        ->get();

    $queryResult['disqusInfo'] = Basic::query()->select('disqus_status', 'disqus_short_name')->firstOrFail();
    $queryResult['categories'] = $this->getCategories($language);
    $queryResult['totalPost'] = $language->postInformation()->count();

    return view('frontend.blog.post-details', $queryResult);
  }

  public function getCategories($language)
  {
    $categories = $language->blogCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $categories->map(function ($category) {
      $category['postCount'] = $category->postInfo()->count();
    });
    return $categories;
  }
}
