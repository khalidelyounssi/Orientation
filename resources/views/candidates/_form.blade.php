<div class="space-y-8">
    @php
        $predictiveData = old('predictive_data', $candidate->predictive_data ?? []);
        $textFields = ['DIV_CDE', 'DEGREE_CDE', 'MAJOR_1', 'ACADEMIC_PROBATION'];
    @endphp

    <section class="soft-card rounded-[2rem] p-6">
        <h2 class="text-xl font-semibold text-slate-950">Informations administratives</h2>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <div>
                <label for="external_student_id" class="text-sm font-medium text-slate-700">Student ID</label>
                <input id="external_student_id" name="external_student_id" type="text" value="{{ old('external_student_id', $candidate->external_student_id) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error field="external_student_id" />
            </div>
            <div>
                <label for="academic_status" class="text-sm font-medium text-slate-700">Academic status</label>
                <input id="academic_status" name="academic_status" type="text" value="{{ old('academic_status', $candidate->academic_status) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error field="academic_status" />
            </div>
            <div>
                <label for="first_name" class="text-sm font-medium text-slate-700">First name</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $candidate->first_name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error field="first_name" />
            </div>
            <div>
                <label for="last_name" class="text-sm font-medium text-slate-700">Last name</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $candidate->last_name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error field="last_name" />
            </div>
            <div>
                <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $candidate->email) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error field="email" />
            </div>
            <div>
                <label for="phone" class="text-sm font-medium text-slate-700">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $candidate->phone) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                <x-input-error field="phone" />
            </div>
            <div>
                <label for="status" class="text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $candidate->status) === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <x-input-error field="status" />
            </div>
            <div class="xl:col-span-3">
                <label for="notes" class="text-sm font-medium text-slate-700">Notes</label>
                <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">{{ old('notes', $candidate->notes) }}</textarea>
                <x-input-error field="notes" />
            </div>
        </div>
    </section>

    <section class="soft-card rounded-[2rem] p-6">
        <h2 class="text-xl font-semibold text-slate-950">Follow-up data</h2>
        <p class="mt-2 text-sm text-slate-500">These fields are used to calculate the vigilance score.</p>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach (\App\Models\Candidate::predictiveFields() as $field => $label)
                <div>
                    <label for="{{ $field }}" class="text-sm font-medium text-slate-700">{{ $label }}</label>
                    <input
                        id="{{ $field }}"
                        name="predictive_data[{{ $field }}]"
                        type="{{ in_array($field, $textFields, true) ? 'text' : 'number' }}"
                        @if (! in_array($field, $textFields, true)) step="0.01" @endif
                        value="{{ $predictiveData[$field] ?? '' }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none"
                    >
                    <x-input-error :field="'predictive_data.'.$field" />
                </div>
            @endforeach
        </div>
    </section>
</div>
