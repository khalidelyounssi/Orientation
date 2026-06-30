<x-layouts.app :title="'Historical record details | Orientation Campus'">
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-teal-700">Historical data</p>
            <h1 class="page-title mt-2 text-4xl font-semibold text-slate-950">Historical admission details</h1>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="soft-card rounded-[2rem] p-6">
                <h2 class="text-xl font-semibold text-slate-950">Academic profile</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div><p class="text-sm text-slate-500">Baccalaureate type</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->bac_type }}</p></div>
                    <div><p class="text-sm text-slate-500">Baccalaureate year</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->bac_year ?: 'N/A' }}</p></div>
                    <div><p class="text-sm text-slate-500">General average</p><p class="mt-1 font-medium text-slate-900">{{ number_format((float) $historicalAdmission->general_average, 2) }}/20</p></div>
                    <div><p class="text-sm text-slate-500">GPA</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->gpa !== null ? number_format((float) $historicalAdmission->gpa, 2) : 'N/A' }}</p></div>
                </div>
            </div>

            <div class="soft-card rounded-[2rem] p-6">
                <h2 class="text-xl font-semibold text-slate-950">Orientation and result</h2>
                <div class="mt-6 space-y-4">
                    <div><p class="text-sm text-slate-500">Admitted program</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->admitted_program ?: 'N/A' }}</p></div>
                    <div><p class="text-sm text-slate-500">Followed program</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->followed_program ?: 'N/A' }}</p></div>
                    <div><p class="text-sm text-slate-500">Recommended program</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->recommended_program ?: 'N/A' }}</p></div>
                    <div><p class="text-sm text-slate-500">First-year result</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->first_year_result ?: 'N/A' }}</p></div>
                    <div><p class="text-sm text-slate-500">Final status</p><p class="mt-1 font-medium text-slate-900">{{ $historicalAdmission->final_status ?: 'N/A' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
