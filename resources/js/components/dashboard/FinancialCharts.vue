<template>
    <div class="financial-charts">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expenses by Category Chart -->
            <div class="chart-container">
                <h3 class="chart-title">Distribuição de Gastos por Categoria</h3>
                <div v-if="data?.charts?.expenses_by_category" class="chart-wrapper">
                    <ChartsBase
                        v-if="data.charts.expenses_by_category.labels.length > 0"
                        type="doughnut"
                        :labels="data.charts.expenses_by_category.labels"
                        :datasets="[
                            {
                                data: data.charts.expenses_by_category.values,
                                backgroundColor: data.charts.expenses_by_category.colors,
                                borderColor: '#fff',
                                borderWidth: 2,
                            },
                        ]"
                        label="Gastos por Categoria"
                        width="100%"
                    />
                    <div v-else class="empty-chart">
                        <p>Nenhuma despesa neste mês</p>
                    </div>
                </div>
                <div v-else class="chart-skeleton"></div>
            </div>

            <!-- Income vs Expense Chart -->
            <div class="chart-container">
                <h3 class="chart-title">Receitas vs Despesas</h3>
                <div v-if="data?.charts?.income_vs_expense" class="chart-wrapper">
                    <ChartsBase
                        type="bar"
                        :labels="data.charts.income_vs_expense.labels"
                        :datasets="[
                            {
                                label: 'Valores',
                                data: data.charts.income_vs_expense.values,
                                backgroundColor: data.charts.income_vs_expense.colors,
                                borderColor: data.charts.income_vs_expense.borderColors,
                                borderWidth: 2,
                            },
                        ]"
                        width="100%"
                    />
                </div>
                <div v-else class="chart-skeleton"></div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
import ChartsBase from '../utils/ChartsBase.vue'

interface FinancialCharts {
    charts?: {
        expenses_by_category?: {
            labels: string[]
            values: number[]
            colors: string[]
        }
        income_vs_expense?: {
            labels: string[]
            values: number[]
            colors: string[]
            borderColors: string[]
        }
    }
}

defineProps({
    data: {
        type: Object as any,
        default: null,
    },
})
</script>

<style scoped lang="scss">
.financial-charts {
    .grid {
        margin-top: 2rem;
    }

    .chart-container {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;

        &:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .chart-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;

            &::before {
                content: '';
                width: 4px;
                height: 20px;
                background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                border-radius: 2px;
            }
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .empty-chart {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 300px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.75rem;
            color: #94a3b8;
            font-size: 1rem;
            font-weight: 500;

            p {
                margin: 0;
            }
        }

        .chart-skeleton {
            height: 300px;
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 0.75rem;
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

@media (max-width: 1024px) {
    .financial-charts {
        .grid {
            grid-template-columns: 1fr !important;
        }
    }
}

@media (max-width: 640px) {
    .financial-charts {
        .chart-container {
            padding: 1rem;

            .chart-title {
                font-size: 1rem;
            }

            .chart-wrapper {
                height: 250px;
            }
        }
    }
}
</style>
