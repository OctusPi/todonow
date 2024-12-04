<template>
    <main class="container-main flex align-middle">
        <div class="flex min-h-full flex-1 flex-col justify-center align-middle px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-16 w-auto" src="@/assets/imgs/logo.svg" alt="Your Company" />
                <h2 class="mt-6 text-center text-2xl/9 font-bold tracking-tight">
                    {{ sysapp.name }}
                </h2>
                <p class="text-sm text-center">
                    {{ sysapp.desc }}
                </p>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

                <div v-if="!page.user">
                    <form class="space-y-6" @submit.prevent="login">
                        <div>
                            <label for="email" class="label-input">E-mail</label>
                            <input id="email" name="email" type="email" autocomplete="email"
                                class="block w-full text-input" v-model="page.data.email"
                                :class="{ 'form-control-alert': page.rules.valids.email }"
                                placeholder="Digite seu e-mail" />
                        </div>
                        <div>
                            <label for="password" class="flex items-center mb-1 justify-between text-xs">
                                <span class="font-normal">Senha</span>
                                <RouterLink to="/recover" class="active-link">Esqueceu sua senha?</RouterLink>
                            </label>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                v-model="page.data.password"
                                :class="{ 'form-control-alert': page.rules.valids.password }"
                                class="block w-full text-input" placeholder="Senha de acesso" />
                        </div>

                        <button type="submit" class="btn btn-action w-full">
                            <component :is="ArrowRightEndOnRectangleIcon" class="w-5 h-5" />
                            Entrar
                        </button>
                    </form>

                    <p class="mt-10 text-center text-sm/6 text-gray-500">
                        Você é novo aqui?
                        <RouterLink to="/newuser" class="active-link">
                            Realize seu Cadastro
                        </RouterLink>
                    </p>
                </div>

                <div v-else class="text-center">
                    <component :is="UserCircleIcon" class="h-16 w-16 mx-auto" />
                    <h2 class="mt-4">{{ page.user.name }}</h2>

                    <div class="flex justify-center mt-10">
                        <button type="button" class="btn btn-cancel mx-2" @click="logout">
                            <span>Sair...</span>
                            <component :is="ArrowLeftEndOnRectangleIcon" class="w-5 h-5 ms-1" />
                        </button>
                        <RouterLink to="/tasks" class="btn btn-action mx-2">
                            <component :is="ArrowRightEndOnRectangleIcon" class="w-5 h-5 me-1" />
                            <span>Partiu!</span>
                        </RouterLink>

                    </div>
                </div>

                <p class="m-0 p-0 mt-4 text-center text-xs text-gray-400">
                    Todos os direitos reservados {{ sysapp.copy }} &copy;
                </p>
            </div>
        </div>
    </main>
</template>

<script setup>
import { inject, reactive, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import { ArrowLeftEndOnRectangleIcon, ArrowRightEndOnRectangleIcon, UserCircleIcon } from '@heroicons/vue/24/outline';
import auth from '@/stores/auth';
import http from '@/services/http';
import forms from '@/services/forms';
import notifys from '@/utils/notifys';

const sysapp = inject('sysapp');
const emit = defineEmits(['callAlert']);
const page = reactive({
    user: auth.get_user(),
    url: '/auth',
    data: {},
    rules: {
        fields: {
            email: 'required|email',
            password: 'required',
        },
        valids: {},
    },
});

function login() {
    const validation = forms.checkform(page.data, page.rules);
    if (!validation.isvalid) {
        emit('callAlert', notifys.warning(validation.message));
        return;
    }

    http.post(page.url, page.data, emit, response => {
        const user = response.data.user;
        auth.set_user(user);
        window.location = '/tasks';
    });
}

function islogged() {
    if (page.user) {
        http.get(`${page.url}/check`, emit, null, response => {
            if (response.status === 200) {
                return;
            }
            logout();
        });
    }
}

function logout() {
    http.get(`${page.url}/logout`, emit)
    auth.clear();
    page.user = null;
}

onMounted(() => {
    islogged();
});
</script>
