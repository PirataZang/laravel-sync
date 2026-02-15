<template>
    <div class="">
        <Button label="Salvar" color="#16a34a" />
        <Button label="Excluir" color="#dc2626"  disabled />
        <Button label="Excluir" color="#0d3ecf" />

        <ChartsBase width="350px" type="line" label="Vendas" :labels="['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun']" :data="[45, 67, 52, 78, 89, 64]" borderColor="rgb(59, 130, 246)" :backgroundColor="'rgba(59, 130, 246, 0.1)'" />
        <ChartsBase width="350px" type="pie" label="Percentual" :labels="['Eletrônicos', 'Roupas', 'Alimentos', 'Outros']" :data="[30, 25, 20, 25]" border-color="white" :backgroundColor="['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(255, 206, 86)', 'rgba(75, 192, 192)']" />
    </div>
</template>

<script setup>
import Button from '../components/utils/Button.vue'
import ChartsBase from '../components/utils/ChartsBase.vue';

const show = (text) => {
    alert(`ola ${text}`)
}
</script>

<style lang="scss">
.test {
    background-color: red;

    &:hover {
        background-color: aquamarine;
    }
}
</style>
