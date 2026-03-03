<template>
    <div class="user-list">
        <div v-for="user in users" :key="user.id" class="user-card">
            <!-- Checkbox -->
            <div class="select-area">
                <input type="checkbox" :checked="selectedUsers.some((u) => u.id === user.id)" @change="handleSelect(user, $event)" />
            </div>

            <!-- Conteúdo -->
            <div class="content">
                <div class="header">
                    <span class="name">{{ user.name }}</span>
                    <span class="id">{{ user.id }}</span>
                </div>

                <div class="email">
                    {{ user.email }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
})

const emit = defineEmits(['select'])

// guarda usuários selecionados
const selectedUsers = ref([])

function handleSelect(user, event) {
    const isChecked = event.target.checked

    if (isChecked) {
        selectedUsers.value.push(user)
    } else {
        selectedUsers.value = selectedUsers.value.filter((u) => u.id !== user.id)
    }

    // 🔥 sempre envia todos selecionados
    emit('select', selectedUsers.value)
}
</script>

<style scoped lang="scss">
.user-list {
    display: flex;
    flex-direction: column;
    gap: 16px;

    .user-card {
        background: white;
        border-radius: 18px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: center;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        position: relative;

        .select-area {
            display: flex;
            align-items: center;

            input[type='checkbox'] {
                width: 18px;
                height: 18px;
                cursor: pointer;
            }
        }

        .content {
            flex: 1;
            min-width: 0; // 🔥 importante pra ellipsis funcionar

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 6px;

                .name {
                    font-weight: 600;
                    font-size: 16px;
                }

                .id {
                    font-size: 13px;
                    opacity: 0.6;
                }
            }

            .email {
                font-size: 13px;
                color: #555;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis; // 🔥 evita quebrar feio
            }
        }
    }
}
</style>
