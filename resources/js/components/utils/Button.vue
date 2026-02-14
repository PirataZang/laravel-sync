<template>
    <a :href="href" v-if="href" target="_blank" class="button-style">
        <span v-html="label">

        </span>
    </a>
    <router-link :to="to" v-else-if="to" target="_blank" class="button-style">
        <span v-html="label">

        </span>
    </router-link>
    <button v-else class="button-style">
        <span v-html="label">

        </span>
    </button>
</template>

<style lang="scss" scoped>
.button-style {
  padding: 10px 18px;
  margin: 4px;
  background: #0d3ecf;
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;

  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;

  font-size: 14px;
  font-weight: 500;
  text-decoration: none;

  box-shadow: 0 6px 16px rgba(13, 62, 207, 0.35);
  transition: 
    background 0.3s ease,
    transform 0.15s ease,
    box-shadow 0.15s ease;

  &:hover {
    background: linear-gradient(135deg, #dd155b, #ec4899);
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(221, 21, 91, 0.4);
  }

  &:active {
    transform: translateY(0);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
  }

  &:focus-visible {
    outline: 3px solid rgba(59, 130, 246, 0.5);
    outline-offset: 2px;
  }

  &:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }
}
</style>


<script setup>

const props = defineProps({
    label: {
        type: String,
        required: true,
        default: 'Botão'
    },
    to: {
        type: String,
        default: null
    },
    href: {
        type: String,
        default: null
    }
})

</script>
