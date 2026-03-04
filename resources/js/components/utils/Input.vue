<template>
    <div class="inputWrapper" :class="attrs.class" :style="{ width }">
        <label v-html="label" class="inputLabel"></label>

        <div class="inputGroup" :class="{ isDisabled: disabled }">
            <input v-bind="inputAttrs" v-model="model" :type="type" :name="inputName" :autocomplete="autocompleteValue" :disabled="disabled" :class="['inputField', { isSmall: small }]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, useAttrs } from 'vue'
import type { InputHTMLAttributes } from 'vue'

/* Tipos possíveis para input */
type InputType = 'text' | 'password' | 'email' | 'number' | 'date' | 'search' | 'tel' | 'url'

interface Props {
    width?: string
    label?: string
    placeholder?: string
    type?: InputType
    disabled?: boolean
    small?: boolean
    name?: string | null
    autocomplete?: string | null
}

const props = withDefaults(defineProps<Props>(), {
    width: '',
    type: 'text',
    disabled: false,
    small: false,
    name: null,
    autocomplete: null,
})

/* v-model tipado */
const model = defineModel<string | number>({
    default: '',
})

const attrs = useAttrs()

const generatedName = 'inp_' + Math.random().toString(36).slice(2, 9)

const inputName = computed(() => props.name || generatedName)

const autocompleteValue = computed(() => {
    if (props.autocomplete) return props.autocomplete
    if (props.type === 'password') return 'new-password'
    return 'off'
})

/* remove class do attrs para não duplicar */
const inputAttrs = computed(() => {
    const { class: _class, ...rest } = attrs as InputHTMLAttributes
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
