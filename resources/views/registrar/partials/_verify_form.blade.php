<form method="POST" action="{{ route('registrar.verify.store', $applicant) }}" class="space-y-3">
    @csrf
    @php
        $docs = [
            'doc_form_137'       => 'Form 137 / TOR',
            'doc_psa_birth_cert' => 'PSA Birth Certificate',
            'doc_good_moral'     => 'Good Moral Certificate',
            'doc_id_photos'      => '2×2 ID Photos',
            'doc_medical_cert'   => 'Medical Certificate',
            'doc_diploma'        => 'Diploma / Certificate of Graduation',
        ];
    @endphp
    <div class="grid sm:grid-cols-2 gap-2">
        @foreach($docs as $key => $label)
            <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded px-3 py-2">
                <input type="checkbox" name="{{ $key }}" value="1"
                       @checked(optional($verification)->$key)>
                <span class="text-sm">{{ $label }}</span>
            </label>
        @endforeach
    </div>

    <label class="block">
        <span class="text-sm font-medium">Remarks</span>
        <textarea name="remarks" rows="3" class="mt-1 w-full border rounded px-3 py-2"
                  >{{ optional($verification)->remarks }}</textarea>
    </label>

    @if($requireOverride)
        <label class="block">
            <span class="text-sm font-medium text-rose-800">Override reason (required — exam failed)</span>
            <textarea name="override_reason" rows="3" required minlength="5" maxlength="500"
                      class="mt-1 w-full border border-rose-300 rounded px-3 py-2 bg-rose-50/40"
                      placeholder="Explain why this applicant is being verified despite a failed entrance exam.">{{ old('override_reason', optional($verification)->override_reason) }}</textarea>
            @error('override_reason')
                <p class="text-xs text-rose-700 mt-1">{{ $message }}</p>
            @enderror
            <p class="text-[11px] text-slate-500 mt-1">This reason is permanently recorded and shown on the applicant's status page.</p>
        </label>
    @endif

    <div class="flex items-center justify-between">
        <div class="text-sm">
            @if($verification)
                Status:
                @php $cls = ['verified'=>'emerald','incomplete'=>'amber','rejected'=>'rose','pending'=>'slate'][$verification->status] ?? 'slate'; @endphp
                <span class="text-{{ $cls }}-700 bg-{{ $cls }}-100 rounded px-2 py-0.5 text-xs capitalize">
                    {{ $verification->status }}
                </span>
            @endif
        </div>
        <button class="{{ $requireOverride ? 'bg-rose-600 hover:bg-rose-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white px-5 py-2 rounded"
                @if($requireOverride) onclick="return confirm('Save verification with override? The reason will be permanently recorded.');" @endif>
            {{ $requireOverride ? 'Save with Override' : 'Save Verification' }}
        </button>
    </div>
</form>
