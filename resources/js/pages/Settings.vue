<template>
    <div class="page">
        <h1>Configurações do Usuário</h1>
        
        <div class="container">
            <div class="box settings-card">
                <form @submit.prevent="saveSettings" class="settings-form">
                    
                    <div class="row">
                        <Input 
                            type="number" 
                            v-model="settings.monthly_income" 
                            label="Renda Mensal (R$)" 
                            class="col-12" 
                            placeholder="Ex: 5000.00"
                            step="0.01"
                        />
                    </div>
                    
                    <div class="row">
                        <Input 
                            type="number" 
                            v-model="settings.monthly_savings_goal" 
                            label="Meta Mensal de Poupança (R$)" 
                            class="col-6" 
                            placeholder="Ex: 1000.00"
                            step="0.01"
                        />
                        <Input 
                            type="number" 
                            v-model="settings.savings_percentage" 
                            label="Porcentagem de Economia (%)" 
                            class="col-6" 
                            placeholder="Ex: 20"
                            step="0.01"
                        />
                    </div>

                    <div class="row">
                        <div class="inputWrapper col-12">
                            <label class="inputLabel">Observações</label>
                            <textarea 
                                v-model="settings.notes" 
                                class="textareaField" 
                                rows="4" 
                                placeholder="Anotações sobre suas metas financeiras..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <Button 
                            nativeType="submit" 
                            color="#28a745" 
                            label="<i class='fa-solid fa-save'></i> Salvar Configurações" 
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { api } from '../service/api'
import Input from '../components/utils/Input.vue'
import Button from '../components/utils/Button.vue'

const settings = ref({
    monthly_income: 0,
    monthly_savings_goal: 0,
    savings_percentage: 0,
    notes: ''
})

const loadSettings = async () => {
    try {
        const response = await api.get('setting')
        // A API retorna success, data, message
        if (response.data.success && response.data.data) {
            settings.value = {
                monthly_income: response.data.data.monthly_income,
                monthly_savings_goal: response.data.data.monthly_savings_goal,
                savings_percentage: response.data.data.savings_percentage,
                notes: response.data.data.notes || ''
            }
        }
    } catch (error) {
        console.error('Erro ao carregar configurações', error)
    }
}

const saveSettings = async () => {
    try {
        const response = await api.put('setting', settings.value)
        if (response.data.success) {
            // Optional: Show a success toast/notification here if you have a notification system
            alert('Configurações salvas com sucesso!')
        }
    } catch (error) {
        console.error('Erro ao salvar configurações', error)
        alert('Erro ao salvar configurações.')
    }
}

onMounted(() => {
    loadSettings()
})
</script>

<style lang="scss" scoped>
.container {
    max-width: 800px;
    margin-top: 20px;
}

.settings-card {
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    background-color: #ffffff;
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.inputWrapper {
    display: flex;
    flex-direction: column;
    gap: 5px;

    .inputLabel {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }

    .textareaField {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background-color: #f9fafb;
        color: #111827;
        font-family: inherit;
        resize: vertical;
        transition: all 0.2s ease;

        &:focus {
            border-color: #3b82f6;
            outline: none;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        &::placeholder {
            color: #9ca3af;
        }
    }
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* Responsivo */
@media (max-width: 768px) {
    .settings-card {
        padding: 20px;
    }
}
</style>
