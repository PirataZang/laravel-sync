<template>
    <div class="category-list">
        <div v-for="category in categories" :key="category.id" class="category-card">
            <!-- Checkbox -->
            <div class="select-area">
                <input type="checkbox" :checked="selectedIds.has(category.id)" @change="handleSelect(category, $event)" />
            </div>

            <!-- Conteúdo -->
            <div class="content">
                <div class="top-row">
                    <span class="name">{{ category.name }}</span>
                    <span class="id">#{{ category.id }}</span>
                </div>

                <div class="meta">
                    <span class="type">{{ category.type }}</span>
                    <span :class="['status', category.active ? 'active' : 'inactive']">
                        {{ category.active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>

            <!-- Botão de editar -->
            <Button title="Editar categoria" class="button-card" color="#007bff" label="<i class='fa-solid fa-pen'></i>" type="router" :href="`/category/form/${category.id}`" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import Button from '../utils/Button.vue'

interface Category {
    id: number
    name: string
    type: string
    active: boolean
}

const props = defineProps<{
    categories: Category[]
}>()

const emit = defineEmits<{
    (e: 'select', value: Category[]): void
}>()

const selectedCategories = ref<Category[]>([])

const selectedIds = computed(() => new Set(selectedCategories.value.map((c) => c.id)))

function handleSelect(category: Category, event: Event) {
    const isChecked = (event.target as HTMLInputElement).checked

    if (isChecked) {
        selectedCategories.value = [...selectedCategories.value, category]
    } else {
        selectedCategories.value = selectedCategories.value.filter((c) => c.id !== category.id)
    }

    emit('select', selectedCategories.value)
}
</script>

<style scoped lang="scss">
.category-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.category-card {
    background: #fff;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;

    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
    }

    .select-area {
        display: flex;
        align-items: center;

        input[type='checkbox'] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
    }

    .content {
        flex: 1;
        min-width: 0;

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .name {
            font-weight: 600;
            font-size: 15px;
            color: #111827;
        }

        .id {
            font-size: 12px;
            color: #9ca3af;
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .meta {
            display: flex;
            gap: 12px;
            font-size: 12px;

            .type {
                color: #6b7280;
                background: #f0f1f3;
                padding: 3px 10px;
                border-radius: 6px;
            }

            .status {
                padding: 3px 10px;
                border-radius: 6px;
                font-weight: 500;

                &.active {
                    color: #065f46;
                    background: #d1fae5;
                }

                &.inactive {
                    color: #7f1d1d;
                    background: #fee2e2;
                }
            }
        }
    }

    .button-card {
        width: 10px;
        height: 20px;
    }
}
</style>
