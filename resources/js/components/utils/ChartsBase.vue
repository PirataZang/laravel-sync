<template>
    <div :class="`w-[${props.width}]`" class="chart-wrapper" :style="{ width: props.width }">
        <canvas ref="chartContainer"></canvas>
    </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import Chart from 'chart.js/auto'
import { onBeforeUnmount } from 'vue'

interface Props {
    type: 'line' | 'bar' | 'doughnut' | 'pie'
    labels?: string[]
    data?: any[]
    label: string
    height?: string
    datasets?: any[] | null
    width?: string
    borderColor?: string
    backgroundColor?: string[] | string
}

const props = withDefaults(defineProps<Props>(), {
    borderColor: 'rgb(75, 192, 192)',
    backgroundColor: 'rgba(75, 192, 192, 0.5)',
})

const chartContainer = ref<HTMLCanvasElement | null>(null)
let chartInstance: Chart | null = null

onMounted(() => {
    if (!chartContainer.value) return

    chartInstance = new Chart(chartContainer.value, {
        type: props.type,
        data: {
            labels: props.labels,
            datasets: props.datasets ?? [
                {
                    label: props.label,
                    data: props.data,
                    borderColor: props.borderColor,
                    backgroundColor: props.backgroundColor,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom' as const,
                    labels: {
                        font: {
                            size: 10,
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: {
                            size: 10,
                        },
                    },
                },
                x: {
                    ticks: {
                        font: {
                            size: 10,
                        },
                    },
                },
            },
        },
    })
})

onBeforeUnmount(() => {
    if (chartInstance) {
        chartInstance.destroy()
    }
})
</script>

<style lang="scss" scoped>
.chart-wrapper {
    border-radius: 1rem; // rounded-2xl
    padding: 0.5rem; // p-2
    border: 1px solid #e5e7eb; // border-gray-200
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); // shadow-md

    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
