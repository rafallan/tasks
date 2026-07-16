<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { store as storeLista } from '@/routes/listas-tarefas';
import { index as equipesIndex } from '@/routes/equipes';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Equipes', href: equipesIndex() }],
    },
});

type Equipe = { id: number; nome: string; descricao: string | null; usuarios: { id: number; name: string; email: string; pivot: { tipo_participacao: string; ativo: boolean } }[]; listas_tarefas: { id: number; nome: string; descricao: string | null; cor: string | null; arquivada_em: string | null }[] };
const props = defineProps<{ equipe: Equipe }>();
const form = useForm({ equipe_id: props.equipe.id, nome: '', descricao: '', cor: '' });
const submit = () => form.post(storeLista().url, { preserveScroll: true, onSuccess: () => form.reset('nome', 'descricao', 'cor') });
</script>

<template>
    <Head :title="equipe.nome" />
    <main class="w-full space-y-6 p-4 lg:p-6"><Link :href="equipesIndex()" class="text-sm text-muted-foreground hover:text-foreground">← Voltar para equipes</Link><div><h1 class="text-2xl font-semibold">{{ equipe.nome }}</h1><p class="mt-1 text-muted-foreground">{{ equipe.descricao || 'Sem descrição.' }}</p></div><div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]"><section class="rounded-xl border bg-card p-5 shadow-sm"><h2 class="font-semibold">Listas de tarefas</h2><div v-if="equipe.listas_tarefas.length" class="mt-4 grid gap-3 sm:grid-cols-2"><div v-for="lista in equipe.listas_tarefas" :key="lista.id" class="rounded-lg border p-4"><div class="flex items-center gap-2"><span :style="{ backgroundColor: lista.cor || '#94a3b8' }" class="size-3 rounded-full" /><h3 class="font-medium">{{ lista.nome }}</h3></div><p class="mt-2 text-sm text-muted-foreground">{{ lista.descricao || 'Sem descrição.' }}</p><p v-if="lista.arquivada_em" class="mt-3 text-xs text-amber-600">Arquivada</p></div></div><p v-else class="mt-4 text-sm text-muted-foreground">Nenhuma lista criada.</p><h2 class="mt-8 font-semibold">Membros</h2><ul class="mt-4 divide-y"><li v-for="membro in equipe.usuarios" :key="membro.id" class="flex items-center justify-between py-3"><span><span class="block text-sm font-medium">{{ membro.name }}</span><span class="text-xs text-muted-foreground">{{ membro.email }}</span></span><span class="capitalize text-sm text-muted-foreground">{{ membro.pivot.tipo_participacao }}</span></li></ul></section><aside class="h-fit rounded-xl border bg-card p-5 shadow-sm"><h2 class="font-semibold">Nova lista</h2><form class="mt-5 space-y-4" @submit.prevent="submit"><label class="grid gap-1.5 text-sm font-medium">Nome<input v-model="form.nome" class="h-9 rounded-md border bg-background px-3" /><span v-if="form.errors.nome" class="text-xs text-destructive">{{ form.errors.nome }}</span></label><label class="grid gap-1.5 text-sm font-medium">Cor<input v-model="form.cor" type="color" class="h-9 w-full rounded-md border bg-background px-1" /></label><label class="grid gap-1.5 text-sm font-medium">Descrição<textarea v-model="form.descricao" rows="3" class="rounded-md border bg-background px-3 py-2" /></label><button :disabled="form.processing" class="w-full rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground">Criar lista</button></form></aside></div></main>
</template>
