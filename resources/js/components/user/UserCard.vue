<template>
    <div class="user-list">
        <div v-for="user in users" :key="user.id" class="user-card">
            <!-- Checkbox -->
            <div class="select-area">
                <input type="checkbox" :checked="selectedIds.has(user.id)" @change="handleSelect(user, $event)" />
            </div>

            <!-- Conteúdo -->
            <div class="content">
                <div class="top-row">
                    <span class="name">{{ user.name }}</span>
                    <span class="id">#{{ user.id }}</span>
                </div>

                <div class="email">
                    {{ user.email }}
                </div>
            </div>

            <!-- Apenas botão de editar -->
            <Button title="Editar usuário" class="button-card" color="#007bff" label="<i class='fa-solid fa-pen'></i>" type="router" :href="`/user/form/${user.id}`" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import Button from '../Utils/Button.vue'

interface User {
    id: number
    name: string
    email: string
}

const props = defineProps<{
    users: User[]
}>()

const emit = defineEmits<{
    (e: 'select', value: User[]): void
}>()

const selectedUsers = ref<User[]>([])

/* 🔥 Melhor performance que usar .some no template */
const selectedIds = computed(() => new Set(selectedUsers.value.map((u) => u.id)))

function handleSelect(user: User, event: Event) {
    const isChecked = (event.target as HTMLInputElement).checked

    if (isChecked) {
        selectedUsers.value = [...selectedUsers.value, user]
    } else {
        selectedUsers.value = selectedUsers.value.filter((u) => u.id !== user.id)
    }

    emit('select', selectedUsers.value)
}
</script>

<style scoped lang="scss">
.user-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.user-card {
    background: #fff;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;

    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
    }

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
        min-width: 0;

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .name {
            font-weight: 600;
            font-size: 15px;
            color: #111827;
        }

        .id {
            font-size: 12px;
            color: #9ca3af;
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .email {
            font-size: 13px;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    .button-card {
        width: 10px;
        height: 20px;
    }
}
</style>
