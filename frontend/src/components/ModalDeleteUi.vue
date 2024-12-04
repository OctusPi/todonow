<template>
    <TransitionRoot appear :show="deleteModal.isOpen" as="template">
        <Dialog as="div" class="relative z-10">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="box w-full max-w-md transform overflow-hidden rounded-2xl p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-red-400">
                                Confirmação de Exclusão
                            </DialogTitle>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Os dados selecionados serão apagados, sem
                                    possibilidade de restauração. Deseja
                                    continuar?
                                </p>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" class="btn btn-danger mx-2" @click="remove">
                                    <component :is="TrashIcon" class="w-5 h-5 me-1" />
                                    Confirmar
                                </button>
                                <button type="button" class="btn btn-cancel" @click="closeModal">
                                    <component :is="XCircleIcon" class="w-5 h-5 me-1" />
                                    Cancelar
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { reactive, watch } from 'vue';
import http from '@/services/http';

import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue';

import { XCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

const emit = defineEmits(['callAlert', 'callUpdate']);
const model = defineModel({ default: () => { } });

const deleteModal = reactive({
    isOpen: false,
});

function closeModal() {
    model.value.id = null
    deleteModal.isOpen = false;
}

function remove() {
    
    http.get(`${model.value.url}/destroy/${model.value.id}`, emit, resp => {
        closeModal()
        if (http.success(resp)) {
            http.post(`${model.value.url}/list`, model.value.search, emit, resp => {
                if (model.value.onRemove) {
                    model.value.onRemove(resp);
                    return;
                }
                emit('callUpdate', resp.data);
            });
        }
    });
}

watch(() => model.value.id, (n) => {
    console.log(n)
    deleteModal.isOpen = !!n;
});
</script>
