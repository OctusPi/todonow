<template>
	<TransitionRoot appear :show="alert.show" as="template">
		<Dialog as="div" @close="closeAlert" class="relative z-20">
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
							class="box w-full max-w-sm transform overflow-hidden rounded-2xl p-6 text-left align-middle shadow-xl transition-all">


							<DialogTitle as="h3" class="text-md text-center font-medium leading-6"
								:class="alerts[alert.data.type]?.style">
                <component
					:is="alerts[alert.data.type]?.icon"
					class="h-8 w-8 mx-auto"
				/>
								{{ alerts[alert.data.type]?.title }}
							</DialogTitle>

							<div class="mt-2 text-center">
								<p class="p-0 m-0 text-xs">{{ alerts[alert.data.type]?.msg }}</p>
								<p class="p-0 m-0 text-xs">{{ alert?.data.msg }}</p>
							</div>

							<div class="mt-6 flex justify-center">
								<button @click="closeAlert" type="button" class="btn btn-alert shadow-sm mx-auto mt-4"
									:class="alerts[alert.data.type]?.btn">
									<component :is="CheckIcon" class="h-5 w-5"></component>
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
import { ref, watch } from 'vue';
import {
	TransitionRoot,
	TransitionChild,
	Dialog,
	DialogPanel,
	DialogTitle,
} from '@headlessui/vue';
import {
	ExclamationTriangleIcon,
	CheckCircleIcon,
	BugAntIcon,
	InformationCircleIcon,
	CheckIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({ alert: { type: Object, required: true } });
const alert = ref(props.alert);

const alerts = {
	success: {
		style: 'alert-success',
		icon: CheckCircleIcon,
		title: 'Sucesso!',
		msg: 'Operação realizada com sucesso!',
		btn: 'btn-alert-success',
	},
	warning: {
		style: 'alert-warning',
		icon: ExclamationTriangleIcon,
		title: 'Alerta!',
		msg: 'Falha ao realizar solicitação!',
		btn: 'btn-alert-warning',
	},
	danger: {
		style: 'alert-danger',
		icon: BugAntIcon,
		title: 'Falha!',
		msg: 'Algo deu errado, verifique e tente novamente!',
		btn: 'btn-alert-danger',
	},
	info: {
		style: 'alert-info',
		icon: InformationCircleIcon,
		title: 'Informe!',
		msg: '',
		btn: 'btn-alert-info',
	},
};

function closeAlert() {
	alert.value.show = false;

}

watch(
	() => props.alert,
	newValue => {
		alert.value = newValue;
	}
);
</script>

<style scoped>
.wall {
	background-color: var(--load-color);
	width: 100%;
	height: 100vh;
	position: fixed;
	top: 0;
	left: 0;
	transition: 0.5s;
}

.box-alert {
	width: 300px;
	min-height: 120px;
	border-radius: 20px;
	position: absolute;
	top: calc(50% - 80px);
	left: calc(50% - 150px);
	background-color: var(--background-secondary-color);
}

.efect-down * {
	display: none;
}

.efect-down {
	opacity: 0;
	transition: 0.1s;
}

.alert-success {
	color: var(--success-color);
}

.alert-warning {
	color: var(--warning-color);
}

.alert-danger {
	color: var(--error-color);
}

.alert-info {
	color: var(--info-color);
}

.btn-alert {
	border-radius: 50%;
	padding: 8px !important;
	display: flex;
	align-items: center;
	justify-content: center;
}

.btn-alert-success {
	background-color: var(--success-color);
	color: var(--success-color-hover);
}

.btn-alert-warning {
	background-color: var(--warning-color);
	color: var(--warning-color-hover);
}

.btn-alert-danger {
	background-color: var(--error-color);
	color: var(--error-color-hover);
}

.btn-alert-info {
	background-color: var(--info-color);
	color: var(--info-color-hover);
}
</style>
