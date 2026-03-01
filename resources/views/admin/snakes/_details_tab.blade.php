{{--
    Reusable partial: language-specific details tab pane.
    Variables expected:
      $lang  — 'en' | 'si' | 'ta'
      $label — 'English' | 'Sinhala' | 'Tamil'
      $snake — Snake model (null when creating)
--}}
@php
    $v = fn($field) => old("{$field}_{$lang}", $snake ? $snake->{"{$field}_{$lang}"} : '');
    // For 'en', the field names have no suffix in the DB (about, habitat, etc.)
    $val = function($field) use ($lang, $snake) {
        if ($lang === 'en') {
            $val = old($field, $snake ? ($snake->{$field} ?? '') : '');
            return $val;
        }
        return old("{$field}_{$lang}", $snake ? ($snake->{"{$field}_{$lang}"} ?? '') : '');
    };

    // Decode JSON arrays for first_aid and donts
    $decodeList = function($field) use ($lang, $snake) {
        $col = $lang === 'en' ? $field : "{$field}_{$lang}";
        $raw = $snake ? ($snake->{$col} ?? '[]') : '[]';
        $arr = is_array($raw) ? $raw : (json_decode($raw, true) ?? []);
        return implode("\n", $arr);
    };
@endphp

<div class="tab-pane fade" id="tab-{{ $lang }}">
    <div class="card bg-dark border-secondary shadow mb-4">
        <div class="card-header border-secondary">
            <span class="text-secondary small fw-bold">{{ strtoupper($label) }} CONTENT</span>
        </div>
        <div class="card-body p-4">

            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold">ABOUT / DESCRIPTION</label>
                <textarea name="{{ $lang === 'en' ? 'about' : "about_{$lang}" }}"
                          class="form-control bg-transparent text-white border-secondary shadow-none"
                          rows="4"
                          placeholder="About this species...">{{ $val('about') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-secondary small fw-bold">HABITAT</label>
                    <textarea name="{{ $lang === 'en' ? 'habitat' : "habitat_{$lang}" }}"
                              class="form-control bg-transparent text-white border-secondary shadow-none"
                              rows="3"
                              placeholder="Where does it live?">{{ $val('habitat') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-secondary small fw-bold">BEHAVIOR</label>
                    <textarea name="{{ $lang === 'en' ? 'behavior' : "behavior_{$lang}" }}"
                              class="form-control bg-transparent text-white border-secondary shadow-none"
                              rows="3"
                              placeholder="How does it behave?">{{ $val('behavior') }}</textarea>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary small fw-bold">DIET</label>
                <textarea name="{{ $lang === 'en' ? 'diet' : "diet_{$lang}" }}"
                          class="form-control bg-transparent text-white border-secondary shadow-none"
                          rows="2"
                          placeholder="What does it eat?">{{ $val('diet') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-secondary small fw-bold">
                        FIRST AID STEPS
                        <span class="text-muted fw-normal ms-1">(one step per line)</span>
                    </label>
                    <textarea name="{{ $lang === 'en' ? 'first_aid' : "first_aid_{$lang}" }}"
                              class="form-control bg-transparent text-white border-secondary shadow-none"
                              rows="5"
                              placeholder="Step 1&#10;Step 2&#10;Step 3">{{ $decodeList('first_aid') }}</textarea>
                    <small class="text-muted">Each line will be stored as a separate list item.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-secondary small fw-bold">
                        DO NOT DO
                        <span class="text-muted fw-normal ms-1">(one item per line)</span>
                    </label>
                    <textarea name="{{ $lang === 'en' ? 'donts' : "donts_{$lang}" }}"
                              class="form-control bg-transparent text-white border-secondary shadow-none"
                              rows="5"
                              placeholder="Do not apply tourniquet&#10;Do not cut the wound">{{ $decodeList('donts') }}</textarea>
                    <small class="text-muted">Each line will be stored as a separate list item.</small>
                </div>
            </div>

        </div>
    </div>
</div>
