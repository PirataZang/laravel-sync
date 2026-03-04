<template>
    <div class="transactions-section">
        <div class="section-header">
            <h2 class="section-title">Transações do Mês</h2>
            <div class="section-stats" v-if="data?.transactions">
                <span class="stat-badge">
                    Total: {{ data.pagination.total }} transações
                </span>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="transactions-table" v-if="data?.transactions && data.transactions.length > 0">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="transaction in data.transactions"
                        :key="transaction.id"
                        :class="['transaction-row', transaction.category_type]"
                    >
                        <td class="col-description">
                            <div class="transaction-name">
                                {{ transaction.name }}
                            </div>
                            <div class="transaction-desc" v-if="transaction.description">
                                {{ transaction.description }}
                            </div>
                        </td>
                        <td class="col-category">
                            <span class="category-badge">
                                {{ transaction.category }}
                            </span>
                        </td>
                        <td class="col-type">
                            <span
                                class="type-badge"
                                :class="transaction.category_type"
                            >
                                {{ transaction.category_type === 'income' ? '📥' : '📤' }}
                                {{ transaction.type }}
                            </span>
                        </td>
                        <td class="col-amount">
                            <span
                                class="amount"
                                :class="transaction.category_type"
                            >
                                {{ transaction.category_type === 'income' ? '+' : '-' }} R$ {{ formatCurrency(transaction.amount) }}
                            </span>
                        </td>
                        <td class="col-date">
                            {{ transaction.date }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-else-if="data?.transactions && data.transactions.length === 0" class="empty-state">
                <div class="empty-icon">💭</div>
                <p class="empty-title">Nenhuma transação</p>
                <p class="empty-description">Não há transações registradas para este período</p>
            </div>

            <div v-else class="table-skeleton">
                <div v-for="i in 5" :key="i" class="skeleton-row"></div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper" v-if="data?.pagination && data.pagination.last_page > 1">
            <div class="pagination-info">
                <span class="pagination-text">
                    Mostrando {{ data.pagination.from }} a {{ data.pagination.to }} de {{ data.pagination.total }}
                </span>
            </div>

            <div class="pagination-controls">
                <button
                    class="pagination-btn"
                    :disabled="data.pagination.current_page === 1"
                    @click="previousPage"
                >
                    ← Anterior
                </button>

                <div class="pagination-numbers">
                    <button
                        v-for="page in visiblePages"
                        :key="page"
                        class="page-btn"
                        :class="{ active: page === data.pagination.current_page }"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </button>
                </div>

                <button
                    class="pagination-btn"
                    :disabled="data.pagination.current_page === data.pagination.last_page"
                    @click="nextPage"
                >
                    Próximo →
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'

interface Transaction {
    id: string
    name: string
    description?: string
    category: string
    category_type: 'income' | 'expense'
    type: string
    amount: number
    date: string
}

interface Pagination {
    current_page: number
    per_page: number
    total: number
    last_page: number
    from: number
    to: number
}

const props = defineProps({
    data: {
        type: Object as any,
        default: null
    },
    onPageChange: {
        type: Function,
        default: null
    }
})

const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value)
}

const visiblePages = computed(() => {
    if (!props.data?.pagination) return []

    const current = props.data.pagination.current_page
    const last = props.data.pagination.last_page
    const delta = 2

    let pages = []

    // First page
    if (current - delta > 1) {
        pages.push(1)
        if (current - delta > 2) {
            pages.push('...')
        }
    }

    // Pages around current
    for (let i = Math.max(1, current - delta); i <= Math.min(last, current + delta); i++) {
        pages.push(i)
    }

    // Last page
    if (current + delta < last) {
        if (current + delta < last - 1) {
            pages.push('...')
        }
        pages.push(last)
    }

    return pages
})

const previousPage = () => {
    if (props.data?.pagination.current_page > 1 && props.onPageChange) {
        props.onPageChange(props.data.pagination.current_page - 1)
    }
}

const nextPage = () => {
    if (props.data?.pagination.current_page < props.data.pagination.last_page && props.onPageChange) {
        props.onPageChange(props.data.pagination.current_page + 1)
    }
}

const goToPage = (page: number) => {
    if (typeof page === 'number' && props.onPageChange) {
        props.onPageChange(page)
    }
}
</script>

