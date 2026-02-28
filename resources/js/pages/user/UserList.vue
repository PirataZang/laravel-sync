<template>
    <div class="page">
        <div>Listagem de usuários</div>
        <div class="container">
            <div class="filters">
                <div class="filters-content">
                    <Input v-model="filters.name.value" label="Pesquisar por nome ou email" placeholder="Digite para filtrar..." width="500px" />
                </div>
                <div class="filters-content">
                    <Button color="#007bff" label="Buscar" @click="reload" />
                </div>
            </div>
            <div class="grid-container">
                <div class="buttons">
                    <Button color="#28a745" label="Criar" type="router" :href="'/user/form'" />
                    <Button color="#007bff" label="Editar" type="router" :disabled="selectedIds.length !== 1" :href="`/user/form/${selectedIds[0]}`" />
                    <Button color="#dc3545" label="Deletar" :disabled="selectedIds.length === 0" @click="deleteUsers" />
                </div>
                <Grid :rowData="data" :columnDefs="colunas" @update:selection="handleSelection" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { api } from '../../service/api'
import Grid from '../../components/utils/Grid.vue'
import Button from '../../components/Utils/Button.vue'
import Input from '../../components/Utils/Input.vue'
import Swal from 'sweetalert2'

const selectedIds = ref<any[]>([])
const data = ref<any[]>([])
const filters = ref<any[]>({
    name: {
        value: '',
        operator: 'like',
    },
})

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
    reload()
})

const reload = async () => {
    const payload = {
        filters: {...filters.value},
        sort: { field: 'id', direction: 'asc' },
        pagination: { page: 1, per_page: 15 },
    }

    try {
        const response = await api.post('user', payload)
        data.value = response.data.data
    } catch (error) {
        console.log(error)
    }
}

const deleteUsers = async () => {
    if (!selectedIds.value.length) return
    try {
        await Promise.all(selectedIds.value.map((id) => api.delete(`user/${id}`)))

        // Remove todos de uma vez
        data.value = data.value.filter((item) => !selectedIds.value.includes(item.id))

        selectedIds.value = []

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Usuário(s) deletado(s) com sucesso!',
            showConfirmButton: false,
            timer: 3000,
        })
    } catch (error) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Erro ao deletar usuário(s)',
            text: error.message,
            showConfirmButton: false,
            timer: 3000,
        })
    }
}
</script>

<style lang="scss" scoped>
.container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 20px;

    .grid-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .filters {
        display: flex;
        flex-direction: column;
        gap: 5px;
        .filters-content {
            display: flex;
            gap: 10px;
        }
    }

    .buttons {
        display: flex;
        gap: 10px;
    }
}
</style>
