<?php

namespace App\Http\Controllers;

use App\ViewModels\ActorsViewModel;
use App\ViewModels\SingleActorViewModel;
use Illuminate\Support\Facades\Http;

class ActorsController extends Controller
{
    public function index($page = 1)
    {
        abort_if($page > 500, 204);

        $popularActors = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/person/popular?language=ru-RU&page={$page}")
            ->json()['results'];

        $viewModel = new ActorsViewModel($popularActors, $page);

        return view('actors.index', $viewModel);
    }

    public function show($id)
    {
        $actor = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/person/{$id}?language=ru-RU")
            ->json();

        $social = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/person/{$id}/external_ids?language=ru-RU")
            ->json();

        $credits = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/person/{$id}/combined_credits?language=ru-RU")
            ->json();

        $viewModel = new SingleActorViewModel($actor, $social, $credits);

        return view('actors.show', $viewModel);
    }
}
