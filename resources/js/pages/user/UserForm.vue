<template>
    <div class="user-form">
        <h1>{{ isEdit ? 'Editar' : 'Criar' }} usuário</h1>
        <form @submit.prevent="save(stay)" autocomplete="off" class="form-container">
            <div class="row">
                <Input class="col-3" required v-model="form.name" label="Nome" />
                <Input class="col-3" required v-model="form.email" label="Email" />
                <Input class="col-3" type="password" v-model="form.password" label="Senha" />
                <!-- custom select para perfil/role -->
                <Select class="col-3" multiple label="Perfil" search v-model="form.role" :options="roleOptions" placeholder="Perfil" />
            </div>
            <Button nativeType="submit" @click="stay = false" :label="isEdit ? 'Salvar' : 'Cadastrar'" />
            <Button nativeType="submit" @click="stay = true" :label="isEdit ? 'Salvar e Continuar' : 'Cadastrar'" />
        </form>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import Input from '../../components/utils/Input.vue'
import Button from '../../components/utils/Button.vue'
import { useRoute } from 'vue-router'
import { api } from '../../service/api'
import Swal from 'sweetalert2'
import router from '../../router/routes'
import Select from '../../components/utils/Select.vue'

const isEdit = ref(false)
const route = useRoute()
const stay = ref(false)

// opções de perfil/role do usuário
const roleOptions = [
    { value: 1, label: 'Admin' },
    { value: 2, label: 'Comum' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
    { value: 3, label: 'Client' },
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
    email: '',
    password: '',
    role: null,
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
        text: `Usuário ${isEdit.value ? 'atualizado' : 'criado'} com sucesso!`,
        icon: 'success',
    })
}

const reload = async () => {
    if (!form.id) return

    try {
        const { data } = await api.get('user/' + form.id)
        Object.assign(form, data?.data || {})
    } catch (error) {
        console.error('Error loading user:', error)
    }
}

const save = async () => {
    try {
        const endpoint = isEdit.value ? `user/${form.id}` : 'user'
        const method = isEdit.value ? 'put' : 'post'
        const payload = { ...form }
        const response = await api[method](endpoint, payload)

        showAlert()

        if (!stay.value) router.push('/user')
    } catch (error) {
        Swal.fire({
            title: 'Erro!',
            text: `Ocorreu um erro ao ${isEdit.value ? 'atualizar' : 'salvar'} o usuário.`,
            icon: 'error',
        })
    }
}
</script>

<style></style>
