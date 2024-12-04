<template>
  <div class="absolute w-full bg-violet-600 -z-20 back-bg"></div>
  <div class="container mx-auto p-4">
    <nav class="flex justify-between items-center navbar navbar-expand-lg navbar-dark text-white">
      <div class="flex items-center">
        <RouterLink class="flex items-center" to="/dashboard">
          <img src="@/assets/imgs/logo-white.svg" alt="Logo Sistema" class="nav-brand me-2">
          <h1 class="text-sm font-semibold p-0 m-0 hidden md:block">BeautyBox</h1>
        </RouterLink>
        <nav v-if="auth_user" class="items-center ms-4 text-xs space-x-2 hidden md:flex ">
          <Popover>
            <PopoverButton>Ferramentas</PopoverButton>
            <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
              enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
              leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
              <PopoverPanel class="absolute z-10 mt-1 bg-violet-700 py-3 px-4 rounded-md">
                <div class="grid grid-cols-1 space-y-3">
                  <template v-for="m in main_menu" :key="m.module">
                    <RouterLink v-if="auth_user.navigation.find(n => n.module === m.module)" :to="m.to"
                      class="flex items-center p-2 rounded-md hover:bg-violet-900">
                      <component :is="m.icon" class="h-4 w-4 me-1" />
                      {{ m.title }}
                    </RouterLink>
                  </template>
                </div>
              </PopoverPanel>
            </transition>
          </Popover>
          <RouterLink to="/plans">Planos</RouterLink>
          <RouterLink to="/payments">Faturas</RouterLink>
          <RouterLink to="/manual">Manual</RouterLink>
          <RouterLink to="/about">Sobre</RouterLink>
        </nav>
      </div>
      <nav class="flex relative items-center ms-4 text-xs space-x-4">
        <Popover class="md:hidden">
          <PopoverButton>
            <component :is="Bars4Icon" class="h-4 w-4"></component>
          </PopoverButton>
          <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
            <PopoverPanel class="absolute z-10 mt-1 bg-violet-700 py-3 px-4 rounded-md">
              <div class="grid grid-cols-1 space-y-3">
                <template v-for="m in main_menu" :key="m.module">
                  <RouterLink v-if="auth_user.navigation.find(n => n.module === m.module)" :to="m.to"
                    class="flex items-center p-2 rounded-md hover:bg-violet-900">
                    <component :is="m.icon" class="h-4 w-4 me-1" />
                    {{ m.title }}
                  </RouterLink>
                </template>
              </div>
            </PopoverPanel>
          </transition>
        </Popover>
        <RouterLink to="/dashboard">
          <component :is="BellAlertIcon" class="h-4 w-4"></component>
        </RouterLink>
        <RouterLink to="/dashboard">
          <component :is="CalendarIcon" class="h-4 w-4"></component>
        </RouterLink>
        <RouterLink to="/dashboard" class="flex items-center text-xs space-x-2">
          <component :is="MoonIcon" class="h-4 w-4"></component>
          <Switch v-model="dark_mode" @click="apply_theme" :class="dark_mode ? 'bg-violet-900' : 'bg-violet-700'"
            class="relative inline-flex h-[16px] w-[32px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
            <span class="sr-only">Dark Mode</span>
            <span aria-hidden="true" :class="dark_mode ? 'translate-x-4' : 'translate-x-0'"
              class="pointer-events-none inline-block h-[13px] w-[16px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
          </Switch>
        </RouterLink>

        <Popover v-if="auth_user">
          <PopoverButton class="flex items-center rounded-full bg-violet-700 py-2 ps-2 pe-3">
            <component :is="UserCircleIcon" class="h-5 w-5 me-1"></component>
            {{ auth_user.name.split(' ')[0] }}
          </PopoverButton>
          <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
            <PopoverPanel class="absolute right-2 z-10 mt-1 bg-violet-700 p-4 rounded-md">
              <div class="grid grid-cols-1 space-y-1">
                <div class="flex items-center p-2 hover:bg-violet-900 cursor-pointer rounded-md">
                  <div class="p-2 bg-violet-500 me-2 rounded-md">
                    <component :is="IdentificationIcon" class="h-5 w-5"></component>
                  </div>
                  <div>
                    <p class="text-xs">Perfil</p>
                    <p class="text-xs font-semibold">{{ auth_user.profile }}</p>
                  </div>
                </div>
                <div v-if="resc_salon" class="flex items-center p-2 hover:bg-violet-900 cursor-pointer rounded-md">
                  <div class="p-2 bg-violet-500 me-2 rounded-md">
                    <component :is="BuildingOffice2Icon" class="h-5 w-5"></component>
                  </div>
                  <div>
                    <p class="text-xs">Salão</p>
                    <p class="text-xs font-semibold">{{ resc_salon.name }}</p>
                  </div>
                </div>
                <div class="flex items-center p-2 hover:bg-violet-900 cursor-pointer rounded-md">
                  <div class="p-2 bg-violet-500 me-2 rounded-md">
                    <component :is="CalendarDateRangeIcon" class="h-5 w-5"></component>
                  </div>

                  <div>
                    <p class="text-xs">Ultimo Acesso</p>
                    <p class="text-xs font-semibold">{{ auth_user.lastlogin }}</p>
                  </div>
                </div>
                <div @click="logout" class="flex items-center p-2 hover:bg-violet-900 cursor-pointer rounded-md">
                  <div class="p-2 bg-violet-500 me-2 rounded-md">
                    <component :is="ArrowLeftStartOnRectangleIcon" class="h-5 w-5"></component>
                  </div>
                  <div>
                    <p class="text-xs">Logout</p>
                    <p class="text-xs font-semibold">Sair com segurança</p>
                  </div>
                </div>
              </div>
            </PopoverPanel>
          </transition>
        </Popover>


      </nav>
    </nav>

  </div>
