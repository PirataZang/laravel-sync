<template>
    <div class="transaction-list">
        <div v-for="transaction in transactions" :key="transaction.id" class="transaction-card">
            <!-- Checkbox -->
            <div class="select-area">
                <input type="checkbox" :checked="selectedIds.has(transaction.id)" @change="handleSelect(transaction, $event)" />
            </div>

            <!-- Conteúdo -->
            <div class="content">
                <div class="top-row">
                    <span class="name">{{ transaction.name }}</span>
                    <span class="id">#{{ transaction.id }}</span>
                </div>

                <div class="description">
                    {{ transaction.description }}
                </div>

                <div class="meta">
                    <span class="category">{{ transaction.category?.name }}</span>
                    <span :class="['amount', transaction.amount >= 0 ? 'positive' : 'negative']">
                        {{ formatCurrency(transaction.amount) }}
                    </span>
                </div>

                <div class="date">
                    {{ formatDate(transaction.created_at) }}
                </div>
            </div>

            <!-- Botão de editar -->
            <Button title="Editar transação" class="button-card" color="#007bff" label="<i class='fa-solid fa-pen'></i>" type="router" :href="`/transaction/form/${transaction.id}`" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import Button from '../utils/Button.vue'

interface Transaction {
    id: number
    name: string
    description: string
    amount: number
    category?: { name: string }
    created_at: string
}

const props = defineProps<{
    transactions: Transaction[]
}>()

const emit = defineEmits<{
    (e: 'select', value: Transaction[]): void
}>()

const selectedTransactions = ref<Transaction[]>([])

const selectedIds = computed(() => new Set(selectedTransactions.value.map((t) => t.id)))

function handleSelect(transaction: Transaction, event: Event) {
    const isChecked = (event.target as HTMLInputElement).checked

    if (isChecked) {
        selectedTransactions.value = [...selectedTransactions.value, transaction]
    } else {
        selectedTransactions.value = selectedTransactions.value.filter((t) => t.id !== transaction.id)
    }

    emit('select', selectedTransactions.value)
}

function formatCurrency(value: number): string {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value)
}

function formatDate(dateString: string): string {
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(date)
}
</script>

<style scoped lang="scss">
.transaction-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.transaction-card {
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
            margin-bottom: 4px;
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

        .description {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .meta {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 6px;
            font-size: 12px;

            .category {
                color: #6b7280;
                background: #f0f1f3;
                padding: 3px 10px;
                border-radius: 6px;
            }

            .amount {
                font-weight: 600;
                padding: 3px 10px;
                border-radius: 6px;

                &.positive {
                    color: #065f46;
                    background: #d1fae5;
                }

                &.negative {
                    color: #7f1d1d;
                    background: #fee2e2;
                }
            }
        }

        .date {
            font-size: 12px;
            color: #9ca3af;
        }
    }

    .button-card {
        width: 10px;
        height: 20px;
    }
}
</style>
