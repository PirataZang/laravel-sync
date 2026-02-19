<template>
    <div class="page">
        <div class="relative top-5px">
            <Grid :rowData="data" :columnDefs="colunas" @update:selection="handleSelection" />
        </div>

        <Input v-model="name" label="<i class='fa-solid fa-user'></i> teste" placeholder="Tomate" width="300px" />
        <Input v-model="email" label="<i class='fa-solid fa-user'></i> teste" placeholder="Perá" width="300px" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import Button from '../components/Utils/Button.vue'
import Grid from '../components/utils/Grid.vue'
import Input from '../components/utils/Input.vue'
import { api } from '../service/api'
import collect from 'collect.js'

const selectedIds = ref<any[]>([])
const data = ref<any[]>([])

const handleSelection = (data: any[]) => {
    selectedIds.value = data.map((item) => item.id)
}


const name = ref('')
const email = ref('')

const colunas = [
    { headerName: 'ID', field: 'id', filter: true, width: 95 },
    { headerName: 'Nome', field: 'name' },
    { headerName: 'Email', field: 'email' },
    { headerName: 'Ativo', field: 'active', align: 'center', width: 95 },
    { headerName: 'Cadastrado em', field: 'created_at', type: 'date' },
    { headerName: 'Atualizado em', field: 'updated_at', type: 'datetime' },
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
