<template>
    <div class="">
        <Button label="Salvar" color="#16a34a" />
        <Button label="Excluir" color="#dc2626"  disabled />
        <Button label="Excluir" color="#0d3ecf" />
    </div>
</template>

<script setup>
import Button from '../components/utils/Button.vue'

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