<style scoped lang="scss">
.transactions-section {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-top: 2rem;

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid #e2e8f0;

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .section-stats {
            .stat-badge {
                display: inline-block;
                padding: 0.375rem 0.75rem;
                background-color: #f1f5f9;
                color: #475569;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                font-weight: 500;
            }
        }
    }

    .table-wrapper {
        overflow-x: auto;
        position: relative;

        .transactions-table {
            width: 100%;
            border-collapse: collapse;

            thead {
                background-color: #f8fafc;
                border-bottom: 2px solid #e2e8f0;

                th {
                    padding: 1rem 1.5rem;
                    text-align: left;
                    font-size: 0.875rem;
                    font-weight: 600;
                    color: #475569;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
            }

            tbody {
                .transaction-row {
                    border-bottom: 1px solid #e2e8f0;
                    transition: all 0.3s ease;

                    &:hover {
                        background-color: #f8fafc;
                    }

                    &.income {
                        td {
                            border-left: 3px solid #22c55e;

                            .amount {
                                color: #16a34a;
                            }
                        }
                    }

                    &.expense {
                        td {
                            border-left: 3px solid #ef4444;

                            .amount {
                                color: #dc2626;
                            }
                        }
                    }

                    td {
                        padding: 1rem 1.5rem;
                        font-size: 0.875rem;
                        color: #475569;

                        .transaction-name {
                            font-weight: 600;
                            color: #1e293b;
                        }

                        .transaction-desc {
                            font-size: 0.75rem;
                            color: #94a3b8;
                            margin-top: 0.25rem;
                        }

                        .category-badge {
                            display: inline-block;
                            padding: 0.375rem 0.75rem;
                            background-color: #f1f5f9;
                            color: #475569;
                            border-radius: 0.375rem;
                            font-size: 0.875rem;
                            font-weight: 500;
                        }

                        .type-badge {
                            display: inline-block;
                            padding: 0.375rem 0.75rem;
                            border-radius: 0.375rem;
                            font-size: 0.875rem;
                            font-weight: 600;

                            &.income {
                                background-color: #dcfce7;
                                color: #166534;
                            }

                            &.expense {
                                background-color: #fee2e2;
                                color: #991b1b;
                            }
                        }

                        .amount {
                            font-weight: 700;
                            font-size: 0.95rem;
                        }
                    }
                }
            }
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
            text-align: center;
            color: #94a3b8;

            .empty-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }

            .empty-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #475569;
                margin: 0 0 0.5rem;
            }

            .empty-description {
                font-size: 0.875rem;
                color: #94a3b8;
                margin: 0;
            }
        }

        .table-skeleton {
            .skeleton-row {
                height: 60px;
                background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
                border-bottom: 1px solid #e2e8f0;
            }
        }
    }

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;

        .pagination-info {
            .pagination-text {
                font-size: 0.875rem;
                color: #475569;
                font-weight: 500;
            }
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;

            .pagination-btn,
            .page-btn {
                padding: 0.5rem 1rem;
                background-color: white;
                border: 1px solid #e2e8f0;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #475569;
                cursor: pointer;
                transition: all 0.3s ease;

                &:hover:not(:disabled) {
                    background-color: #f1f5f9;
                    border-color: #cbd5e1;
                }

                &:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }

                &.active {
                    background-color: #3b82f6;
                    color: white;
                    border-color: #3b82f6;
                }
            }

            .pagination-numbers {
                display: flex;
                gap: 0.25rem;
            }
        }
    }
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

@media (max-width: 768px) {
    .transactions-section {
        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .table-wrapper {
            .transactions-table {
                thead {
                    display: none;
                }

                tbody {
                    .transaction-row {
                        display: block;
                        margin-bottom: 1rem;
                        border-bottom: 1px solid #e2e8f0;

                        td {
                            display: block;
                            padding: 0.75rem;
                            text-align: right;
                            border-left: none !important;

                            &:first-child {
                                text-align: left;
                                font-weight: 600;
                                background-color: #f1f5f9;
                                padding-bottom: 0.5rem;
                            }

                            &::before {
                                content: attr(data-label);
                                display: block;
                                float: left;
                                font-weight: 600;
                                color: #1e293b;
                                font-size: 0.75rem;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            }
                        }
                    }
                }
            }
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;

            .pagination-info {
                text-align: center;
            }

            .pagination-controls {
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    }
}
</style>
