<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Write a Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- <x-auth-card> -->
                        <x-slot name="logo">
                            <a href="/">
                                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                            </a>
                        </x-slot>

                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('back.posts.store') }}">
                            @csrf

                            <div>
                                <x-label for="title" value="Title" />

                                <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            </div>

                            <div class="mt-4">
                                <x-label for="description" value="Description" />

                                <textarea id="description" class="
                                    mt-1
                                    block
                                    w-full
                                    rounded-md
                                    border-gray-300
                                    shadow-sm
                                    focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                " rows="3" name="description" value="old('description')" required
                                ></textarea>
                            </div>

                            <div class="flex items-center mt-4">
                                <x-button class="">
                                    Publish post
                                </x-button>
                            </div>
                        </form>
                    <!-- </x-auth-card> -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
