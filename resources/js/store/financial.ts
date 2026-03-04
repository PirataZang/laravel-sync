import { ref, computed } from 'vue'

export const createFinancialStore = () => {
    const currentMonth = ref(new Date().getMonth() + 1)
    const currentYear = ref(new Date().getFullYear())

    const dashboardData = ref<any>(null)
    const error = ref<string | null>(null)
    const isLoading = ref(false)

    const fetchDashboardData = async (month?: number, year?: number) => {
        const month_param = month || currentMonth.value
        const year_param = year || currentYear.value

        isLoading.value = true
        error.value = null

        try {
            const response = await fetch(
                `/api/reports/complete-dashboard?month=${month_param}&year=${year_param}`,
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

            const data = await response.json()
            dashboardData.value = data

            // Update month/year if provided
            if (month) currentMonth.value = month
            if (year) currentYear.value = year
        } catch (err) {
            error.value = err instanceof Error ? err.message : 'Erro desconhecido'
            console.error('Erro ao buscar dados financeiros:', err)
        } finally {
            isLoading.value = false
        }
    }

    const changePage = async (newPage: number) => {
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
    }

    return {
        currentMonth,
        currentYear,
        dashboardData,
        error,
        isLoading,
        fetchDashboardData,
        changePage,
    }
}

