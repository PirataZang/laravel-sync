<template>
    <div class="page">
        <Button label="<i class='fa-solid fa-user'></i> aaaa" @click="alertShow" />
        <div class="relative top-5px">
            <Grid :rowData="dados" :columnDefs="colunas" @update:selection="handleSelection" />
        </div>
        <i class="fa-solid fa-user"></i>
        <ChartsBase width="350px" type="line" label="Vendas" :labels="['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun']" :data="[45, 67, 52, 78, 89, 64]" borderColor="rgb(59, 130, 246)" :backgroundColor="'rgba(59, 130, 246, 0.1)'" />
        <ChartsBase width="350px" type="pie" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
        <ChartsBase width="350px" type="bar" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
        <ChartsBase width="350px" type="doughnut" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Button from '../components/Utils/Button.vue'
import Grid from '../components/utils/Grid.vue'
import ChartsBase from '../components/utils/ChartsBase.vue'

const selectedIds = ref<any[]>([])

const handleSelection = (data: any[]) => {
    selectedIds.value = data.map((item) => item.id)
}

const alertShow = () => {
    return alert(`aaaaa ${selectedIds.value}`)
}

const colunas = [
    { headerName: 'ID', field: 'id', filter: true},
    { headerName: 'Nome', field: 'nome' },
    { headerName: 'Email', field: 'email' },
    { headerName: 'Idade', field: 'idade' },
    { headerName: 'Cargo', field: 'cargo' },
]

const cargos = ['Desenvolvedor', 'QA', 'Designer', 'PO', 'DevOps']

// gerador maroto de dados fake
const dados = ref(
    Array.from({ length: 25 }).map((_, i) => ({
        id: i + 1,
        nome: `Pessoa ${i + 1}`,
        email: `pessoa${i + 1}@empresa.com`,
        idade: 18 + Math.floor(Math.random() * 30),
        cargo: cargos[Math.floor(Math.random() * cargos.length)],
    })),
)
</script>