<script setup>
import { onMounted, ref } from 'vue';
import { RouterView } from 'vue-router';
import theme from '@/stores/theme';

import AlertUi from './components/AlertUi.vue';
import ModalDeleteUi from './components/ModalDeleteUi.vue';

const alert = ref({ show: false, data: {} });
const list = ref([]);
const erase = ref({id:null, url: null});

onMounted(() => {
  theme.apply_theme();
});
</script>

<template>

  <AlertUi :alert="alert" />

  <div class="absolute inset-x-0 top-0 -z-10 transform-gpu overflow-hidden blur-3xl sm:top-10" aria-hidden="true">
    <div
      class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#8ff0ff] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
      style="
				clip-path: polygon(
					74.1% 44.1%,
					100% 61.6%,
					97.5% 26.9%,
					85.5% 0.1%,
					80.7% 2%,
					72.5% 32.5%,
					60.2% 62.4%,
					52.4% 68.1%,
					47.5% 58.3%,
					45.2% 34.5%,
					27.5% 76.7%,
					0.1% 64.9%,
					17.9% 100%,
					27.6% 76.8%,
					76.1% 97.7%,
					74.1% 44.1%
				);
			" />
  </div>

  <div id="load-wall" class="load-wall d-none">
    <img id="load-img" class="load-img rotate" src="./assets/imgs/load.svg" />
  </div>

  <RouterView :datalist="list"
  @callAlert="data => { alert = data; }"
  @callRemove="data => { erase = data; }" />

  <ModalDeleteUi v-model="erase"
  @callUpdate="data => { list = data; } "
  @callAlert="data => { alert = data; }" />


</template>