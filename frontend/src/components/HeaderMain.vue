<template>
  
  <div class="container mx-auto p-4">
    <nav class="flex justify-between items-center navbar navbar-expand-lg navbar-dark">
      <div class="flex items-center">
        <RouterLink class="flex items-center" to="/dashboard">
          <img src="@/assets/imgs/logo-white.svg" alt="Logo Sistema" class="nav-brand me-2">
          <h1 class="text-sm font-semibold p-0 m-0 hidden md:block">To Do List</h1>
        </RouterLink>
        <nav v-if="auth_user" class="items-center ms-4 text-xs space-x-2 hidden md:flex ">
          <RouterLink to="/plans">Planos</RouterLink>
          <RouterLink to="/manual">Manual</RouterLink>
          <RouterLink to="/about">Sobre</RouterLink>
        </nav>
      </div>
      <nav class="flex relative items-center ms-4 text-xs space-x-4">
        
        <div class="flex items-center text-xs space-x-2">
          <component v-if="dark_mode" :is="MoonIcon" class="h-4 w-4"></component>
          <component v-else :is="SunIcon" class="h-5 w-5"></component>
          <Switch v-model="dark_mode" @click="apply_theme" :class="dark_mode ? 'bg-sky-500' : 'bg-slate-400'"
            class="relative inline-flex h-[16px] w-[32px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">
            <span class="sr-only">Dark Mode</span>
            <span aria-hidden="true" :class="dark_mode ? 'translate-x-4' : 'translate-x-0'"
              class="pointer-events-none inline-block h-[13px] w-[16px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
          </Switch>
        </div>

        <Popover v-if="auth_user">
          <PopoverButton class="flex items-center rounded-full bg-sky-500 py-2 ps-2 pe-3 text-white">
            <component :is="UserCircleIcon" class="h-5 w-5 me-1"></component>
            {{ auth_user.name.split(' ')[0] }}
          </PopoverButton>
          <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
            <PopoverPanel class="absolute right-2 z-10 mt-1 bg-sky-500 p-4 rounded-md w-full">
              <div class="grid grid-cols-1 space-y-1">
                
                <div @click="logout" class="flex items-center p-2 hover:bg-sky-700 cursor-pointer rounded-md text-white">
                  <div class="p-2 bg-sky-700 me-2 rounded-md">
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
import { Popover, PopoverButton, PopoverPanel, Switch } from "@headlessui/vue";
import { ArrowLeftStartOnRectangleIcon, MoonIcon, SunIcon, UserCircleIcon } from '@heroicons/vue/24/outline';

const emit = defineEmits(['callAlert']);
const auth_user = ref(auth.get_user())
const dark_mode = ref(theme.get_theme() === 'dark');


function apply_theme() {
  const mode = dark_mode.value ? "light" : "dark";
  theme.set_theme(mode)
  theme.apply_theme()
}

function logout() {
  http.get('/auth/logout', emit, () => {
    auth.clear();
    auth_user.value = null
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
