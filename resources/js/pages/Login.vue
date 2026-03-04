<template>
    <div class="login-page">
        <div class="login-card">
            <h2>Entrar</h2>

            <form @submit.prevent="login">
                <Input v-model="form.email" label="Email" type="email" />
                <Input v-model="form.password" :type="showPass ? 'text' : 'password'" label="Senha" />

                <div class="row">
                    <Button class="col-6" :native-type="'button'" @click="showPass = !showPass" :label="showPass ? `<i class='fa-solid fa-eye'></i> Ocultar` : `<i class='fa-solid fa-eye-slash'></i> Mostrar`" />
                    <Button class="col-6" :native-type="'submit'" label="Entrar" />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Input from '../components/utils/Input.vue'
import Button from '../components/Utils/Button.vue'
import { api } from '../service/api'
import router from '../router/routes'
import Swal from 'sweetalert2'

const form = ref({
    email: '',
    password: '',
})

const showPass = ref(false)

const login = async () => {
    if (!form.value.email || !form.value.password) {
        Swal.fire({
            icon: 'error',
            title: 'Campos obrigatórios',
            text: 'Por favor preencha email e senha.',
        })
        return
    }

    try {
        const response = await api.post('/user/login', form.value)

        const user = response.data.data.user
        localStorage.setItem('auth_token', response.data.data.token)
        localStorage.setItem('user', JSON.stringify(user))

        await Swal.fire({
            icon: 'success',
            title: 'Bem‑vindo!',
            text: `Olá, ${user.name}`,
            timer: 1500,
            showConfirmButton: false,
        })

        router.push('/')
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro ao autenticar',
            text: error.response?.data?.message || 'Não foi possível fazer login. Verifique suas credenciais.',
        })
    }
}
</script>

<style scoped lang="scss">
.login-page {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    /* creative gradient background */
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.login-card {
    background: #fff;
    padding: 2.5rem 2rem;
    border-radius: 12px;
    width: 360px;
    max-width: 90%;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);

    h2 {
        margin-bottom: 1rem;
        color: #333;
        font-size: 1.75rem;
        font-weight: 600;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .toggle-pass {
        flex: 1;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }
}
</style>
