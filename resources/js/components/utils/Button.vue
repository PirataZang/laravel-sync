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

<script setup>
import { computed } from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true,
        default: 'Botão',
    },
    type: {
        type: String,
        default: '',
    },
    to: {
        type: String,
    },

    href: {
        type: String,
        default: null,
    },
    color: {
        type: String,
        default: '#0d3ecf', // azul padrão
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})

const buttonStyle = computed(() => ({
    '--btn-color': props.color,
}))

const handleClick = (e) => {
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
