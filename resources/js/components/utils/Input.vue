<template>
    <div class="inputWrapper" :style="{ width: width }">
        <label v-html="label" class="inputLabel"></label>

        <div class="inputGroup" :class="{ isDisabled: disabled }">
            <input v-bind="$attrs" :type="type" :placeholder="placeholder" v-model="localValue" :disabled="disabled" :class="{ inputField: true, isSmall: small }" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: [String, Number],
    width: { type: String, default: '' },
    label: String,
    placeholder: String,
    type: { type: String, default: 'text' },
    disabled: { type: Boolean, default: false },
    small: { type: Boolean, default: false },
    required: { type: Boolean, default: false }, // 👈 novo!
})

const emit = defineEmits(['update:modelValue'])

const localValue = ref(props.modelValue)

watch(
    () => props.modelValue,
    (val) => {
        if (val !== localValue.value) localValue.value = val
    },
)

watch(localValue, (val) => {
    emit('update:modelValue', val)
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

        &.hasError {
            .inputField {
                border-color: #ef4444;
                box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
            }

            &:focus {
                border-color: #ef4444;
            }

            .inputErrorIcon {
                color: #ef4444;
            }
        }

        .isSmall {
            padding: 1px;
            padding-left: 5px;
        }

        .inputErrorIcon {
            position: absolute;
            right: 0.75rem;
            font-size: 0.875rem;
            color: #ef4444;
        }
    }

    .inputErrorText {
        margin-top: 0.125rem;
        font-size: 0.75rem;
        color: #ef4444;
    }
}
</style>
