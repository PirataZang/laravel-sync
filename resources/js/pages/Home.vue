<template>
    <div class="page">
        <Button label="<i class='fa-solid fa-user'></i> aaaa" @click="alertShow" />
        <div class="relative top-5px">
            <Grid :rowData="data" :columnDefs="colunas" @update:selection="handleSelection" />
        </div>
        <i class="fa-solid fa-user"></i>
        <ChartsBase width="350px" type="line" label="Vendas" :labels="['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun']" :data="[45, 67, 52, 78, 89, 64]" borderColor="rgb(59, 130, 246)" :backgroundColor="'rgba(59, 130, 246, 0.1)'" />
        <ChartsBase width="350px" type="pie" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
        <ChartsBase width="350px" type="bar" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
        <ChartsBase width="350px" type="doughnut" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import Button from '../components/Utils/Button.vue'
import Grid from '../components/utils/Grid.vue'
import ChartsBase from '../components/utils/ChartsBase.vue'
import { api } from '../service/api'
import collect from 'collect.js'

const selectedIds = ref<any[]>([])
const data = ref<any[]>([])



const handleSelection = (data: any[]) => {
    selectedIds.value = data.map((item) => item.id)
}

const colunas = [
    { headerName: 'ID', field: 'id', filter: true },
    { headerName: 'Nome', field: 'name' },
    { headerName: 'Email', field: 'email' },
    { headerName: 'Ativo', field: 'active', align: 'center', width: 95},
    { headerName: 'Cadastrado em', field: 'created_at' },
    { headerName: 'Atualizado em', field: 'updated_at' },
]

onMounted(async () => {
    try {
        const response = await api.get('user')
        data.value = collect(response.data.data).toArray()
    } catch (error) {
        console.log(error)
    }
})
</script>
