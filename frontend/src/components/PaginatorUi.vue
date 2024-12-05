<template>
    <div v-if="model?.data">
        <div class="flex items-center justify-center space-x-2 py-4">
            <!-- Botão de retroceder -->
            <button :disabled="model.current_page == 1" @click="navigation(model.current_page - 1)">
                <component :is="ChevronLeftIcon" class="text-sky-500 h-4 w-4"></component>
            </button>

            <!-- Números das páginas -->
            <button v-for="i in model.last_page" :key="i" @click="navigation(i)" aria-current="page">
                <component v-if="model.current_page === i" :is="EyeIcon" class="text-sky-500 h-4 w-4"></component>
                <component v-else :is="StopIcon" class="text-sky-500 h-3 w-3"></component>
            </button>

            <!-- Botão de avançar -->
            <button :disabled="model.current_page == model.last_page" @click="navigation(model.current_page + 1)">
                <component :is="ChevronRightIcon" class="text-sky-500 h-4 w-4"></component>
            </button>
        </div>
    </div>
</template>


<script setup>

import { reactive, watch } from 'vue';
import { EyeIcon, StopIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';
import http from '@/services/http';

const props = defineProps({
    url: { type: String, default: '' },
    search: {type: Object, default: () => { }}
})

const model = defineModel({type: Object})

const paginator = reactive({
    search: props.search
})

function navigation(page) {
    http.post(`${props.url}/list/${page}`, paginator.search, null, (resp) => {
        model.value = resp.data
    })
}

watch(() => props.search, (newdata) => {
    paginator.search = newdata
})
</script>
