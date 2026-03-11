<template>
    <div class="transaction-form">
        <h1>{{ isEdit ? 'Editar' : 'Criar' }} transação</h1>
        <form @submit.prevent="save(stay)" autocomplete="off" class="form-container">
            <div class="row">
                <Input class="col-3" required v-model="form.name" label="Descrição" />
                <Input class="col-3" required v-model="form.amount" type="number" step="0.01" label="Valor" />
                <Select class="col-3" label="Categoria" v-model="form.category_id" :options="categoryOptions" placeholder="Selecione uma categoria" required />
            </div>
            <div class="row">
                <Input class="col-6" v-model="form.description" label="Descrição adicional" placeholder="Detalhes sobre a transação" />
            </div>
            <Button nativeType="submit" @click="stay = false" :label="isEdit ? 'Salvar' : 'Cadastrar'" />
            <Button nativeType="submit" @click="stay = true" :label="isEdit ? 'Salvar e Continuar' : 'Cadastrar e Continuar'" />
        </form>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import Input from '../../components/utils/Input.vue'
import Button from '../../components/utils/Button.vue'
import Select from '../../components/utils/Select.vue'
import { useRoute } from 'vue-router'
import { api } from '../../service/api'
import Swal from 'sweetalert2'
import router from '../../router/routes'

const isEdit = ref(false)
const route = useRoute()
const stay = ref(false)
const categoryOptions = ref([])

// Métodos de ciclo de vida
onMounted(() => {
    loadCategories()
    if (route.params.id) {
        isEdit.value = true
        reload()
    }
})

// Funções
const form = reactive({
    id: route.params.id || null,
    name: '',
    description: '',
    amount: '',
    category_id: null,
})

const loadCategories = async () => {
    try {
        const { data } = await api.get('category', {
            params: {
                pagination: { per_page: 999 },
            },
        })
        categoryOptions.value = data.data.map((cat) => ({
            value: cat.id,
            label: cat.name,
        }))
    } catch (error) {
        console.error('Error loading categories:', error)
    }
}

const showAlert = () => {
    Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    }).fire({
        title: 'Sucesso!',
        text: `Transação ${isEdit.value ? 'atualizada' : 'criada'} com sucesso!`,
        icon: 'success',
    })
}

const reload = async () => {
    if (!form.id) return

    try {
        const { data } = await api.get('transaction/' + form.id)
        Object.assign(form, data?.data || {})
    } catch (error) {
        console.error('Error loading transaction:', error)
    }
}

const save = async () => {
    try {
        const endpoint = isEdit.value ? `transaction/${form.id}` : 'transaction'
        const method = isEdit.value ? 'put' : 'post'
        const payload = { ...form }
        const response = await api[method](endpoint, payload)

        showAlert()

        if (!stay.value) router.push('/transaction')
    } catch (error) {
        Swal.fire({
            title: 'Erro!',
            text: `Ocorreu um erro ao ${isEdit.value ? 'atualizar' : 'salvar'} a transação.`,
            icon: 'error',
        })
    }
}
</script>

<style></style>
