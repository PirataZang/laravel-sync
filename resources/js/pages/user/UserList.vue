<template>
    <div class="page">
        <div>Listagem de usuários</div>
        <div class="relative top-5px">
            <Grid :rowData="data" :columnDefs="colunas" @update:selection="handleSelection" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { api } from '../../service/api'
import Grid from '../../components/utils/Grid.vue'

const selectedIds = ref<any[]>([])
const data = ref<any[]>([])

const handleSelection = (data: any[]) => {
    selectedIds.value = data.map((item) => item.id)
}

const colunas = [
    { headerName: 'ID', field: 'id', filter: true, width: 95 },
    { headerName: 'Nome', field: 'name' },
    { headerName: 'Email', field: 'email', cellClass: 'cell-left' },
    { headerName: 'Ativo', field: 'active', cellClass: 'cell-center', width: 95 },
    { headerName: 'Cadastrado em', field: 'created_at', type: 'date' },
    { headerName: 'Atualizado em', field: 'updated_at', type: 'datetime' },
]

onMounted(async () => {
    try {
        const response = await api.get('user')
        data.value = response.data.data
    } catch (error) {
        console.log(error)
    }
})
</script>
