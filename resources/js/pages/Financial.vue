<template>
    <div class="dashboard-page">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">💼 Painel Financeiro</h1>
                <p class="dashboard-subtitle">
                    Acompanhe sua saúde financeira em tempo real
                </p>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="loading-state">
                <div class="loading-spinner"></div>
                <p>Carregando dados financeiros...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="error-state">
                <div class="error-icon">⚠️</div>
                <p class="error-message">{{ error }}</p>
                <button class="retry-btn" @click="loadData">
                    Tentar Novamente
                </button>
            </div>

            <!-- Dashboard Content -->
            <template v-else-if="dashboardData">
                <!-- Financial Indicators -->
                <FinancialIndicators
                    :data="dashboardData"
                    :currentMonth="currentMonth"
                    :currentYear="currentYear"
                    @update-month="updateMonth"
                />

                <!-- Financial Charts -->
                <FinancialCharts :data="dashboardData" />

                <!-- Transactions Table -->
                <TransactionsTable
                    :data="dashboardData"
                    :onPageChange="handlePageChange"
                />
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import { createFinancialStore } from '../../store/financial'
import FinancialIndicators from '../../components/dashboard/FinancialIndicators.vue'
import FinancialCharts from '../../components/dashboard/FinancialCharts.vue'
import TransactionsTable from '../../components/dashboard/TransactionsTable.vue'

const currentMonth = ref(new Date().getMonth() + 1)
const currentYear = ref(new Date().getFullYear())
const dashboardData = ref<any>(null)
const error = ref<string | null>(null)
const isLoading = ref(false)

const financialStore = createFinancialStore()

onMounted(() => {
    loadData()
})

const loadData = async () => {
    isLoading.value = true
    error.value = null

    try {
        const response = await fetch(
            `/api/reports/complete-dashboard?month=${currentMonth.value}&year=${currentYear.value}`,
            {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                },
            }
        )

        if (!response.ok) {
            throw new Error('Erro ao carregar dados do painel')
        }

        dashboardData.value = await response.json()
    } catch (err) {
        error.value = err instanceof Error ? err.message : 'Erro desconhecido'
        console.error('Erro ao buscar dados financeiros:', err)
    } finally {
        isLoading.value = false
    }
}

const handlePageChange = async (newPage: number) => {
    isLoading.value = true
    try {
        const response = await fetch(
            `/api/reports/monthly-transactions?month=${currentMonth.value}&year=${currentYear.value}&page=${newPage}`,
            {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Content-Type': 'application/json',
                },
            }
        )

        if (response.ok) {
            const data = await response.json()
            if (dashboardData.value) {
                dashboardData.value.transactions = data.transactions
                dashboardData.value.pagination = data.pagination
            }
        }
    } finally {
        isLoading.value = false
    }
}

const updateMonth = (month: number, year: number) => {
    currentMonth.value = month
    currentYear.value = year
}

// Watch for month/year changes
watch([currentMonth, currentYear], () => {
    loadData()
})
</script>

<style scoped lang="scss">
.dashboard-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 2rem 1rem;

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 2rem;
        animation: slideDown 0.5s ease-out;

        .dashboard-title {
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.5rem;
            letter-spacing: -0.5px;
        }

        .dashboard-subtitle {
            font-size: 1rem;
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }
    }

    .loading-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        color: #475569;

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            margin: 0;
            font-weight: 500;
        }
    }

    .error-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #ef4444;

        .error-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .error-message {
            text-align: center;
            color: #475569;
            margin: 0 0 1.5rem;
            font-size: 1rem;
            font-weight: 500;
        }

        .retry-btn {
            padding: 0.75rem 1.5rem;
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;

            &:hover {
                background-color: #dc2626;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            }

            &:active {
                transform: translateY(0);
            }
        }
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@media (max-width: 768px) {
    .dashboard-page {
        padding: 1rem;

        .dashboard-container {
            max-width: 100%;
        }

        .dashboard-header {
            margin-bottom: 1.5rem;

            .dashboard-title {
                font-size: 1.75rem;
            }

            .dashboard-subtitle {
                font-size: 0.95rem;
            }
        }
    }
}

@media (max-width: 480px) {
    .dashboard-page {
        padding: 0.75rem;

        .dashboard-header {
            .dashboard-title {
                font-size: 1.5rem;
            }

            .dashboard-subtitle {
                font-size: 0.85rem;
            }
        }
    }
}
</style>
