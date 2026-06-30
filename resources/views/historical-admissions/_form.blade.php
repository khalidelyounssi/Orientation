<div class="soft-card rounded-[2rem] p-6">
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <div>
            <label for="bac_type" class="text-sm font-medium text-slate-700">Baccalaureate type</label>
            <input id="bac_type" name="bac_type" type="text" value="{{ old('bac_type', $historicalAdmission->bac_type) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none" required>
            <x-input-error field="bac_type" />
        </div>
        <div>
            <label for="bac_year" class="text-sm font-medium text-slate-700">Baccalaureate year</label>
            <input id="bac_year" name="bac_year" type="number" value="{{ old('bac_year', $historicalAdmission->bac_year) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="bac_year" />
        </div>
        <div>
            <label for="general_average" class="text-sm font-medium text-slate-700">General average</label>
            <input id="general_average" name="general_average" step="0.01" type="number" value="{{ old('general_average', $historicalAdmission->general_average) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none" required>
            <x-input-error field="general_average" />
        </div>

        @foreach ([
            'math_score' => 'Mathematics',
            'physics_score' => 'Physics',
            'french_score' => 'French',
            'english_score' => 'English',
            'computer_science_score' => 'Computer science',
            'biology_score' => 'Biology',
            'economics_score' => 'Economics',
        ] as $field => $label)
            <div>
                <label for="{{ $field }}" class="text-sm font-medium text-slate-700">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" step="0.01" type="number" value="{{ old($field, $historicalAdmission->{$field}) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error :field="$field" />
            </div>
        @endforeach

        <div class="xl:col-span-3">
            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3">
                <input type="checkbox" name="has_repeated_year" value="1" @checked(old('has_repeated_year', $historicalAdmission->has_repeated_year)) class="rounded border-slate-300 text-teal-700 focus:ring-teal-700">
                <span class="text-sm font-medium text-slate-700">Repeated at least one year</span>
            </label>
            <x-input-error field="has_repeated_year" />
        </div>

        <div>
            <label for="number_of_repeated_years" class="text-sm font-medium text-slate-700">Number of repeated years</label>
            <input id="number_of_repeated_years" name="number_of_repeated_years" type="number" value="{{ old('number_of_repeated_years', $historicalAdmission->number_of_repeated_years ?? 0) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="number_of_repeated_years" />
        </div>
        @foreach ([
            'first_choice' => 'First choice',
            'second_choice' => 'Second choice',
            'third_choice' => 'Third choice',
            'preferred_domain' => 'Preferred domain',
            'admitted_program' => 'Admitted program',
            'followed_program' => 'Followed program',
            'recommended_program' => 'Recommended program',
            'first_year_result' => 'First-year result',
            'final_status' => 'Final status',
        ] as $field => $label)
            <div>
                <label for="{{ $field }}" class="text-sm font-medium text-slate-700">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="text" value="{{ old($field, $historicalAdmission->{$field}) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error :field="$field" />
            </div>
        @endforeach
        <div>
            <label for="gpa" class="text-sm font-medium text-slate-700">GPA</label>
            <input id="gpa" name="gpa" step="0.01" type="number" value="{{ old('gpa', $historicalAdmission->gpa) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="gpa" />
        </div>
    </div>
</div>