</template>

<script setup>
import { ref } from "vue";
import http from "@/services/http.js"
import theme from "@/stores/theme.js";
import auth from "@/stores/auth.js"
import enterprise from "@/stores/enterprise.js"
import { Popover, PopoverButton, PopoverPanel, Switch } from "@headlessui/vue";
import { BellAlertIcon, BuildingOffice2Icon, ArrowLeftStartOnRectangleIcon, BanknotesIcon, IdentificationIcon, CalendarIcon, CalendarDateRangeIcon, RectangleGroupIcon, CubeIcon, MoonIcon, UserCircleIcon, UserGroupIcon, Bars4Icon } from '@heroicons/vue/24/outline';

const emit = defineEmits(['callAlert']);
const auth_user = ref(auth.get_user())
const resc_salon = ref(enterprise.get_enterprise())
const dark_mode = ref(theme.get_theme() === 'dark');

const main_menu = [
  { 'module': 'appointments', title: 'Agenda', icon: CalendarDateRangeIcon, to: '/appointments' },
  { 'module': 'customers', title: 'Clientes', icon: UserGroupIcon, to: '/customers' },
  { 'module': 'services', title: 'Serviços', icon: RectangleGroupIcon, to: '/services' },
  { 'module': 'products', title: 'Produtos', icon: CubeIcon, to: '/products' },
  { 'module': 'employees', title: 'Funcionários', icon: IdentificationIcon, to: '/employees' },
  { 'module': 'cashs', title: 'Livro Caixa', icon: BanknotesIcon, to: '/cashs' },
  { 'module': 'enterprises', title: 'Empresa', icon: BuildingOffice2Icon, to: '/enterprises' },
  { 'module': 'users', title: 'Usuários', icon: UserCircleIcon, to: '/users' },
]

function apply_theme() {
  const mode = dark_mode.value ? "light" : "dark";
  theme.set_theme(mode)
  theme.apply_theme()
}

function logout() {
  http.get('/auth/logout', emit, () => {
    auth.clear();
    enterprise.clear();
    auth_user.value = null
    resc_salon.value = null
    window.location = '/login'
  })
}

</script>

<style scoped>
.back-bg {
  height: 400px;
}

.nav-brand {
  height: 20px;
  width: auto;
}
</style>
