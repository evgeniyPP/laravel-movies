<?php

namespace App\Http\Controllers;

use App\View\ViewModels\SingleTvShowViewModel;
use App\View\ViewModels\TvShowsViewModel;
use Illuminate\Support\Facades\Http;

class TvShowsController extends Controller
{
    public function index()
    {
        $popularTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/popular?language=ru-RU')
            ->json()['results'];

        $topRatedTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/top_rated?language=ru-RU')
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list?language=ru-RU')
            ->json()['genres'];

        $viewModel = new TvShowsViewModel(
            $popularTv,
            $topRatedTv,
            $genres
        );

        return view('tv.index', $viewModel);
    }

    public function show($id)
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/tv/{$id}?language=ru-RU&append_to_response=credits,videos,images&include_image_language=en,null")
            ->json();

        $viewModel = new SingleTvShowViewModel($tvshow);

        return view('tv.show', $viewModel);
    }
}
