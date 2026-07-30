<div
    class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 transition hover:shadow-md">

    <div class="flex items-start justify-between">

        <div>

            <p class="text-sm font-medium text-slate-500">

                {{ $title }}

            </p>

            <h2 class="mt-3 text-3xl font-bold text-slate-800">

                {{ $value }}

            </h2>

            @isset($description)

                <p class="mt-2 text-sm text-slate-400">

                    {{ $description }}

                </p>

            @endisset

        </div>

        <div
            class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center">

            {{ $icon ?? '📊' }}

        </div>

    </div>

</div>