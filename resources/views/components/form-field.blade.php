{{-- resources/views/components/form-field.blade.php --}}

@php
    $inputClasses = "w-full px-4 py-3 border-2 border-orange-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent " . ($error ? 'border-red-500' : '');
    $labelClasses = "block text-sm font-medium text-amber-800 mb-1";
@endphp

<div class="form-field-wrapper">
    <label for="{{ $field['key'] }}" class="{{ $labelClasses }}">
        <i class="{{ $field['icon'] }} text-orange-500 mr-1"></i>
        {{ $field['name'] }}
        @if($field['required'])
            <span class="text-danger">*</span>
        @endif
    </label>

    @switch($field['type'])
        @case('text')
        @case('email')
        @case('tel')
        @case('number')
        @case('date')
            <input 
                type="{{ $field['type'] }}" 
                class="{{ $inputClasses }}"
                id="{{ $field['key'] }}" 
                name="{{ $field['key'] }}"
                value="{{ $old_value }}"
                placeholder="{{ $field['placeholder'] ?? '' }}"
                {{ $field['required'] ? 'required' : '' }}
                @if($field['type'] === 'number')
                    @if(str_contains($field['validation'], 'min:'))
                        min="{{ preg_match('/min:(\d+)/', $field['validation'], $matches) ? $matches[1] : '' }}"
                    @endif
                    @if(str_contains($field['validation'], 'max:'))
                        max="{{ preg_match('/max:(\d+)/', $field['validation'], $matches) ? $matches[1] : '' }}"
                    @endif
                    @if($field['key'] === 'ipk')
                        step="0.01" min="0" max="4"
                    @endif
                @endif
            >
            @break

        @case('textarea')
            <textarea 
                class="{{ $inputClasses }}" 
                id="{{ $field['key'] }}" 
                name="{{ $field['key'] }}" 
                rows="4" 
                placeholder="{{ $field['placeholder'] ?? '' }}"
                {{ $field['required'] ? 'required' : '' }}
            >{{ $old_value }}</textarea>
            @break

        @case('select')
            <select 
                class="{{ str_replace('form-control', 'form-select', $inputClasses) }}" 
                id="{{ $field['key'] }}" 
                name="{{ $field['key'] }}"
                {{ $field['required'] ? 'required' : '' }}
            >
                <option value="">{{ $field['placeholder'] ?? '-- Pilih --' }}</option>
                @if(isset($field['options']) && is_array($field['options']))
                    @foreach($field['options'] as $option)
                        <option value="{{ $option['value'] }}" {{ $old_value == $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @endforeach
                @endif
            </select>
            @break

        @case('radio')
            <div class="space-y-2 mt-2">
                @if(isset($field['options']) && is_array($field['options']))
                    @foreach($field['options'] as $option)
                        <div class="flex items-center">
                            <input 
                                type="radio" 
                                class="mr-2" 
                                id="{{ $field['key'] }}_{{ $option['value'] }}" 
                                name="{{ $field['key'] }}" 
                                value="{{ $option['value'] }}"
                                {{ $old_value == $option['value'] ? 'checked' : '' }}
                                {{ $field['required'] ? 'required' : '' }}
                            >
                            <label for="{{ $field['key'] }}_{{ $option['value'] }}" class="text-amber-800">
                                {{ $option['label'] }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            @break

        @case('checkbox')
            <div class="space-y-2 mt-2">
                @if(isset($field['options']) && is_array($field['options']))
                    @foreach($field['options'] as $option)
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                class="mr-2" 
                                id="{{ $field['key'] }}_{{ $option['value'] }}" 
                                name="{{ $field['key'] }}[]" 
                                value="{{ $option['value'] }}"
                                {{ is_array($old_value) && in_array($option['value'], $old_value) ? 'checked' : '' }}
                            >
                            <label for="{{ $field['key'] }}_{{ $option['value'] }}" class="text-amber-800">
                                {{ $option['label'] }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            @break

        @default
            <input 
                type="text" 
                class="{{ $inputClasses }}"
                id="{{ $field['key'] }}" 
                name="{{ $field['key'] }}"
                value="{{ $old_value }}"
                placeholder="{{ $field['placeholder'] ?? '' }}"
                {{ $field['required'] ? 'required' : '' }}
            >
    @endswitch

    @if($error)
        <div class="text-red-500 text-sm mt-1">{{ $error }}</div>
    @endif

    @if(!empty($field['validation']) && str_contains($field['validation'], 'min:') && $field['type'] === 'textarea')
        <div class="text-right text-sm mt-1">
            <span class="text-orange-600" id="char-count-{{ $field['key'] }}">0</span>
            <span class="text-orange-600">
                / {{ preg_match('/max:(\d+)/', $field['validation'], $matches) ? $matches[1] : '1000' }} karakter
            </span>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('{{ $field['key'] }}');
                const counter = document.getElementById('char-count-{{ $field['key'] }}');
                if (textarea && counter) {
                    function updateCount() {
                        counter.textContent = textarea.value.length;
                    }
                    textarea.addEventListener('input', updateCount);
                    updateCount();
                }
            });
        </script>
    @endif
</div>