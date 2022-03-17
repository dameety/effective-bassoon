<x-front.app-layout>
    <div class="py-12">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div>
                <p class="text-sm mt-2">
                    {{$post->publication_date->toFormattedDateString()}}
                </p>
                <h1 class="col-span-full text-3xl sm:text-4xl xl:mb-16 font-extrabold tracking-tight text-slate-900">
                    {{$post->title}}
                </h1>
                <div class="mb-6">
                    <p>
                        {{$post->description}}
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>