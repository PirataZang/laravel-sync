<template>
    <div class="login">
        login
        <form @submit.prevent="login">
            <Input v-model="form.email" label="Email" />
            <Input v-model="form.password" :type="showPass ? 'text' : 'password'" label="Senha" />
            <Button @click="showPass = !showPass" :label="showPass ? 'teu pai' : 'mostrar senha'" />
            <Button type="submit" label="adentrar" />
        </form>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import Input from '../components/utils/Input.vue'
import Button from '../components/Utils/Button.vue'
import { api } from '../service/api'
import router from '../router/routes'

const form = ref({
    email: '',
    password: '',
})

const showPass = ref(false)

const login = async () => {
    try {
        const response = await api.post('/user/login', form.value)

        localStorage.setItem('auth_token', response.data.data.token)
        localStorage.setItem('user', JSON.stringify(response.data.data.user))
        router.push('/')
    } catch (error) {}
}
</script>
