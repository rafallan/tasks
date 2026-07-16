<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { index as equipesIndex, show as equipeShow, store as storeEquipe } from '@/routes/equipes';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Equipes', href: equipesIndex() }],
    },
});

type Equipe = { id: number; nome: string; descricao: string | null; usuarios_count: number; listas_tarefas_count: number };
defineProps<{ equipes: Equipe[] }>();
const form = useForm({ nome: '', descricao: '', ativo: true });
const submit = () => form.post(storeEquipe().url, { onSuccess: () => form.reset() });
</script>

<template>
    <Head title="Equipes" />
    <main class="grid gap-6 p-4 lg:grid-cols-[minmax(0,1fr)_22rem] lg:p-6"><section><h1 class="text-2xl font-semibold">Equipes</h1><p class="mt-1 text-sm text-muted-foreground">Organize pessoas, listas e responsabilidades.</p><div v-if="equipes.length" class="mt-6 grid gap-4 sm:grid-cols-2"><Link v-for="equipe in equipes" :key="equipe.id" :href="equipeShow(equipe)" class="rounded-xl border bg-card p-5 shadow-sm transition hover:border-primary/40"><h2 class="font-semibold">{{ equipe.nome }}</h2><p class="mt-2 line-clamp-2 text-sm text-muted-foreground">{{ equipe.descricao || 'Sem descrição.' }}</p><p class="mt-5 text-sm text-muted-foreground">{{ equipe.usuarios_count }} membros · {{ equipe.listas_tarefas_count }} listas</p></Link></div><p v-else class="mt-6 rounded-xl border border-dashed p-10 text-center text-sm text-muted-foreground">Nenhuma equipe disponível.</p></section><aside class="h-fit rounded-xl border bg-card p-5 shadow-sm"><h2 class="font-semibold">Nova equipe</h2><form class="mt-5 space-y-4" @submit.prevent="submit"><label class="grid gap-1.5 text-sm font-medium">Nome<input v-model="form.nome" class="h-9 rounded-md border bg-background px-3" /><span v-if="form.errors.nome" class="text-xs text-destructive">{{ form.errors.nome }}</span></label><label class="grid gap-1.5 text-sm font-medium">Descrição<textarea v-model="form.descricao" rows="4" class="rounded-md border bg-background px-3 py-2" /></label><button :disabled="form.processing" class="w-full rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50">{{ form.processing ? 'Criando…' : 'Criar equipe' }}</button></form></aside></main>
</template>
