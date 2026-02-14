<template>
    <div class="">
        alguma cosia
        <Button href="https://www.youtube.com" label="youtoba"/>
        <Button to="alguma_dcoisa" label="youtoba"/>
        <Button label="previous" @click="show('')"/>
            
        
    </div>
</template>

<script setup>
    import Button from '../components/utils/Button.vue';

    const show = (text) => {
        alert(`ola ${text}`);

    };
</script>

<style lang="scss">
    .test {
        background-color: red;

        &:hover {
            background-color: aquamarine;
        }
    }
</style>

