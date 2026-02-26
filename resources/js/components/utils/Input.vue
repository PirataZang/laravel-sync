<template>
    <div class="inputWrapper" :class="attrs.class" :style="{ width }">
        <label v-html="label" class="inputLabel"></label>

        <div class="inputGroup" :class="{ isDisabled: disabled }">
            <input v-bind="inputAttrs" v-model="model" :type="type" :name="inputName" :autocomplete="autocompleteValue" :disabled="disabled" :class="['inputField', { isSmall: small }]" />
        </div>
    </div>
</template>

<script setup>
import { computed, useAttrs } from 'vue'

const attrs = useAttrs()

const model = defineModel({
    type: [String, Number],
    default: '',
})

const props = defineProps({
    width: { type: String, default: '' },
    label: String,
    placeholder: String,
    type: { type: String, default: 'text' },
    disabled: { type: Boolean, default: false },
    small: { type: Boolean, default: false },
    name: { type: String, default: null },
    autocomplete: { type: String, default: null },
})

const generatedName = 'inp_' + Math.random().toString(36).slice(2, 9)

const inputName = computed(() => props.name || generatedName)

const autocompleteValue = computed(() => {
    if (props.autocomplete) return props.autocomplete
    if (props.type === 'password') return 'new-password'
    return 'off'
})

const inputAttrs = computed(() => {
    const { class: _class, ...rest } = attrs
    return rest
})
</script>

<style lang="scss" scoped>
.inputWrapper {
    display: flex;
    flex-direction: column;
    gap: 2px;

    .inputLabel {
        font-size: 13px;
        color: #374151;
    }

    .inputGroup {
        position: relative;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;

        &.isDisabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        &:not(.isDisabled) {
            cursor: text;
        }

        .inputField {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            background-color: #fff;
            color: #111827;
            outline: none;
            transition: all 0.2s ease;

            &::placeholder {
                color: #9ca3af;
            }

            &:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            }

            &:disabled {
                background-color: #f3f4f6;
                cursor: not-allowed;
            }
        }

        .isSmall {
            padding: 1px;
            padding-left: 5px;
        }
    }

    .inputErrorText {
        margin-top: 0.125rem;
        font-size: 0.75rem;
        color: #ef4444;
    }
}
</style>
