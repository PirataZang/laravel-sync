# Filtro Searchable Dinâmico e Genérico

## 📋 Visão Geral

Sistema completo de filtros dinâmicos reutilizável em qualquer Service Laravel. Suporta:
- **Múltiplos operadores** (like, =, !=, >, <, >=, <=, in, between, null, not_null)
- **Ordenação** (sort) dinâmica
- **Paginação** automática
- **Metadados** de resposta (total, página atual, última página, etc)

---

## 🔧 Como Usar no Backend

### 1. Adicionar a Trait ao Service

```php
<?php

namespace App\Service;

use App\Traits\SearchableFilter;

class UserService extends Service
{
    use SearchableFilter; // ✅ Adicione a trait
    
    protected function modelClass(): string
    {
        return User::class;
    }
}
```

> **Nota**: A classe base `Service` já usa a trait, então você não precisa adicionar em cada service!

---

## 📨 Padrão de Requisição (Front-end para Backend)

### Requisição Completa

```javascript
const payload = {
    filters: {
        name: {
            value: "João",
            operator: "like"  // ✅ Busca parcial
        },
        email: {
            value: "teste@email.com",
            operator: "="     // ✅ Exato
        },
        created_at: {
            value: "2025-01-01",
            operator: ">"     // ✅ Maior que
        },
        status: {
            value: ["active", "pending"],
            operator: "in"    // ✅ Em lista
        }
    },
    sort: {
        field: "created_at",
        direction: "desc"     // ✅ asc ou desc
    },
    pagination: {
        page: 1,
        per_page: 15          // ✅ Registros por página
    }
}

const response = await api.post('user', payload)
```

### Requisição Mínima

```javascript
// Sem filtros, usa padrão (página 1, 15 registros)
const payload = {
    filters: {},
    sort: { field: 'id', direction: 'asc' },
    pagination: { page: 1, per_page: 15 }
}

const response = await api.post('user', payload)
```

---

## 📦 Padrão de Resposta (Backend para Front-end)

```json
{
    "success": true,
    "message": "OK",
    "data": [
        {
            "id": 1,
            "name": "João Silva",
            "email": "joao@email.com",
            "active": 1,
            "created_at": "2025-01-01 10:30:00",
            "updated_at": "2025-01-15 14:20:00"
        },
        {
            "id": 2,
            "name": "Maria Santos",
            "email": "maria@email.com",
            "active": 1,
            "created_at": "2025-01-02 09:15:00",
            "updated_at": "2025-01-16 11:45:00"
        }
    ],
    "meta": {
        "total": 156,           // ✅ Total geral de registros
        "per_page": 15,         // ✅ Registros por página
        "current_page": 1,      // ✅ Página atual
        "last_page": 11,        // ✅ Última página
        "from": 1,              // ✅ Registro inicial da página
        "to": 15,               // ✅ Registro final da página
        "has_more": true        // ✅ Tem próxima página?
    }
}
```

---

## 🔍 Operadores Disponíveis

| Operador | Descrição | Exemplo |
|----------|-----------|---------|
| `like` | Busca parcial (case-insensitive) | `"João"` → encontra "João Silva", "joao santos", etc |
| `ilike` | Busca parcial (PostgreSQL) | Mesmo que `like` no MySQL |
| `=` | Exatamente igual | `"active"` → encontra apenas "active" |
| `!=` ou `ne` | Diferente de | `"inactive"` → exclui "inactive" |
| `>` | Maior que | `100` → valores > 100 |
| `<` | Menor que | `100` → valores < 100 |
| `>=` | Maior ou igual | `100` → valores >= 100 |
| `<=` | Menor ou igual | `100` → valores <= 100 |
| `in` | Em lista | `["ativo", "pendente"]` → encontra um ou outro |
| `not_in` | Não em lista | `["deletado", "bloqueado"]` → exclui esses |
| `between` | Entre dois valores | `[1, 100]` → valores entre 1 e 100 |
| `null` | Campo nulo | Verifica se é NULL |
| `not_null` | Campo não nulo | Verifica se NÃO é NULL |

---

## 💻 Exemplos Vue.js

### Exemplo 1: Listagem com Filtros Simples

```vue
<template>
    <div>
        <div class="filters">
            <input v-model="filters.name.value" placeholder="Pesquisar por nome..." />
            <button @click="search">Buscar</button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th @click="sort('id')">ID</th>
                    <th @click="sort('name')">Nome</th>
                    <th @click="sort('created_at')">Data</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.created_at }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="pagination">
            <button v-if="currentPage > 1" @click="previousPage">← Anterior</button>
            <span>Página {{ currentPage }} de {{ lastPage }}</span>
            <button v-if="hasMore" @click="nextPage">Próxima →</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { api } from '../../service/api'

const users = ref([])
const meta = ref({})
const currentPage = ref(1)
const lastPage = ref(1)
const hasMore = ref(false)

const filters = ref({
    name: { value: '', operator: 'like' }
})

const sort = ref({
    field: 'id',
    direction: 'asc'
})

const pagination = ref({
    page: 1,
    per_page: 15
})

const search = async () => {
    pagination.value.page = 1 // Reset para página 1
    await loadUsers()
}

const loadUsers = async () => {
    const payload = {
        filters: filters.value,
        sort: sort.value,
        pagination: pagination.value
    }

    const response = await api.post('user', payload)
    users.value = response.data.data
    meta.value = response.data.meta
    currentPage.value = meta.value.current_page
    lastPage.value = meta.value.last_page
    hasMore.value = meta.value.has_more
}

const sort = (field) => {
    if (sort.value.field === field) {
        // Alterna entre asc e desc
        sort.value.direction = sort.value.direction === 'asc' ? 'desc' : 'asc'
    } else {
        sort.value.field = field
        sort.value.direction = 'asc'
    }
    search()
}

const nextPage = () => {
    if (hasMore.value) {
        pagination.value.page++
        loadUsers()
    }
}

const previousPage = () => {
    if (currentPage.value > 1) {
        pagination.value.page--
        loadUsers()
    }
}

onMounted(() => {
    loadUsers()
})
</script>
```

