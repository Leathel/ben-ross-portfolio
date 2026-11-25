<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    BriefcaseBusiness,
    ExternalLink,
    FolderGit2,
    History,
    Inbox,
    LayoutGrid,
    UserRound,
    Wrench,
} from '@lucide/vue';
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
import { home } from '@/routes';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index as experienceIndex } from '@/routes/admin/experience';
import { index as messagesIndex } from '@/routes/admin/messages';
import { edit as profileEdit } from '@/routes/admin/profile';
import { index as projectsIndex } from '@/routes/admin/projects';
import { index as skillsIndex } from '@/routes/admin/skills';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Overview',
        href: adminDashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Projects',
        href: projectsIndex(),
        icon: BriefcaseBusiness,
    },
    {
        title: 'Skills',
        href: skillsIndex(),
        icon: Wrench,
    },
    {
        title: 'Experience',
        href: experienceIndex(),
        icon: History,
    },
    {
        title: 'Messages',
        href: messagesIndex(),
        icon: Inbox,
    },
    {
        title: 'Profile',
        href: profileEdit(),
        icon: UserRound,
    },
    {
        title: 'View site',
        href: home(),
        icon: ExternalLink,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="adminDashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
