<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { index as papeisIndex, store, update } from '@/routes/admin/papeis';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Administração', href: papeisIndex() }, { title: 'Papéis e permissões', href: papeisIndex() }],
    },
});

type Permission = { id: number; name: string };
type Role = { id: number; name: string; users_count: number; permissions: Permission[] };

const props = defineProps<{ roles: Role[]; permissions: Permission[] }>();
const selectedRole = ref<Role | null>(null);
const createForm = useForm({ name: '', permissions: [] as string[] });
const editForm = useForm({ name: '', permissions: [] as string[] });

const createRole = (): void => {
    createForm.post(store().url, { onSuccess: () => createForm.reset() });
};

const selectRole = (role: Role): void => {
    selectedRole.value = role;
    editForm.clearErrors();
    editForm.name = role.name;
    editForm.permissions = role.permissions.map((permission) => permission.name);
};

const updateRole = (): void => {
    if (selectedRole.value === null) {
        return;
    }

    editForm.put(update(selectedRole.value).url, { preserveScroll: true });
};
</script>

<template>
    <Head title="Papéis e permissões" />

    <main class="space-y-6 p-4 md:p-6">
        <div>
            <h1 class="text-2xl font-semibold">Papéis e permissões</h1>
            <p class="mt-1 text-sm text-muted-foreground">Defina o que cada papel pode fazer no sistema.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="rounded-xl border bg-card p-5 shadow-sm">
                <h2 class="font-semibold">Novo papel</h2>
                <form class="mt-5 space-y-4" @submit.prevent="createRole">
                    <label class="grid gap-1.5 text-sm font-medium">Nome
                        <input v-model="createForm.name" class="h-9 rounded-md border bg-background px-3" />
                        <span v-if="createForm.errors.name" class="text-xs text-destructive">{{ createForm.errors.name }}</span>
                    </label>
                    <fieldset class="grid gap-2"><legend class="text-sm font-medium">Permissões</legend>
                        <label v-for="permission in permissions" :key="permission.id" class="flex items-center gap-2 text-sm"><input v-model="createForm.permissions" type="checkbox" :value="permission.name" />{{ permission.name }}</label>
                    </fieldset>
                    <button :disabled="createForm.processing" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50">{{ createForm.processing ? 'Criando…' : 'Criar papel' }}</button>
                </form>
            </section>

            <section class="rounded-xl border bg-card p-5 shadow-sm">
                <template v-if="selectedRole">
                    <h2 class="font-semibold">Editar {{ selectedRole.name }}</h2>
                    <p v-if="selectedRole.name === 'Admin'" class="mt-1 text-sm text-muted-foreground">O nome do papel Admin é protegido. Administradores têm acesso total.</p>
                    <form class="mt-5 space-y-4" @submit.prevent="updateRole">
                        <label class="grid gap-1.5 text-sm font-medium">Nome
                            <input v-model="editForm.name" :disabled="selectedRole.name === 'Admin'" class="h-9 rounded-md border bg-background px-3 disabled:cursor-not-allowed disabled:opacity-60" />
                            <span v-if="editForm.errors.name" class="text-xs text-destructive">{{ editForm.errors.name }}</span>
                        </label>
                        <fieldset class="grid gap-2"><legend class="text-sm font-medium">Permissões</legend>
                            <label v-for="permission in permissions" :key="permission.id" class="flex items-center gap-2 text-sm"><input v-model="editForm.permissions" type="checkbox" :value="permission.name" />{{ permission.name }}</label>
                        </fieldset>
                        <button :disabled="editForm.processing" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50">{{ editForm.processing ? 'Salvando…' : 'Salvar alterações' }}</button>
                    </form>
                </template>
                <p v-else class="py-16 text-center text-sm text-muted-foreground">Selecione um papel na lista para editá-lo.</p>
            </section>
        </div>

        <section class="overflow-hidden rounded-xl border bg-card shadow-sm">
            <div class="border-b px-5 py-4"><h2 class="font-semibold">Papéis cadastrados</h2></div>
            <div v-if="props.roles.length" class="divide-y">
                <button v-for="role in props.roles" :key="role.id" type="button" class="flex w-full flex-wrap items-center justify-between gap-3 px-5 py-4 text-left transition hover:bg-muted/50" @click="selectRole(role)">
                    <div><p class="font-medium">{{ role.name }}</p><p class="text-sm text-muted-foreground">{{ role.permissions.map((permission) => permission.name).join(', ') || 'Sem permissões diretas' }}</p></div>
                    <span class="text-sm text-muted-foreground">{{ role.users_count }} usuário(s)</span>
                </button>
            </div>
            <p v-else class="p-8 text-center text-sm text-muted-foreground">Nenhum papel cadastrado.</p>
        </section>
    </main>
</template>
