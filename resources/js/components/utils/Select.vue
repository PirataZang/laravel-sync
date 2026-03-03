<template>
    <div class="custom-select" ref="root" :class="{ open: isOpen }">
        <span v-html="label" class="select-label"></span>
        <div class="select-display" role="combobox" :aria-expanded="isOpen.toString()" @click="toggle" tabindex="0">
            <template v-if="multiple">
                <div class="tags">
                    <span v-for="opt in selectedOptions" :key="opt.value" class="tag">
                        {{ opt.label }}
                        <button class="tag-remove" @click.stop="removeOption(opt)"><i class="fa-solid fa-xmark"></i></button>
                    </span>
                </div>
                <span v-if="!selectedOptions.length && placeholder" class="placeholder">{{ placeholder }}</span>
            </template>

            <template v-else>
                <span class="single-value">
                    {{ selectedOptions[0]?.label || placeholder || '' }}
                </span>
            </template>
            <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
        </div>

        <transition name="fade">
            <div v-if="isOpen" class="dropdown" role="listbox" :aria-multiselectable="multiple.toString()">
                <template v-if="search">
                    <input type="text" class="search-input" v-model="searchTerm" placeholder="Buscar..." />
                </template>

                <ul class="options-list">
                    <li v-for="opt in filteredOptions" :key="opt.value" class="option" :class="{ disabled: opt.disabled, selected: isSelected(opt) }" @click="select(opt)" role="option" :aria-selected="isSelected(opt).toString()">
                        <template v-if="multiple">
                            <input type="checkbox" class="option-checkbox" :checked="isSelected(opt)" :disabled="opt.disabled" @click.stop="select(opt)" />
                        </template>
                        {{ opt.label }}
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'

interface Option {
    value: string | number
    label: string
    disabled?: boolean
}

const props = defineProps<{
    options: Option[]
    label?: string
    modelValue: string | number | Array<string | number> | null
    multiple?: boolean
    search?: boolean
    placeholder?: string
}>()

const emit = defineEmits<{
    (e: 'update:modelValue', val: string | number | Array<string | number> | null): void
    (e: 'change', val: string | number | Array<string | number> | null): void
}>()

const multiple = computed(() => !!props.multiple)
const search = computed(() => !!props.search)
const placeholder = computed(() => props.placeholder || '')

const isOpen = ref(false)
const searchTerm = ref('')
const root = ref<HTMLElement | null>(null)

const selectedValues = ref<Array<string | number>>(multiple.value ? (Array.isArray(props.modelValue) ? [...props.modelValue] : []) : props.modelValue != null ? [props.modelValue as string | number] : [])

watch(
    () => props.modelValue,
    (nv) => {
        if (multiple.value) {
            selectedValues.value = Array.isArray(nv) ? [...nv] : []
        } else {
            selectedValues.value = nv != null ? [nv as string | number] : []
        }
    },
)

const filteredOptions = computed(() => {
    if (search.value && searchTerm.value) {
        const term = searchTerm.value.toLowerCase()
        return props.options.filter((o) => o.label.toLowerCase().includes(term))
    }
    return props.options
})

const selectedOptions = computed(() => props.options.filter((o) => selectedValues.value.includes(o.value)))

function isSelected(opt: Option) {
    return selectedValues.value.includes(opt.value)
}

function select(opt: Option) {
    if (opt.disabled) return
    if (multiple.value) {
        if (isSelected(opt)) {
            selectedValues.value = selectedValues.value.filter((v) => v !== opt.value)
        } else {
            selectedValues.value = [...selectedValues.value, opt.value]
        }
        emit('update:modelValue', selectedValues.value)
        emit('change', selectedValues.value)
    } else {
        selectedValues.value = opt.value != null ? [opt.value] : []
        emit('update:modelValue', selectedValues.value[0] || null)
        emit('change', selectedValues.value[0] || null)
        close()
    }
}

function removeOption(opt: Option) {
    if (!multiple.value) return
    selectedValues.value = selectedValues.value.filter((v) => v !== opt.value)
    emit('update:modelValue', selectedValues.value)
    emit('change', selectedValues.value)
}

function toggle() {
    isOpen.value = !isOpen.value
    if (isOpen.value && search.value) {
        searchTerm.value = ''
    }
}

function close() {
    isOpen.value = false
}

function handleClickOutside(e: MouseEvent) {
    if (root.value && !root.value.contains(e.target as Node)) {
        close()
    }
}

onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside)
})
onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleClickOutside)
})
</script>

<style scoped lang="scss">
.custom-select {
    position: relative;
    width: 250px;
    font-family: Arial, sans-serif;

    &.open {
        .select-display {
            border-color: #666;
        }
    }
}

.select-label {
    font-size: 13px;
    color: #374151;
    display: block;
    margin-bottom: 1px;
}

.select-display {
    border: 1px solid #ccc;
    padding: 2px;
    display: flex;
    border-radius: 7px;
    align-items: center;
    min-height: 32px;
    cursor: pointer;
    background: #fff;
    transition: border 0.15s ease;

    &:focus {
        outline: none;
        border-color: #666;
    }

    .arrow {
        margin-left: auto;
        font-size: 12px;
    }
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    scrollbar-width: thin;
    padding: 5px;
    max-height: 200px;
    overflow-y: auto;
}

.tag {
    background: #e0e0e0;
    border-radius: 5px;
    padding: 3px 6px;
    display: flex;
    align-items: center;
    font-size: 12px;

    .tag-remove {
        border: none;
        background: transparent;
        cursor: pointer;
        padding: 2px 0px 0px 5px;
        color: red;
        font-size: 12px;

        &:hover {
            opacity: 0.7;
        }
    }
}

.placeholder {
    color: #888;
    font-size: 14px;
}

.dropdown {
    position: absolute;
    top: 100%;
    scrollbar-width: thin;
    left: 0;
    right: 0;
    border: 1px solid #ccc;
    background: #fff;
    max-height: 200px;
    overflow-y: auto;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.search-input {
    margin: 5px;
    width: 100%;
    padding: 4px 6px;
    border: none;
    border-bottom: 1px solid #ccc;
    box-sizing: border-box;

    &:focus {
        outline: none;
    }
}

.options-list {
    list-style: none;
    margin: 0;
    padding: 0;

    .option {
        padding: 6px 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background 0.15s ease;

        &:hover {
            background: #f0f0f0;
        }

        &.disabled {
            color: #aaa;
            cursor: not-allowed;

            &:hover {
                background: transparent;
            }
        }

        &.selected {
            background: #e6f7ff;
        }

        .option-checkbox {
            margin-right: 8px;
        }
    }
}

.single-value {
    flex: 1;
}

/* Transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>