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


                    <form class="space-y-6" @submit.prevent="register">
                        <div>
                            <label for="name" class="label-input">Nome</label>
                            <input id="name" name="name" type="name" autocomplete="off"
                                class="block w-full text-input" v-model="page.data.name"
                                :class="{ 'form-control-alert': page.rules.valids.name }" 
                                placeholder="Digite seu Nome" />
                        </div>
                        <div>
                            <label for="email" class="label-input">E-mail</label>
                            <input id="email" name="email" type="email" autocomplete="off"
                                class="block w-full text-input" v-model="page.data.email"
                                :class="{ 'form-control-alert': page.rules.valids.email }"
                                placeholder="Digite seu e-mail" />
                        </div>
                        <div>
                            <label for="password" class="label-input">Senha</label>
                            <input id="password" name="password" type="password" autocomplete="off"
                                v-model="page.data.password"
                                :class="{ 'form-control-alert': page.rules.valids.password }"
                                class="block w-full text-input" placeholder="Senha de acesso" />
                        </div>
                        <div>
                            <label for="passdoublecheck" class="label-input">Confirmação de Senha</label>
                            <input id="passdoublecheck" name="passdoublecheck" type="password" autocomplete="off"
                                v-model="page.data.passdoublecheck"
                                :class="{ 'form-control-alert': page.rules.valids.passdoublecheck }"
                                class="block w-full text-input" placeholder="Confirmação de Senha" />
                        </div>

                        <p class="text-red-400 text-xs text-center"> Sua senha deve ter pelo menos 08 caracteres com letra maiúsculas e minúsculas, número e símbolos.</p>

                        <button type="submit" class="btn btn-action w-full">
                            <component :is="ArrowRightEndOnRectangleIcon" class="w-5 h-5" />
                            Realizar Cadastro
                        </button>
                    </form>

                    <p class="mt-10 text-center text-sm/6 text-gray-500">
                        Já tem cadastro?
                        <RouterLink to="/" class="active-link">
                            Faça o login
                        </RouterLink>
                    </p>



                <p class="m-0 p-0 mt-4 text-center text-xs text-gray-400">
                    Todos os direitos reservados {{ sysapp.copy }} &copy;
                </p>
            </div>
        </div>
    </main>
</template>

<script setup>
import { inject, reactive } from 'vue';
import { RouterLink } from 'vue-router';
import { ArrowRightEndOnRectangleIcon } from '@heroicons/vue/24/outline';
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
            name: 'required',
            email: 'required|email',
            password: 'required|password',
            passdoublecheck: 'required|password',
        },
        valids: {},
    },
});

function register() {
    const validation = forms.checkform(page.data, page.rules);
    if (!validation.isvalid) {
        emit('callAlert', notifys.warning(validation.message));
        return;
    }

    if (page.data.password !== page.data.passdoublecheck) {
        emit('callAlert', notifys.warning('Senhas não são iguais'));
        return;
    }

    http.post(`${page.url}/newuser`, page.data, emit, () => {
        console.log('entrou')

        login()
        
    });
}

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

</script>
