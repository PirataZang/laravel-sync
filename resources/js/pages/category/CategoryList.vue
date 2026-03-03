<template>
    <div class="page">
        <h1>Listagem de Categorias</h1>
        <div class="container">
            <div class="box filters">
                <div class="row">
                    <Input v-model="filters.name.value" @keyup.enter="reload" label="Pesquisar por nome ou email" placeholder="Digite para filtrar..." class="col-6" />
                </div>
                <div class="filters-content">
                    <Button color="#007bff" label="<i class='fa-solid fa-magnifying-glass'></i> Buscar" @click="reload" />
                </div>
                <div class="buttons">
                    <Button color="#28a745" label="<i class='fa-solid fa-plus'></i> Criar" type="router" :href="'/category/form'" />
                    <Button color="#007bff" label="<i class='fa-solid fa-pen'></i> Editar" type="router" :disabled="selectedIds.length !== 1" :href="`/category/form/${selectedIds[0]}`" />
                    <Button color="#dc3545" label="<i class='fa-solid fa-trash-can'></i> Deletar" :disabled="selectedIds.length === 0" @click="deleteCategorys" />
                </div>
            </div>
            <div class="grid-container">
                <Grid :rowData="data" :paginationPageSize="pageSize" :columnDefs="colunas" @update:selection="handleSelection" @update:page="currentPage = $event" @update:pageSize="pageSize = $event" />
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
const currentPage = ref(1)
const pageSize = ref(50)

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
        filters: { ...filters.value },
        sort: { field: 'id', direction: 'asc' },
        pagination: { page: currentPage.value, per_page: pageSize.value },
    }

    try {
        const response = await api.get('category', {
            params: payload,
        })
        data.value = response.data.data
    } catch (error) {
        console.log(error)
    }
}

const deleteCategorys = async () => {
    if (!selectedIds.value.length) return
    try {
        await Promise.all(selectedIds.value.map((id) => api.delete(`category/${id}`)))

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
}
</style>
