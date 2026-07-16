<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { index as tarefasIndex, show as tarefaShow, store as storeTarefa } from '@/routes/tarefas';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Tarefas', href: tarefasIndex() }],
    },
});

type Lista = {
    id: number;
    nome: string;
    equipe: string;
    responsaveis: { id: number; name: string }[];
};
type Tarefa = {
    id: number;
    titulo: string;
    descricao: string | null;
    status: string;
    prioridade: string;
    prazo: string | null;
    responsavel: { name: string } | null;
    lista: { nome: string; equipe: { nome: string } };
};

const props = defineProps<{
    tarefas: Tarefa[];
    listas: Lista[];
    statusDisponiveis: { value: string; label: string }[];
}>();

const form = useForm({
    lista_tarefa_id: '',
    responsavel_id: '',
    titulo: '',
    descricao: '',
    prioridade: 'media',
    data_inicio: '',
    prazo: '',
    estimativa_minutos: '',
});

const responsaveisDisponiveis = computed(
    () =>
        props.listas.find((lista) => lista.id === Number(form.lista_tarefa_id))
            ?.responsaveis ?? [],
);

const submit = () =>
    form.post(storeTarefa().url, {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });

const label = (value: string) => value.replaceAll('_', ' ');
const priorityClass = (priority: string) =>
    ({
        baixa: 'bg-slate-100 text-slate-700',
        media: 'bg-blue-100 text-blue-700',
        alta: 'bg-amber-100 text-amber-700',
        urgente: 'bg-rose-100 text-rose-700',
    })[priority] ?? 'bg-muted';
</script>

<template>
    <Head title="Tarefas" />

    <main class="grid gap-6 p-4 lg:grid-cols-[minmax(0,1fr)_22rem] lg:p-6">
        <section class="space-y-4">
            <div>
                <h1 class="text-2xl font-semibold">Tarefas</h1>
                <p class="text-sm text-muted-foreground">
                    Todas as tarefas que você pode acompanhar.
                </p>
            </div>

            <div v-if="tarefas.length" class="grid gap-3">
                <Link
                    v-for="tarefa in tarefas"
                    :key="tarefa.id"
                    :href="tarefaShow(tarefa)"
                    class="rounded-xl border bg-card p-4 shadow-sm transition hover:border-primary/40"
                >
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="font-medium">{{ tarefa.titulo }}</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ tarefa.lista.equipe.nome }} · {{ tarefa.lista.nome }}
                            </p>
                        </div>
                        <span
                            :class="[
                                'rounded-full px-2.5 py-1 text-xs font-medium capitalize',
                                priorityClass(tarefa.prioridade),
                            ]"
                        >
                            {{ tarefa.prioridade }}
                        </span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-sm text-muted-foreground">
                        <span class="capitalize">{{ label(tarefa.status) }}</span>
                        <span>{{ tarefa.responsavel?.name ?? 'Sem responsável' }}</span>
                        <span v-if="tarefa.prazo">
                            Prazo: {{ new Date(tarefa.prazo).toLocaleDateString('pt-BR') }}
                        </span>
                    </div>
                </Link>
            </div>

            <p v-else class="rounded-xl border border-dashed p-10 text-center text-sm text-muted-foreground">
                Ainda não há tarefas disponíveis.
            </p>
        </section>

        <aside class="h-fit rounded-xl border bg-card p-5 shadow-sm">
            <h2 class="font-semibold">Nova tarefa</h2>
            <p class="mt-1 text-sm text-muted-foreground">
                Disponível nas listas que você gerencia.
            </p>

            <form class="mt-5 space-y-4" @submit.prevent="submit">
                <label class="grid gap-1.5 text-sm font-medium">
                    Lista
                    <select
                        v-model="form.lista_tarefa_id"
                        class="h-9 rounded-md border bg-background px-3"
                        @change="form.responsavel_id = ''"
                    >
                        <option value="">Selecione</option>
                        <option v-for="lista in listas" :key="lista.id" :value="lista.id">
                            {{ lista.equipe }} · {{ lista.nome }}
                        </option>
                    </select>
                    <span v-if="form.errors.lista_tarefa_id" class="text-xs text-destructive">
                        {{ form.errors.lista_tarefa_id }}
                    </span>
                </label>

                <label class="grid gap-1.5 text-sm font-medium">
                    Responsável
                    <select
                        v-model="form.responsavel_id"
                        class="h-9 rounded-md border bg-background px-3"
                        :disabled="!form.lista_tarefa_id"
                    >
                        <option value="">Não atribuir agora</option>
                        <option
                            v-for="responsavel in responsaveisDisponiveis"
                            :key="responsavel.id"
                            :value="responsavel.id"
                        >
                            {{ responsavel.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.responsavel_id" class="text-xs text-destructive">
                        {{ form.errors.responsavel_id }}
                    </span>
                </label>

                <label class="grid gap-1.5 text-sm font-medium">
                    Título
                    <input v-model="form.titulo" class="h-9 rounded-md border bg-background px-3" />
                    <span v-if="form.errors.titulo" class="text-xs text-destructive">
                        {{ form.errors.titulo }}
                    </span>
                </label>

                <label class="grid gap-1.5 text-sm font-medium">
                    Prioridade
                    <select v-model="form.prioridade" class="h-9 rounded-md border bg-background px-3">
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </label>

                <label class="grid gap-1.5 text-sm font-medium">
                    Prazo
                    <input v-model="form.prazo" type="datetime-local" class="h-9 rounded-md border bg-background px-3" />
                </label>

                <label class="grid gap-1.5 text-sm font-medium">
                    Descrição
                    <textarea v-model="form.descricao" rows="3" class="rounded-md border bg-background px-3 py-2" />
                </label>

                <button
                    :disabled="form.processing || !listas.length"
                    class="w-full rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50"
                >
                    {{ form.processing ? 'Criando…' : 'Criar tarefa' }}
                </button>
            </form>
        </aside>
    </main>
</template>
