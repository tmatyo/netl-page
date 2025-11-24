<script setup lang="ts">
import { computed } from 'vue';

import { useSidebar } from '@/composables/useSidebar';
import { Link } from '@inertiajs/vue3';
import { ChevronDownIcon, GridIcon, HorizontalDots } from '../../icons';
import SidebarWidget from './SidebarWidget.vue';

const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();
type MenuGroupType = {
    title: string;
    items: Array<{
        icon: any;
        name: string;
        path?: string;
        subItems?: Array<{
            name: string;
            path: string;
            pro?: boolean;
            new?: boolean;
        }>;
    }>;
};
const menuGroups: MenuGroupType[] = [
    {
        title: 'Menu',
        items: [
            {
                icon: GridIcon,
                name: 'Domain SK',
                subItems: [
                    { name: 'Statistics', path: '/domainsk/', pro: false },
                    { name: 'Domain list', path: '/domainsk/domain-list', pro: false },
                    { name: 'Domain owners', path: '/domainsk/domain-owners', pro: false },
                    { name: 'Domain registrars', path: '/domainsk/domain-registrars', pro: false },
                ],
            },
        ],
    },
];

const isActive = (path: string) => window.location.pathname === path;

const toggleSubmenu = (groupIndex: number, itemIndex: number) => {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isAnySubmenuRouteActive = computed(() => {
    return menuGroups.some((group) => group.items.some((item) => item.subItems && item.subItems.some((subItem) => isActive(subItem.path))));
});

const isSubmenuOpen = (groupIndex: number, itemIndex: number) => {
    const key = `${groupIndex}-${itemIndex}`;
    return (
        openSubmenu.value === key ||
        (isAnySubmenuRouteActive.value && menuGroups[groupIndex].items[itemIndex].subItems?.some((subItem) => isActive(subItem.path)))
    );
};

const startTransition = (el: Element) => {
    (el as HTMLElement).style.height = 'auto';
    const height = el.scrollHeight;
    (el as HTMLElement).style.height = '0px';
    (el as HTMLElement).offsetHeight; // force reflow
    (el as HTMLElement).style.height = height + 'px';
};

const endTransition = (el: Element) => {
    (el as HTMLElement).style.height = '';
};
</script>
<template>
    <aside
        :class="[
            'fixed top-0 left-0 z-99999 mt-16 flex h-screen flex-col border-r border-gray-200 bg-white px-5 text-gray-900 transition-all duration-300 ease-in-out lg:mt-0 dark:border-gray-800 dark:bg-gray-900',
            {
                'lg:w-[290px]': isExpanded || isMobileOpen || isHovered,
                'lg:w-[90px]': !isExpanded && !isHovered,
                'w-[290px] translate-x-0': isMobileOpen,
                '-translate-x-full': !isMobileOpen,
                'lg:translate-x-0': true,
            },
        ]"
        @mouseenter="!isExpanded && (isHovered = true)"
        @mouseleave="isHovered = false"
    >
        <div :class="['flex py-8', !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start']">
            <Link href="/">
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="dark:hidden"
                    src="/images/logo/logo.svg"
                    alt="Logo"
                    width="150"
                    height="40"
                />
                <img
                    v-if="isExpanded || isHovered || isMobileOpen"
                    class="hidden dark:block"
                    src="/images/logo/logo-dark.svg"
                    alt="Logo"
                    width="150"
                    height="40"
                />
                <img v-else src="/images/logo/logo-icon.svg" alt="Logo" width="32" height="32" />
            </Link>
        </div>
        <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
            <nav class="mb-6">
                <div class="flex flex-col gap-4">
                    <div v-for="(menuGroup, groupIndex) in menuGroups" :key="groupIndex">
                        <h2
                            :class="[
                                'mb-4 flex text-xs leading-[20px] text-gray-400 uppercase',
                                !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start',
                            ]"
                        >
                            <template v-if="isExpanded || isHovered || isMobileOpen">
                                {{ menuGroup.title }}
                            </template>
                            <HorizontalDots v-else />
                        </h2>
                        <ul class="flex flex-col gap-4">
                            <li v-for="(item, index) in menuGroup.items" :key="item.name">
                                <button
                                    v-if="item.subItems"
                                    @click="toggleSubmenu(groupIndex, index)"
                                    :class="[
                                        'menu-item group w-full',
                                        {
                                            'menu-item-active': isSubmenuOpen(groupIndex, index),
                                            'menu-item-inactive': !isSubmenuOpen(groupIndex, index),
                                        },
                                        !isExpanded && !isHovered ? 'lg:justify-center' : 'lg:justify-start',
                                    ]"
                                >
                                    <span :class="[isSubmenuOpen(groupIndex, index) ? 'menu-item-icon-active' : 'menu-item-icon-inactive']">
                                        <component :is="item.icon" />
                                    </span>
                                    <span v-if="isExpanded || isHovered || isMobileOpen" class="menu-item-text">{{ item.name }}</span>
                                    <ChevronDownIcon
                                        v-if="isExpanded || isHovered || isMobileOpen"
                                        :class="[
                                            'ml-auto h-5 w-5 transition-transform duration-200',
                                            {
                                                'text-brand-500 rotate-180': isSubmenuOpen(groupIndex, index),
                                            },
                                        ]"
                                    />
                                </button>
                                <Link
                                    v-else-if="item.path"
                                    :href="item.path"
                                    :class="[
                                        'menu-item group',
                                        {
                                            'menu-item-active': isActive(item.path),
                                            'menu-item-inactive': !isActive(item.path),
                                        },
                                    ]"
                                >
                                    <span :class="[isActive(item.path) ? 'menu-item-icon-active' : 'menu-item-icon-inactive']">
                                        <component :is="item.icon" />
                                    </span>
                                    <span v-if="isExpanded || isHovered || isMobileOpen" class="menu-item-text">{{ item.name }}</span>
                                </Link>
                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div v-show="isSubmenuOpen(groupIndex, index) && (isExpanded || isHovered || isMobileOpen)">
                                        <ul class="mt-2 ml-9 space-y-1">
                                            <li v-for="subItem in item.subItems" :key="subItem.name">
                                                <Link
                                                    :href="subItem.path"
                                                    :class="[
                                                        'menu-dropdown-item',
                                                        {
                                                            'menu-dropdown-item-active': isActive(subItem.path),
                                                            'menu-dropdown-item-inactive': !isActive(subItem.path),
                                                        },
                                                    ]"
                                                >
                                                    {{ subItem.name }}
                                                    <span class="ml-auto flex items-center gap-1">
                                                        <span
                                                            v-if="subItem.new"
                                                            :class="[
                                                                'menu-dropdown-badge',
                                                                {
                                                                    'menu-dropdown-badge-active': isActive(subItem.path),
                                                                    'menu-dropdown-badge-inactive': !isActive(subItem.path),
                                                                },
                                                            ]"
                                                        >
                                                            new
                                                        </span>
                                                        <span
                                                            v-if="subItem.pro"
                                                            :class="[
                                                                'menu-dropdown-badge',
                                                                {
                                                                    'menu-dropdown-badge-active': isActive(subItem.path),
                                                                    'menu-dropdown-badge-inactive': !isActive(subItem.path),
                                                                },
                                                            ]"
                                                        >
                                                            pro
                                                        </span>
                                                    </span>
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </transition>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <SidebarWidget v-if="isExpanded || isHovered || isMobileOpen" />
        </div>
    </aside>
</template>