### Exemplo 2: Filtros Avançados

```vue
<script setup>
const filters = ref({
    // Texto - busca parcial
    name: { value: '', operator: 'like' },
    
    // Email - exato
    email: { value: '', operator: '=' },
    
    // Data - intervalo
    created_at_from: { value: '', operator: '>=' },
    created_at_to: { value: '', operator: '<=' },
    
    // Seleção múltipla
    status: { value: [], operator: 'in' },
    
    // Valor numérico
    age: { value: '', operator: '>' }
})

const search = async () => {
    const payload = {
        filters: {
            name: filters.value.name,
            email: filters.value.email,
            created_at: {
                value: [filters.value.created_at_from.value, filters.value.created_at_to.value],
                operator: 'between'
            },
            status: filters.value.status,
            age: filters.value.age
        },
        sort: { field: 'created_at', direction: 'desc' },
        pagination: { page: 1, per_page: 20 }
    }

    const response = await api.post('user', payload)
    users.value = response.data.data
}
</script>
```

### Exemplo 3: Component Genérico Reutilizável

```vue
<!-- FilterForm.vue -->
<template>
    <div class="filter-form">
        <div v-for="(config, key) in filterConfigs" :key="key" class="filter-group">
            <label>{{ config.label }}</label>
            
            <!-- Texto -->
            <input 
                v-if="config.type === 'text'"
                v-model="filters[key].value"
                :placeholder="config.placeholder"
                @keyup.enter="emit('search')"
            />
            
            <!-- Select -->
            <select v-if="config.type === 'select'" v-model="filters[key].value">
                <option value="">{{ config.placeholder }}</option>
                <option v-for="opt in config.options" :key="opt" :value="opt">
                    {{ opt }}
                </option>
            </select>
            
            <!-- Data -->
            <input v-if="config.type === 'date'" v-model="filters[key].value" type="date" />
        </div>
        
        <button @click="emit('search')">🔍 Buscar</button>
        <button @click="clearFilters">❌ Limpar</button>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    config: Object // Configuração dos filtros
})

const emit = defineEmits(['search', 'filters-changed'])

const filters = ref(props.config.filters)

const clearFilters = () => {
    Object.keys(filters.value).forEach(key => {
        if (Array.isArray(filters.value[key].value)) {
            filters.value[key].value = []
        } else {
            filters.value[key].value = ''
        }
    })
    emit('search')
}
</script>
```

---

## 🎯 Casos de Uso Práticos

### Caso 1: Dashboard de Transações

```javascript
const payload = {
    filters: {
        user_id: { value: userId, operator: '=' },
        status: { value: 'pending', operator: '=' },
        amount: { value: [100, 1000], operator: 'between' },
        created_at: { value: '2025-01-01', operator: '>=' }
    },
    sort: { field: 'created_at', direction: 'desc' },
    pagination: { page: 1, per_page: 50 }
}
```

### Caso 2: Busca de Produtos

```javascript
const payload = {
    filters: {
        name: { value: searchText, operator: 'like' },
        category_id: { value: selectedCategories, operator: 'in' },
        price: { value: [minPrice, maxPrice], operator: 'between' },
        in_stock: { value: 1, operator: '=' }
    },
    sort: { field: 'price', direction: 'asc' },
    pagination: { page: 1, per_page: 20 }
}
```

### Caso 3: Relatório com Filtros Complexos

```javascript
const payload = {
    filters: {
        created_at: { value: dateRange, operator: 'between' },
        department_id: { value: departments, operator: 'in' },
        salary: { value: minSalary, operator: '>=' },
        active: { value: 1, operator: '=' },
        name: { value: search, operator: 'like' }
    },
    sort: { field: 'created_at', direction: 'desc' },
    pagination: { page: 1, per_page: 100 }
}
```

---

## ✅ Checklist de Implementação

- [x] Trait `SearchableFilter` criada
- [x] Service base integrado com a trait
- [x] Documentação de padrão de requisição
- [x] Documentação de padrão de resposta
- [x] Exemplos Vue.js
- [x] Todos os operadores suportados

---

## 🚀 Próximos Passos

1. **Criar um Component reutilizável** para filtros dinâmicos no Vue
2. **Adicionar validação** dos filtros no backend
3. **Implementar cache** para filtros frequentes
4. **Adicionar relatórios** com agregação de dados
