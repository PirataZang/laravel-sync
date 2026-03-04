<template>
    <div class="financial-indicators">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Card Total Income -->
            <div class="indicator-card income">
                <div class="card-header">
                    <span class="card-icon">💰</span>
                    <span class="card-title">Total de Receitas</span>
                </div>
                <div class="card-value" v-if="data">
                    R$ {{ formatCurrency(data.summary.total_income) }}
                </div>
                <div class="card-value skeleton" v-else></div>
            </div>

            <!-- Card Total Expense -->
            <div class="indicator-card expense">
                <div class="card-header">
                    <span class="card-icon">💸</span>
                    <span class="card-title">Total de Despesas</span>
                </div>
                <div class="card-value" v-if="data">
                    R$ {{ formatCurrency(data.summary.total_expense) }}
                </div>
                <div class="card-value skeleton" v-else></div>
            </div>

            <!-- Card Balance -->
            <div class="indicator-card" :class="data?.summary.status === 'positive' ? 'positive' : 'negative'">
                <div class="card-header">
                    <span class="card-icon">
                        {{ data?.summary.status === 'positive' ? '📈' : '📉' }}
                    </span>
                    <span class="card-title">Saldo Final</span>
                </div>
                <div class="card-value" v-if="data">
                    R$ {{ formatCurrency(data.summary.balance) }}
                </div>
                <div class="card-value skeleton" v-else></div>
                <div class="card-badge" :class="data?.summary.status" v-if="data">
                    {{ data.summary.status === 'positive' ? '✓ Positivo' : '✗ Negativo' }}
                </div>
            </div>
        </div>

        <!-- Month/Year Selector -->
        <div class="month-selector" v-if="data">
            <div class="selector-info">
                <span class="text-gray-600">
                    Mês de {{ formatMonth(currentMonth) }} de {{ currentYear }}
                </span>
            </div>
            <div class="selector-buttons">
                <button class="selector-btn" @click="previousMonth" title="Mês anterior">
                    ← Anterior
                </button>
                <button class="selector-btn" @click="currentMonth_" title="Mês atual">
                    Atual
                </button>
                <button class="selector-btn" @click="nextMonth" title="Próximo mês">
                    Próximo →
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

interface FinancialSummary {
    summary: {
        total_income: number
        total_expense: number
        balance: number
        status: 'positive' | 'negative'
        month: number
        year: number
    }
}

const props = defineProps({
    data: {
        type: Object as any,
        default: null
    },
    currentMonth: {
        type: Number,
        required: true
    },
    currentYear: {
        type: Number,
        required: true
    }
})

const emit = defineEmits(['update-month'])

const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value)
}

const formatMonth = (month: number): string => {
    const months = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ]
    return months[month - 1]
}

const previousMonth = () => {
    let month = props.currentMonth
    let year = props.currentYear

    if (month === 1) {
        month = 12
        year -= 1
    } else {
        month -= 1
    }

    emit('update-month', month, year)
}

const nextMonth = () => {
    let month = props.currentMonth
    let year = props.currentYear

    if (month === 12) {
        month = 1
        year += 1
    } else {
        month += 1
    }

    emit('update-month', month, year)
}

const currentMonth_ = () => {
    const now = new Date()
    emit('update-month', now.getMonth() + 1, now.getFullYear())
}
</script>

<style scoped lang="scss">
.financial-indicators {
    .grid {
        margin-bottom: 2rem;
    }

    .indicator-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #cbd5e1;
        transition: all 0.3s ease;

        &:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        &.income {
            border-left-color: #3b82f6;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        }

        &.expense {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        }

        &.positive {
            border-left-color: #22c55e;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }

        &.negative {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;

            .card-icon {
                font-size: 1.5rem;
            }

            .card-title {
                color: #64748b;
                font-size: 0.875rem;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;

            &.skeleton {
                height: 2.5rem;
                background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
                border-radius: 0.5rem;
            }
        }

        .card-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;

            &.positive {
                background-color: #dcfce7;
                color: #166534;
            }

            &.negative {
                background-color: #fee2e2;
                color: #991b1b;
            }
        }
    }

    .month-selector {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-top: 1.5rem;

        .selector-info {
            font-size: 0.95rem;
            font-weight: 600;
            color: #475569;
        }

        .selector-buttons {
            display: flex;
            gap: 0.75rem;

            .selector-btn {
                padding: 0.5rem 1rem;
                background-color: #f1f5f9;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #475569;
                cursor: pointer;
                transition: all 0.3s ease;

                &:hover {
                    background-color: #3b82f6;
                    color: white;
                    border-color: #3b82f6;
                }

                &:active {
                    transform: scale(0.95);
                }
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
    .financial-indicators {
        .month-selector {
            flex-direction: column;
            gap: 1rem;

            .selector-info {
                width: 100%;
                text-align: center;
            }

            .selector-buttons {
                width: 100%;
            }

            .selector-btn {
                flex: 1;
            }
        }
    }
}
</style>
