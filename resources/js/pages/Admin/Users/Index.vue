<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { index as usuariosIndex, store, update } from '@/routes/admin/usuarios';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Administração', href: usuariosIndex() }, { title: 'Usuários', href: usuariosIndex() }],
    },
});

type Role = { id: number; name: string };
type ManagedUser = { id: number; name: string; email: string; ativo: boolean; roles: Role[] };

const props = defineProps<{ users: ManagedUser[]; roles: Role[] }>();
const selectedUser = ref<ManagedUser | null>(null);

const createForm = useForm({ name: '', email: '', password: '', ativo: true, roles: [] as string[] });
const editForm = useForm({ name: '', email: '', password: '', ativo: true, roles: [] as string[] });

const createUser = (): void => {
    createForm.post(store().url, {
        onSuccess: () => createForm.reset(),
    });
};

const selectUser = (user: ManagedUser): void => {
    selectedUser.value = user;
    editForm.clearErrors();
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.password = '';
    editForm.ativo = user.ativo;
    editForm.roles = user.roles.map((role) => role.name);
};

const updateUser = (): void => {
    if (selectedUser.value === null) {
        return;
    }

    editForm.put(update(selectedUser.value).url, {
        preserveScroll: true,
        onSuccess: () => {
            editForm.password = '';
        },
    });
};
</script>

<template>
    <Head title="Usuários" />

    <main class="space-y-6 p-4 md:p-6">
        <div>
            <h1 class="text-2xl font-semibold">Gestão de usuários</h1>
            <p class="mt-1 text-sm text-muted-foreground">Crie usuários, altere seus dados e defina os papéis de acesso.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="rounded-xl border bg-card p-5 shadow-sm">
                <h2 class="font-semibold">Novo usuário</h2>
                <form class="mt-5 space-y-4" @submit.prevent="createUser">
                    <label class="grid gap-1.5 text-sm font-medium">Nome
                        <input v-model="createForm.name" class="h-9 rounded-md border bg-background px-3" autocomplete="name" />
                        <span v-if="createForm.errors.name" class="text-xs text-destructive">{{ createForm.errors.name }}</span>
                    </label>
                    <label class="grid gap-1.5 text-sm font-medium">E-mail
                        <input v-model="createForm.email" type="email" class="h-9 rounded-md border bg-background px-3" autocomplete="email" />
                        <span v-if="createForm.errors.email" class="text-xs text-destructive">{{ createForm.errors.email }}</span>
                    </label>
                    <label class="grid gap-1.5 text-sm font-medium">Senha inicial
                        <input v-model="createForm.password" type="password" class="h-9 rounded-md border bg-background px-3" autocomplete="new-password" />
                        <span v-if="createForm.errors.password" class="text-xs text-destructive">{{ createForm.errors.password }}</span>
                    </label>
                    <fieldset class="grid gap-2"><legend class="text-sm font-medium">Papéis</legend>
                        <label v-for="role in roles" :key="role.id" class="flex items-center gap-2 text-sm"><input v-model="createForm.roles" type="checkbox" :value="role.name" />{{ role.name }}</label>
                        <span v-if="createForm.errors.roles" class="text-xs text-destructive">{{ createForm.errors.roles }}</span>
                    </fieldset>
                    <label class="flex items-center gap-2 text-sm font-medium"><input v-model="createForm.ativo" type="checkbox" />Usuário ativo</label>
                    <button :disabled="createForm.processing" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50">{{ createForm.processing ? 'Criando…' : 'Criar usuário' }}</button>
                </form>
            </section>

            <section class="rounded-xl border bg-card p-5 shadow-sm">
                <template v-if="selectedUser">
                    <h2 class="font-semibold">Editar {{ selectedUser.name }}</h2>
                    <form class="mt-5 space-y-4" @submit.prevent="updateUser">
                        <label class="grid gap-1.5 text-sm font-medium">Nome
                            <input v-model="editForm.name" class="h-9 rounded-md border bg-background px-3" autocomplete="name" />
                            <span v-if="editForm.errors.name" class="text-xs text-destructive">{{ editForm.errors.name }}</span>
                        </label>
                        <label class="grid gap-1.5 text-sm font-medium">E-mail
                            <input v-model="editForm.email" type="email" class="h-9 rounded-md border bg-background px-3" autocomplete="email" />
                            <span v-if="editForm.errors.email" class="text-xs text-destructive">{{ editForm.errors.email }}</span>
                        </label>
                        <label class="grid gap-1.5 text-sm font-medium">Nova senha <span class="font-normal text-muted-foreground">(opcional)</span>
                            <input v-model="editForm.password" type="password" class="h-9 rounded-md border bg-background px-3" autocomplete="new-password" />
                            <span v-if="editForm.errors.password" class="text-xs text-destructive">{{ editForm.errors.password }}</span>
                        </label>
                        <fieldset class="grid gap-2"><legend class="text-sm font-medium">Papéis</legend>
                            <label v-for="role in roles" :key="role.id" class="flex items-center gap-2 text-sm"><input v-model="editForm.roles" type="checkbox" :value="role.name" />{{ role.name }}</label>
                            <span v-if="editForm.errors.roles" class="text-xs text-destructive">{{ editForm.errors.roles }}</span>
                        </fieldset>
                        <label class="flex items-center gap-2 text-sm font-medium"><input v-model="editForm.ativo" type="checkbox" />Usuário ativo</label>
                        <button :disabled="editForm.processing" class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50">{{ editForm.processing ? 'Salvando…' : 'Salvar alterações' }}</button>
                    </form>
                </template>
                <p v-else class="py-16 text-center text-sm text-muted-foreground">Selecione um usuário na lista para editá-lo.</p>
            </section>
        </div>

        <section class="overflow-hidden rounded-xl border bg-card shadow-sm">
            <div class="border-b px-5 py-4"><h2 class="font-semibold">Usuários cadastrados</h2></div>
            <div v-if="props.users.length" class="divide-y">
                <button v-for="user in props.users" :key="user.id" type="button" class="flex w-full flex-wrap items-center justify-between gap-3 px-5 py-4 text-left transition hover:bg-muted/50" @click="selectUser(user)">
                    <div><p class="font-medium">{{ user.name }}</p><p class="text-sm text-muted-foreground">{{ user.email }}</p></div>
                    <div class="flex items-center gap-3 text-sm"><span class="text-muted-foreground">{{ user.roles.map((role) => role.name).join(', ') || 'Sem papel' }}</span><span :class="user.ativo ? 'text-emerald-600' : 'text-destructive'">{{ user.ativo ? 'Ativo' : 'Inativo' }}</span></div>
                </button>
            </div>
            <p v-else class="p-8 text-center text-sm text-muted-foreground">Nenhum usuário cadastrado.</p>
        </section>
    </main>
</template>
