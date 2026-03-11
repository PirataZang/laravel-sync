<template>
    <div class="category-form">
        <h1>{{ isEdit ? 'Editar' : 'Criar' }} categoria</h1>
        <form @submit.prevent="save(stay)" autocomplete="off" class="form-container">
            <div class="row">
                <Input class="col-3" required v-model="form.name" label="Nome" />
                <Select class="col-3" label="Tipo" v-model="form.type" :options="typeOptions" placeholder="Selecione um tipo" required />
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

// Opções de tipo de categoria
const typeOptions = [
    { value: 'income', label: 'Receita' },
    { value: 'expense', label: 'Despesa' },
    { value: 'transfer', label: 'Transferência' },
]

// Métodos de ciclo de vida
onMounted(() => {
    if (route.params.id) {
        isEdit.value = true
        reload()
    }
})

// Funções
const form = reactive({
    id: route.params.id || null,
    name: '',
    type: null,
})

const showAlert = () => {
    Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    }).fire({
        title: 'Sucesso!',
        text: `Categoria ${isEdit.value ? 'atualizada' : 'criada'} com sucesso!`,
        icon: 'success',
    })
}

const reload = async () => {
    if (!form.id) return

    try {
        const { data } = await api.get('category/' + form.id)
        Object.assign(form, data?.data || {})
    } catch (error) {
        console.error('Error loading category:', error)
    }
}

const save = async () => {
    try {
        const endpoint = isEdit.value ? `category/${form.id}` : 'category'
        const method = isEdit.value ? 'put' : 'post'
        const payload = { ...form }
        const response = await api[method](endpoint, payload)

        showAlert()

        if (!stay.value) router.push('/category')
    } catch (error) {
        Swal.fire({
            title: 'Erro!',
            text: `Ocorreu um erro ao ${isEdit.value ? 'atualizar' : 'salvar'} a categoria.`,
            icon: 'error',
        })
    }
}
</script>

<style scoped lang="scss">
</style>
