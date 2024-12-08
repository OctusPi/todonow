<template>
	<div v-if="sent" class="tablelist">
		<div v-if="body?.data && body?.data.length" class="relative">
			<table>
				<thead>
					<tr>
						<th v-for="(item, i) in props.header" :key="i"
							@click="(e) => props.order && orderBy(e, item.key)">
							<div class="flex items-center gap-1 text-xs font-bold cursor-pointer">
								{{ item.title }}
								<component v-if="props.order && !item?.noOrder" :is="ArrowsUpDownIcon"
									class="h-3 w-3 ms-1 hover:text-sky-400"></component>
							</div>
						</th>
						<th v-if="props.actions"></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(instance, i) in userBody?.data" :key="i" :class="[$slots.select && 'cursor-pointer']"
						class="even:bg-slate-400 even:bg-opacity-15 hover:bg-slate-500 hover:bg-opacity-15">

						<td v-for="(mounted, j) in applyMounters(instance, userHeader)" :key="j" class="align-middle">
							<div>
								<span v-if="userHeader[j].key || userHeader[j].isCheck"
									class="text-xs txt-color font-medium" :class="mounted.classes"
									:title="getTitle(mounted)">
									<template v-if="!userHeader[j].isCheck">
										{{ getValue(mounted.value, userHeader[j]) }}
									</template>
									<template v-else>
										<input type="checkbox" :name="`${identify}_${i}`"
											@change="userHeader[j].action(instance)" class="me-6 md:me-3"
											:checked="instance[userHeader[j].check] == userHeader[j].check_value">
									</template>
								</span>
							</div>
							<div>
								<span v-for="(submounted, k) in applyMounters(instance, userHeader[j].sub ?? [])"
									:key="k" class="inline-block text-xs txt-color-sec me-1"
									:title="getTitle(submounted)" :class="submounted.classes">
									{{ userHeader[j].sub[k].title }}
									{{ getValue(submounted.value, userHeader[j].sub[k]) }}
								</span>
							</div>
						</td>
						<td v-if="props.actions && !props.selectable" class="align-middle text-end">
							<Popover>
								<PopoverButton class="focus-visible:outline-0 text-end">
									<component :is="EllipsisVerticalIcon" class="h-5 w-5 me-1"></component>
								</PopoverButton>
								<transition enter-active-class="transition duration-200 ease-out"
									enter-from-class="translate-y-1 opacity-0"
									enter-to-class="translate-y-0 opacity-100"
									leave-active-class="transition duration-150 ease-in"
									leave-from-class="translate-y-0 opacity-100"
									leave-to-class="translate-y-1 opacity-0">
									<PopoverPanel
										class="absolute right-2 z-10 mt-1 p-4 w-auto rounded-md shadow-sm drop-menu">
										<ul class="grid grid-cols-1 space-y-0">
											<li v-for="(item, j) in props.actions" :key="j"
												class="p-2 flex items-center cursor-pointer text-xs rounded-md drop-menu-item"
												href="#" @click.prevent="item.action && item.action(instance.id)"
												:data-bs-target="item.modal"
												:data-bs-toggle="item.modal ? 'modal' : null">
												<component :is="item.icon" class="h-4 w-4 me-1" />
												{{ item.title }}
											</li>
										</ul>
									</PopoverPanel>
								</transition>
							</Popover>

						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div v-else class="text-center p-4 txt-color-sec">
			<component :is="CubeTransparentIcon" class="w-8 h-8 mx-auto" />
			<p class="p-0 m-0 mt-4 text-xs">Não foram localizados registros...</p>
		</div>
	</div>

	<div v-else class="text-center txt-color-sec p-4">
		<component :is="ChatBubbleLeftEllipsisIcon" class="w-8 h-8 mx-auto" />
		<p class="p-0 m-0 mt-4 text-xs">Aplique o filtro na opção localizar, para visualizar os dados...</p>
	</div>
</template>

<script setup>
import { ref, watch } from "vue"
import Mounts from "@/services/mounts"
import { ArrowsUpDownIcon, ChatBubbleLeftEllipsisIcon, CubeTransparentIcon, EllipsisVerticalIcon } from "@heroicons/vue/24/outline";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";

const props = defineProps({
	identify: { type: String },
	sent: { type: Boolean, default: true },
	body: { type: Object, default: () => { } },
	header: { type: Array, default: () => [] },
	actions: { type: Array },
	virtual: { type: Object, default: () => ({}) },
	mounts: { type: Object },
	order: { type: Boolean, default: true },
})

const userBody = ref(props.body)
const userHeader = ref(props.header)

function multiplexer(instance, key) {
	if (key.includes('.')) {
		let value = instance
		key.split('.').forEach((subk) => {
			if (Array.isArray(value)) {
				value = value.map((item) => item[subk]).join(',')
			} else {
				value = value[subk] ?? ''
			}
		})
		return value
	}

	return instance[key]
}

function orderBy(e, key) {
	if (e.target.dataset.asc) {
		e.target.removeAttribute('data-asc')
		userBody.value?.data.sort((a, b) => {
			return a[key] < b[key] ? -1 : a[key] > b[key] ? 1 : 0
		})
	} else {
		e.target.dataset.asc = true
		userBody.value?.data.sort((a, b) => {
			return a[key] < b[key] ? 1 : a[key] > b[key] ? -1 : 0
		})
	}
}

function applyMounters(instance, header) {
	const mInstance = { ...instance, _virtual: {} }

	Object.keys(props.virtual).forEach((key) => {
		mInstance._virtual[key] = props.virtual[key](instance)
	})

	return header.map((attr) => {
		if (!attr.key) {
			return { value: null, classes: [] }
		}

		const initial = multiplexer(mInstance, attr.key)
		const title = Mounts.StripHTML()(initial)

		if (props.mounts && props.mounts[attr.key]) {
			return props.mounts[attr.key].reduce((prev, current) => {
				const { value, classes } = current(
					prev.value,
					mInstance
				)
				prev.classes.push(...classes)
				if (value != null) {
					prev.value = value
				}
				return prev
			}, { title: title.value, value: initial, classes: [] })
		}
		return { title: title.value, value: initial, classes: [] }
	})
}

function getTitle(item) {
	return item.classes.includes('has-title') ? item.title : null
}

function getValue(value, header) {
	return value && value != '***'
		? value
		: header.err || '***'
}

watch(() => props.body, (newval) => {
	userBody.value = newval
})

</script>


<style scoped>
table {
	width: calc(100% + 3rem);
	margin-left: -1.5rem;
}

table thead th {
	padding-top: 1rem;
	padding-bottom: 1rem;
}

table tbody td {
	padding-top: 0.5rem;
	padding-bottom: 0.5rem;
}


table tbody tr:last-child {
	border-radius: 15px;
}

table tbody tr:last-child td:last-child {
	border-radius: 0 0 15px;
}

table tbody tr:last-child td:first-child {
	border-radius: 0 0 0 15px;
}

table tr th:first-child {
	padding-left: 2vw;
}

table tr td:first-child {
	padding-left: 2vw;
}

table tr td:last-child {
	padding-right: 2vw;
}

.drop-menu {
	background-color: var(--background-secondary-color);
}

.drop-menu-item:hover {
	background-color: var(--background-color)
}
</style>
