<template>
    <div class="grid-wrapper ag-theme-quartz">
        <AgGridVue class="grid" theme="legacy" :rowData="rowData" :columnDefs="columnDefs" :columnTypes="columnTypes" :defaultColDef="defaultColDef" :localeText="localeText" pagination :paginationAutoPageSize="true" :rowSelection="rowSelection" @grid-ready="onGridReady" @selection-changed="onSelectionChanged" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { AgGridVue } from 'ag-grid-vue3'
import type { ColDef, ColGroupDef, GridApi, LocaleText } from 'ag-grid-community'
import { ModuleRegistry, ClientSideRowModelModule, CellStyleModule, PaginationModule, RowSelectionModule, TextFilterModule, NumberFilterModule, DateFilterModule, LocaleModule, ValidationModule } from 'ag-grid-community'

/* ===============================
   REGISTRO DOS MÓDULOS
================================ */
ModuleRegistry.registerModules([ClientSideRowModelModule, CellStyleModule, PaginationModule, RowSelectionModule, TextFilterModule, NumberFilterModule, DateFilterModule, LocaleModule, ValidationModule])

/* ===============================
   PROPS (TIPADAS DE VERDADE)
================================ */
const props = defineProps<{
    rowData: any[]
    columnDefs: (ColDef | ColGroupDef)[]
    defaultColDef?: ColDef
}>()
/* ===============================
   EMITS
================================ */
const emit = defineEmits<{
    (e: 'update:selection', value: any[]): void
}>()

/* ===============================
   GRID API
================================ */
const gridApi = ref<GridApi | null>(null)

const onGridReady = (params: { api: GridApi }) => {
    gridApi.value = params.api
}

const onSelectionChanged = () => {
    if (!gridApi.value) return

    const selectedData = gridApi.value.getSelectedNodes().map((node) => node.data)

    emit('update:selection', selectedData)
}

/* ===============================
   CONFIGS
================================ */
const rowSelection: any = {
    mode: 'multiRow',
    checkboxes: true,
    headerCheckbox: true,
}

const defaultColDef: ColDef = {
    sortable: true,
    filter: true,
    floatingFilter: false,
    resizable: true,
}

const localeText: LocaleText = {
    page: 'Página',
    more: 'Mais',
    to: 'até',
    of: 'de',
    next: 'Próxima',
    last: 'Última',
    first: 'Primeira',
    previous: 'Anterior',
    loadingOoo: 'Carregando...',
    selectAll: 'Selecionar tudo',
    searchOoo: 'Pesquisar...',
    blanks: 'Em branco',

    filterOoo: 'Filtrar...',
    equals: 'Igual',
    notEqual: 'Diferente',
    lessThan: 'Menor que',
    greaterThan: 'Maior que',
    contains: 'Contém',
    notContains: 'Não contém',
    startsWith: 'Começa com',
    endsWith: 'Termina com',
    blank: 'Vazio',
    notBlank: 'Não Vazio',
    after: 'Depois',
    before: 'Antes',
    true: 'Verdadeiro',
    between: 'Entre',
    false: 'Falso',
    applyFilter: 'Aplicar filtro',
    clearFilter: 'Limpar filtro',
}

const columnTypes = {
    date: {
        filter: 'agDateColumnFilter',
        valueFormatter: (params: any) => {
            if (!params.value) return ''

            const date = new Date(params.value)

            return date.toLocaleDateString('pt-BR')
        },
    },

    datetime: {
        filter: 'agDateColumnFilter',
        valueFormatter: (params: any) => {
            if (!params.value) return ''

            const date = new Date(params.value)

            return date.toLocaleString('pt-BR')
        },
    },

    money: {
        filter: 'agNumberColumnFilter',
        valueFormatter: (params: any) => {
            if (!params.value) return ''

            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(params.value)
        },
    },
}
</script>

<style lang="scss">
.grid-wrapper {
    width: 100%;
    height: 80vh;

    .grid {
        width: 100%;
        height: 100%;
        // Types
        .cell-center {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .cell-right {
            display: flex;
            justify-content: end;
            align-items: end;
            text-align: right;
        }
        .cell-left {
            display: flex;
            justify-content: start;
            align-items: start;
            text-align: left;
        }

        @media (max-width: 768px) {
            .ag-paging-row-summary-panel,
            .ag-paging-page-size {
                display: none;
            }

            .ag-paging-page-summary-panel {
                display: flex;
                width: 100%;
                justify-content: space-between;
            }
        }
    }
}
</style>
