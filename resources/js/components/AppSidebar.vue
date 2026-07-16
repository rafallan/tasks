<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { CheckSquare, KeyRound, LayoutGrid, UserCog, Users } from '@lucide/vue';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as papeisIndex } from '@/routes/admin/papeis';
import { index as usuariosIndex } from '@/routes/admin/usuarios';
import { index as equipesIndex } from '@/routes/equipes';
import { index as tarefasIndex } from '@/routes/tarefas';
import type { NavItem } from '@/types';

const page = usePage();
const isAdmin = computed(() => page.props.auth.isAdmin === true);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Tarefas',
        href: tarefasIndex(),
        icon: CheckSquare,
    },
    {
        title: 'Equipes',
        href: equipesIndex(),
        icon: Users,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Usuários',
        href: usuariosIndex(),
        icon: UserCog,
    },
    {
        title: 'Papéis e permissões',
        href: papeisIndex(),
        icon: KeyRound,
    },
];

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain label="Trabalho" :items="mainNavItems" />
            <NavMain v-if="isAdmin" label="Administração" :items="adminNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
