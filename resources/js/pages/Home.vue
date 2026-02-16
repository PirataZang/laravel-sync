<template>
    <div class="page">
        <Button label="aaaa" @click="alertShow" />
        <div class="relative top-5px">
            <Grid :rowData="dados" :columnDefs="colunas" @update:selection="handleSelection" />
        </div>
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
    { headerName: 'ID', field: 'id', filter: true },
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