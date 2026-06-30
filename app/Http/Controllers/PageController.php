<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function returns()
    {
        return view('pages.returns');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan menghubungi Anda segera.');
    }

    public function sitemap()
    {
        $sitemap = \Spatie\Sitemap\Sitemap::create()
            ->add(\Spatie\Sitemap\Tags\Url::create('/'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/products'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/kategori'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/tentang'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/kontak'));

        foreach (\App\Models\Category::all() as $category) {
            $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/products?category={$category->id}"));
        }

        foreach (\App\Models\Product::where('is_active', true)->get() as $product) {
            $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/products/{$product->slug}"));
        }

        return $sitemap->toResponse(request());
    }
}
