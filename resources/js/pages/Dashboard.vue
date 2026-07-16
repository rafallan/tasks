<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2, CircleAlert, Clock3, ListTodo, PlayCircle } from '@lucide/vue';
import { dashboard } from '@/routes';
import { index as tarefasIndex, show as tarefaShow } from '@/routes/tarefas';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
    },
});

type Tarefa = { id: number; titulo: string; status: string; prioridade: string; prazo: string | null; responsavel: { name: string } | null; lista: { nome: string; equipe: { nome: string } } };
defineProps<{ resumo: Record<string, number>; tarefasRecentes: Tarefa[] }>();

const cards = [
    { key: 'pendentes', label: 'Pendentes', icon: ListTodo, class: 'text-slate-600 dark:text-slate-300' },
    { key: 'em_andamento', label: 'Em andamento', icon: PlayCircle, class: 'text-blue-600' },
    { key: 'bloqueadas', label: 'Bloqueadas', icon: CircleAlert, class: 'text-amber-600' },
    { key: 'concluidas', label: 'Concluídas', icon: CheckCircle2, class: 'text-emerald-600' },
    { key: 'atrasadas', label: 'Atrasadas', icon: Clock3, class: 'text-rose-600' },
];

const label = (value: string) => value.replaceAll('_', ' ');
</script>

<template>
    <Head title="Dashboard" />
    <main class="space-y-6 p-4 md:p-6">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div><h1 class="text-2xl font-semibold">Visão geral</h1><p class="text-sm text-muted-foreground">Acompanhe o trabalho das suas equipes.</p></div>
                <Link :href="tarefasIndex()" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground">Ver tarefas</Link>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <div v-for="card in cards" :key="card.key" class="rounded-xl border bg-card p-4 shadow-sm"><component :is="card.icon" :class="['mb-4 size-5', card.class]" /><p class="text-3xl font-semibold">{{ resumo[card.key] }}</p><p class="mt-1 text-sm text-muted-foreground">{{ card.label }}</p></div>
            </div>
            <section class="rounded-xl border bg-card"><div class="border-b px-5 py-4"><h2 class="font-semibold">Tarefas recentes</h2></div><div v-if="tarefasRecentes.length" class="divide-y"><Link v-for="tarefa in tarefasRecentes" :key="tarefa.id" :href="tarefaShow(tarefa)" class="flex flex-wrap items-center justify-between gap-2 px-5 py-4 hover:bg-muted/50"><div><p class="font-medium">{{ tarefa.titulo }}</p><p class="text-sm text-muted-foreground">{{ tarefa.lista.equipe.nome }} · {{ tarefa.lista.nome }}</p></div><div class="text-right text-sm"><p class="capitalize">{{ label(tarefa.status) }}</p><p class="text-muted-foreground">{{ tarefa.responsavel?.name ?? 'Sem responsável' }}</p></div></Link></div><p v-else class="p-8 text-center text-sm text-muted-foreground">Nenhuma tarefa para exibir.</p></section>
    </main>
</template>
