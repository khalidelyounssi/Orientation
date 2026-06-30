<div class="soft-card rounded-[2rem] p-6">
    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="name" class="text-sm font-medium text-slate-700">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $program->name) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none" required>
            <x-input-error field="name" />
        </div>
        <div>
            <label for="slug" class="text-sm font-medium text-slate-700">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $program->slug) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="slug" />
        </div>
        <div>
            <label for="department" class="text-sm font-medium text-slate-700">Department</label>
            <input id="department" name="department" type="text" value="{{ old('department', $program->department) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="department" />
        </div>
        <div>
            <label for="faculty" class="text-sm font-medium text-slate-700">Faculty</label>
            <input id="faculty" name="faculty" type="text" value="{{ old('faculty', $program->faculty) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="faculty" />
        </div>
        <div>
            <label for="capacity" class="text-sm font-medium text-slate-700">Capacity</label>
            <input id="capacity" name="capacity" type="number" value="{{ old('capacity', $program->capacity) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="capacity" />
        </div>
        <div>
            <label for="minimum_average" class="text-sm font-medium text-slate-700">Minimum average</label>
            <input id="minimum_average" name="minimum_average" step="0.01" type="number" value="{{ old('minimum_average', $program->minimum_average) }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">
            <x-input-error field="minimum_average" />
        </div>
        <div class="md:col-span-2">
            <label for="description" class="text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">{{ old('description', $program->description) }}</textarea>
            <x-input-error field="description" />
        </div>
        <div>
            <label for="required_subjects" class="text-sm font-medium text-slate-700">Required subjects</label>
            <textarea id="required_subjects" name="required_subjects" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">{{ old('required_subjects', $program->required_subjects) }}</textarea>
            <x-input-error field="required_subjects" />
        </div>
        <div>
            <label for="important_subjects" class="text-sm font-medium text-slate-700">Important subjects</label>
            <textarea id="important_subjects" name="important_subjects" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-teal-600 focus:outline-none">{{ old('important_subjects', $program->important_subjects) }}</textarea>
            <x-input-error field="important_subjects" />
        </div>
        <div class="md:col-span-2">
            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $program->is_active)) class="rounded border-slate-300 text-teal-700 focus:ring-teal-700">
                <span class="text-sm font-medium text-slate-700">Active program</span>
            </label>
            <x-input-error field="is_active" />
        </div>
    </div>
</div>
