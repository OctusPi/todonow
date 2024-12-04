<template>
  <main class="container-main flex align-middle">
        <div class="flex min-h-full flex-1 flex-col justify-center align-middle px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-16 w-auto" src="@/assets/imgs/logo.svg" alt="Your Company" />
                <h2 class="mt-6 text-center text-2xl/9 font-bold tracking-tight">
                    Destino não Encontrado
                </h2>
                <p class="text-sm text-center">
                    Parece que você se perdeu, que tal voltar a civilização? <RouterLink to="/" class="active-link">Acessa {{ sysapp.name }}</RouterLink>
                </p>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

                <img src="@/assets/imgs/notfound.svg" class="img-ilt" alt="imagem ilustrativa">

                <p class="m-0 p-0 mt-4 text-center text-xs text-gray-400">
                    Todos os direitos reservados {{ sysapp.copy }} &copy;
                </p>
            </div>
        </div>
    </main>
</template>

<script setup>
import { inject } from 'vue';
const sysapp = inject('sysapp')
</script>

<style scoped>
.img-ilt{
  width: 280px;
  height: auto;
  margin: 0 auto;
}
</style>
