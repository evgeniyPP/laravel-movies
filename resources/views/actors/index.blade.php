@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="popular-movies">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Популярные актеры</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($popularActors as $actor)
                    <div class="actor mt-8">
                        <a href="{{ route('actors.show', $actor['id']) }}">
                            <img src="{{ $actor['poster'] }}" alt="actor" class="hover:opacity-75 transition ease-in-out duration-150">
                            <div class="mt-2">
                                <a href="{{ route('actors.show', $actor['id']) }}" class="text-lg hover:text-gray-300">{{ $actor['name'] }}</a>
                                <div class="text-sm truncate text-gray-400">{{ $actor['known_for'] }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="page-load-status my-8">
            <div class="flex justify-center">
                <div class="spinner infinite-scroll-request my-8 text-4xl">&nbsp;</div>
                <p class="infinite-scroll-last">Конец</p>
                <p class="infinite-scroll-error">Ошибка</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.min.js"></script>
    <script>
        var elem = document.querySelector('.grid');
        var infScroll = new InfiniteScroll( elem, {
          path: '/actors/page/@{{#}}',
          append: '.actor',
          status: '.page-load-status',
          history: false,
        });
    </script>
@endsection
