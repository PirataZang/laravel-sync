<template>
    <div class="user-form">
        <h1>{{ isEdit ? 'Editar' : 'Criar' }} usuário</h1>
        <form @submit.prevent="submit" autocomplete="off">
            <div class="row">
                <Input class="col-3" required v-model="form.name" label="Nome" />
                <Input class="col-3" required v-model="form.email" label="Email" />
                <Input class="col-1" required v-model="form.email" label="Email" />
                <Input class="col-2" required v-model="form.email" label="Email" />
                <Input class="col-2" required v-model="form.email" label="Email" />
            </div>

            <Button type="button" @click="toggleShow" :label="showPass ? 'Ocultar senha' : 'Mostrar senha'" />
            <Button type="submit" :label="isEdit ? 'Salvar' : 'Criar'" />
        </form>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import Input from '../../components/utils/Input.vue'
import Button from '../../components/Utils/Button.vue'

const isEdit = ref(false)
const showPass = ref(false)

const form = reactive({
    name: '',
    email: '',
    password: '',
})

const toggleShow = () => {
    showPass.value = !showPass.value
}

const submit = async () => {
    console.log('submit', { ...form })
}
</script>

<style></style>
