<x-app-layout>
    <div
        class="fixed z-10 right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">

        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Profile')</h2>

        <div class="mt-8 space-y-4">




            <div
                class="flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2">


                <div class="text-left rtl:text-right">
                    <h1 class="text-sm font-medium text-gray-700 capitalize dark:text-white">
                        <div x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name"
                            x-on:profile-updated.window="name = $event.detail.name"></div>
                    </h1>


                </div>
            </div>


            <livewire:layout.logout />

        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.limit-profile-information />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.public-blog />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.lang />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.public-user-profile />
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
