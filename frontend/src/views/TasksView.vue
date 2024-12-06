<template>

  <TransitionRoot appear :show="page.ui.register" as="template">
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
              class="box w-full max-w-lg transform overflow-hidden rounded-2xl p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="div" class="flex items-center">
                <div class="p-2.5 me-2 hidden md:block text-sky-700 bg-sky-200 rounded-md">
                  <component :is="DocumentTextIcon" class="h-5 w-5" />
                </div>
                <div class="text-start">
                  <h2 class="text-lg font-semibold leading-6 text-sky-400">
                    Registro de Tarefa
                  </h2>
                  <p class="text-xs text-gray-500 p-0 m-0">
                    Para registrar uma tarefa informe seu título e adicione uma descrição.
                  </p>
                </div>

              </DialogTitle>

              <div class="mt-6">
                <form @submit.prevent="action.save(null, () => { page.ui.register = false })">
                  <div class="grid grid-cols-6 gap-4">
                    <div class="col-span-6">
                      <label for="title" class="label-input">Título da Tarefa</label>
                      <input id="title" name="title" type="text" v-model="page.data.title"
                        :class="{ 'form-control-alert': page.valids.title }" class="text-input w-full"
                        placeholder="Fazer Compras" />
                    </div>
                    <div class="col-span-6">
                      <label for="description" class="label-input">Descrição da Tarefa</label>
                      <textarea id="description" name="description" v-model="page.data.description" rows="5"
                        class="text-input w-full"
                        placeholder="Lembrar de comprar leite e pão quando for ao mercado"></textarea>
                    </div>

                  </div>
                  <div class="mt-6 flex items-center justify-end space-x-2">
                    <button type="submit" class="btn btn-action">
                      <component :is="CheckIcon" class="h-5 w-5 me-1" />
                      Salvar
                    </button>
                    <button type="button" class="btn btn-cancel" @click="action.ui('list')">
                      <component :is="XMarkIcon" class="h-5 w-5 me-1" />
                      Cancelar
                    </button>
                  </div>
                </form>
              </div>

            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <HeaderMain />

  <main class="container flex mx-auto px-4 py-4 content-main">
    <section class="section-main">
      <!-- Header Bar -->
      <div class="header-bar md:flex items-center justify-between mb-6">
        <header>
          <h1 class="text-xl font-semibold m-0 p-0">Lista de Tarefas</h1>
          <p class="m-0 p-0 text-xs">Adicione tarefas ou verifique suas tarefas pendentes e realizadas</p>
        </header>
        <div class="header-bar-action-btns flex items-center justify-end space-x-2 mt-2 md:mt-0">
          <button type="button" class="btn btn-tab-action" @click="action.ui('register')">
            <component :is="PlusCircleIcon" class="h-4 w-4 me-1" />
            Adicionar
          </button>
          <button type="button" class="btn btn-tab-action" @click="action.ui('search')">
            <component :is="MagnifyingGlassIcon" class="h-4 w-4 me-1" />
            Localizar
          </button>
        </div>
      </div>

      <!-- Content Box -->
      <div class="content-box box">

        <!-- Search Box -->
        <div v-if="page.ui.search" id="content-search" class="mb-6">
          <header class="flex items-center justify-between mb-8">
            <div class="p-1 text-sky-700 bg-sky-300 rounded-md">
              <component :is="MagnifyingGlassIcon" class="h-5 w-5" />
            </div>
            <div class="text-end">
              <h2 class="text-sm m-0 p-0 font-semibold">Localizar</h2>
              <p class="text-xs m-0 p-0 font-light">Aplique o filtro para localizar suas tarefas</p>
            </div>
          </header>

          <form @submit.prevent="action.list">
            <div class="grid grid-cols-6 gap-4">

              <div class="col-span-6 md:col-span-2">
                <label for="title" class="label-input">Titulo</label>
                <input id="title" name="title" type="text" v-model="page.search.title" class="text-input w-full"
                  placeholder="Busque pelo titulo da tarefa" />
              </div>
              <div class="col-span-6 md:col-span-2">
                <label for="description" class="label-input">Descrição</label>
                <input id="description" name="description" type="text" v-model="page.search.description"
                  class="text-input w-full" placeholder="Busque pela descrição da tarefa" />
              </div>
              <div class="col-span-6 md:col-span-2">
                <label for="status" class="label-input">Status</label>
                <select id="status" name="status" v-model="page.search.status" class="text-input w-full">
                  <option></option>
                  <option v-for="(i, j) in page.selects.status" :key="j" :value="i">{{ i }}</option>
                </select>
              </div>
            </div>
            <div class="mt-6 flex items-center justify-end space-x-2">
              <button type="submit" class="btn btn-action">
                <component :is="CheckIcon" class="h-5 w-5 me-1" />
                Aplicar
              </button>
            </div>
          </form>
        </div>

        <!-- List Box -->
        <div id="content-list">
        
          <TableList :header="page.headers" :body="page.datalist" identify="list_tasks" :mounts="{
            description: [Mounts.Truncate(100)],
            status: [Mounts.Status()]
          }" :actions="[
            Actions.Edit(action.update),
            Actions.Delete(action.remove)
          ]" />

          <PaginatorUi v-model="page.datalist" :search="page.search" :url="page.url" class="mt-4"/>
        </div>


      </div>
    </section>
  </main>

  <FooterMain />
</template>

<script setup>
import { onMounted, watch } from "vue";
import Layout from "@/services/layout";
import Actions from "@/services/actions";
import HeaderMain from "@/components/HeaderMain.vue";
import FooterMain from "@/components/FooterMain.vue";
import TableList from "@/components/TableList.vue";
import Mounts from "@/services/mounts";
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import { CheckIcon, DocumentTextIcon, MagnifyingGlassIcon, PlusCircleIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import http from "@/services/http";
import PaginatorUi from "@/components/PaginatorUi.vue";

const emit = defineEmits(['callAlert', 'callRemove'])
const props = defineProps({ datalist: { type: Object, default: () => { } } })
const [page, action] = Layout.new(emit, {
  url: '/tasks',
  data: {},
  search: { sent: false },
  datalist: props.datalist,
  headers: [
    { title: '', check: 'status', check_value: 'Finalizada', action: updateTask, isCheck: true, noOrder: true },
    { title: 'TAREFA', key: 'title', sub: [{ key: 'description' }] },
    { title: 'STATUS', key: 'status' },
  ],
  selects: {
    status: ['Pendente', 'Finalizada']
  },
  rules: {
    title: 'required'
  }
})

function updateTask(task) {
  task.status = task.status == 'Finalizada' ? 'Pendente' : 'Finalizada'
  http.post(`${page.url}/save`, task, emit)
}

watch(() => props.datalist, (newdata) => {
  page.datalist = newdata
})

onMounted(() => {
  action.list()
})
</script>