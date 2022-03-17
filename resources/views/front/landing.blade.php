<x-front.app-layout>
    <div class="py-12">

        @foreach($posts as $post)
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
                <div class="flex">

                    <div class="w-1/5">
                        <p class="text-sm mt-2">
                            {{$post->publication_date->toFormattedDateString()}}
                        </p>

                    </div>

                    <div class="w-4/5">
                        <h3 class="mb-4 text-2xl font-bold text-slate-900 tracking-tight">
                            <a href="{{ route('front.posts.show', $post)}}"> {{$post->title}} </a>
                        </h3>

                        <div class="mb-6">
                            <p>
                                {{$post->description}}
                            </p>
                        </div>

                        <a class="group inline-flex items-center h-9 rounded-full text-sm font-semibold whitespace-nowrap px-3 focus:outline-none focus:ring-2 bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900 focus:ring-slate-500 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dark:focus:ring-slate-500" href="{{ route('front.posts.show', $post)}}">Read more<span class="sr-only">,
                            </span><svg class="overflow-visible ml-3 text-slate-300 group-hover:text-slate-400 dark:text-slate-500 dark:group-hover:text-slate-400" width="3" height="6" viewBox="0 0 3 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M0 0L3 3L0 6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach



        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <hr>
        </div>

        @if ($posts->links()->paginator->hasPages())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-whitee overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-whiete border-b border-gray-200">
                    {!! $posts->links() !!}
                    </div>
                </div>
            </div>
        @endif


    </div>
    </x-app-layout>