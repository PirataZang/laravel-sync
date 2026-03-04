<template>
    <a v-if="type == 'link'" :href="disabled ? undefined : href" target="_blank" class="button-style" :class="{ disabled }" :style="buttonStyle" @click.prevent="handleClick">
        <span v-html="label"></span>
    </a>

    <router-link v-else-if="type == 'router'" :to="disabled ? '' : href" class="button-style" :class="{ disabled }" :style="buttonStyle" @click.prevent="handleClick">
        <span v-html="label"></span>
    </router-link>

    <button v-else class="button-style" :style="buttonStyle" :disabled="disabled">
        <span v-html="label"></span>
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { CSSProperties } from 'vue'

type ButtonType = 'button' | 'link' | 'router'

interface Props {
    label: string
    type?: ButtonType
    to?: string
    href?: string
    color?: string
    disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    type: 'button',
    color: '#0d3ecf',
    disabled: false,
})

const buttonStyle = computed<CSSProperties>(() => ({
    '--btn-color': props.color,
}))

const handleClick = (e: MouseEvent) => {
    if (props.disabled) {
        e.preventDefault()
        e.stopPropagation()
    }
}
</script>

<style lang="scss" scoped>
.button-style {
    --btn-color: #0d3ecf;

    padding: 10px 18px;
    margin: 4px;
    background: var(--btn-color);
    color: #fff;
    border: none;
    border-radius: 10px;
    cursor: pointer;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    font-size: 14px;
    font-weight: 500;
    text-decoration: none;

    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.18);
    transition:
        background 0.25s ease,
        transform 0.12s ease,
        box-shadow 0.12s ease,
        opacity 0.2s ease;

    &:hover:not(.disabled):not(:disabled) {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.22);
    }

    &:active:not(.disabled):not(:disabled) {
        transform: translateY(0);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    &:focus-visible {
        outline: 3px solid rgba(59, 130, 246, 0.4);
        outline-offset: 2px;
    }

    &.disabled,
    &:disabled {
        filter: grayscale(25%);
        cursor: not-allowed;
        opacity: 0.7;
        box-shadow: none;
        transform: none;
        pointer-events: none;
    }
}
</style>
