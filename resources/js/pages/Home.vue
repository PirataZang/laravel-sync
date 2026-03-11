<template>
    <div class="page">
        <h1>Dashboard</h1>
        <div class="container">
            <div class="box filters">
                <div class="row">
                    <Input type="date" v-model="filters.start_date" @keyup.enter="loadDashboard" label="Data Início" class="col-6" />
                    <Input type="date" v-model="filters.end_date" @keyup.enter="loadDashboard" label="Data Fim" class="col-6" />
                </div>
                <div class="filters-content">
                    <Button color="#007bff" label="<i class='fa-solid fa-filter'></i> Filtrar" @click="loadDashboard" />
                </div>
            </div>

            <!-- Totais -->
            <div class="totals-grid">
                <div class="box total-card">
                    <h3>Receitas</h3>
                    <p class="income">{{ formatCurrency(totals.income || 0) }}</p>
                </div>
                <div class="box total-card">
                    <h3>Despesas</h3>
                    <p class="expense">{{ formatCurrency(totals.expense || 0) }}</p>
                </div>
                <div class="box total-card">
                    <h3>Saldo Parcial</h3>
                    <p :class="totals.balance >= 0 ? 'income' : 'expense'">{{ formatCurrency(totals.balance || 0) }}</p>
                </div>
            </div>

            <!-- Gráficos -->
            <div v-if="loaded" class="charts-grid">
                <div class="box chart-container">
                    <h3>Entradas vs Saídas</h3>
                    <ChartsBase
                        type="doughnut"
                        :labels="charts.income_vs_expense.labels"
                        :data="charts.income_vs_expense.data"
                        :backgroundColor="charts.income_vs_expense.backgroundColor"
                        label="Total"
                    />
                </div>
                <div class="box chart-container">
                    <h3>Despesas por Categoria</h3>
                    <ChartsBase
                        type="pie"
                        :labels="charts.expenses_by_category.labels"
                        :data="charts.expenses_by_category.data"
                        :backgroundColor="charts.expenses_by_category.backgroundColor"
                        label="Despesas"
                    />
                </div>
                <div class="box chart-container">
                    <h3>Receitas por Categoria</h3>
                    <ChartsBase
                        type="pie"
                        :labels="charts.incomes_by_category.labels"
                        :data="charts.incomes_by_category.data"
                        :backgroundColor="charts.incomes_by_category.backgroundColor"
                        label="Receitas"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '../service/api'
import Input from '../components/utils/Input.vue'
import Button from '../components/utils/Button.vue'
import ChartsBase from '../components/utils/ChartsBase.vue'

const filters = ref({
    start_date: '',
    end_date: ''
})

const loaded = ref(false)
const totals = ref({
    income: 0,
    expense: 0,
    balance: 0
})
const charts = ref({
    incomes_by_category: { labels: [], data: [], backgroundColor: [] },
    expenses_by_category: { labels: [], data: [], backgroundColor: [] },
    income_vs_expense: { labels: [], data: [], backgroundColor: [] }
})

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value)
}

const loadDashboard = async () => {
    try {
        loaded.value = false;
        const response = await api.get('dashboard', { params: filters.value })
        const { data } = response.data
        
        totals.value = data.totals
        charts.value = data.charts
        
        // Small delay to ensure DOM is ready for Chart rendering
        setTimeout(() => {
            loaded.value = true;
        }, 100)
    } catch (error) {
        console.error('Erro ao carregar dashboard', error)
    }
}

onMounted(() => {
    // Set default month
    const currentDate = new Date()
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1)
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0)
    
    // adjust timezone offset to get correct ISO date string
    const formatISODate = (date: Date) => {
        const offset = date.getTimezoneOffset()
        const adjustedDate = new Date(date.getTime() - (offset*60*1000))
        return adjustedDate.toISOString().split('T')[0]
    }

    filters.value.start_date = formatISODate(firstDay)
    filters.value.end_date = formatISODate(lastDay)
    
    loadDashboard()
})
</script>

<style lang="scss" scoped>
.container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;
}

.totals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;

    .total-card {
        text-align: center;
        padding: 20px 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        
        h3 {
            margin-bottom: 10px;
            color: #6c757d;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        p {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            
            &.income {
                color: #28a745;
            }
            
            &.expense {
                color: #dc3545;
            }
        }
    }
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;

    .chart-container {
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;

        h3 {
            margin-bottom: 20px;
            color: #495057;
            text-align: center;
            width: 100%;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
        }
        
        // This targets the chart wrapper rendered by ChartsBase
        :deep(.chart-wrapper) {
            border: none;
            box-shadow: none;
            width: 100% !important;
            height: 300px;
            
            canvas {
                max-height: 100%;
                width: auto !important;
            }
        }
    }
}

@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .totals-grid {
        grid-template-columns: 1fr;
    }
}
</style>
