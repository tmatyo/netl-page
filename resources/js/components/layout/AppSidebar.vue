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
        <div :class="['flex py-8', !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start']"></div>
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

                                <transition
                                    @enter="startTransition"
                                    @after-enter="endTransition"
                                    @before-leave="startTransition"
                                    @after-leave="endTransition"
                                >
                                    <div v-show="isSubmenuOpen(groupIndex, index) && (isExpanded || isHovered || isMobileOpen)">
                                        <ul class="mt-2 ml-9 space-y-1">
                                            <li v-for="subItem in item.subItems" :key="subItem.name">hajajaj</li>
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

<script setup>
import { computed } from 'vue';

import { useSidebar } from '@/composables/useSidebar';
import BoxCubeIcon from '@/icons/BoxCubeIcon.vue';
import {
    CalenderIcon,
    ChevronDownIcon,
    GridIcon,
    HorizontalDots,
    ListIcon,
    PageIcon,
    PieChartIcon,
    PlugInIcon,
    TableIcon,
    UserCircleIcon,
} from '../../icons';
import SidebarWidget from './SidebarWidget.vue';

const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();

const menuGroups = [
    {
        title: 'Menu',
        items: [
            {
                icon: GridIcon,
                name: 'Dashboard',
                subItems: [{ name: 'Ecommerce', path: '/', pro: false }],
            },
            {
                icon: CalenderIcon,
                name: 'Calendar',
                path: '/calendar',
            },
            {
                icon: UserCircleIcon,
                name: 'User Profile',
                path: '/profile',
            },

            {
                name: 'Forms',
                icon: ListIcon,
                subItems: [{ name: 'Form Elements', path: '/form-elements', pro: false }],
            },
            {
                name: 'Tables',
                icon: TableIcon,
                subItems: [{ name: 'Basic Tables', path: '/basic-tables', pro: false }],
            },
            {
                name: 'Pages',
                icon: PageIcon,
                subItems: [
                    { name: 'Black Page', path: '/blank', pro: false },
                    { name: '404 Page', path: '/error-404', pro: false },
                ],
            },
        ],
    },
    {
        title: 'Others',
        items: [
            {
                icon: PieChartIcon,
                name: 'Charts',
                subItems: [
                    { name: 'Line Chart', path: '/line-chart', pro: false },
                    { name: 'Bar Chart', path: '/bar-chart', pro: false },
                ],
            },
            {
                icon: BoxCubeIcon,
                name: 'Ui Elements',
                subItems: [
                    { name: 'Alerts', path: '/alerts', pro: false },
                    { name: 'Avatars', path: '/avatars', pro: false },
                    { name: 'Badge', path: '/badge', pro: false },
                    { name: 'Buttons', path: '/buttons', pro: false },
                    { name: 'Images', path: '/images', pro: false },
                    { name: 'Videos', path: '/videos', pro: false },
                ],
            },
            {
                icon: PlugInIcon,
                name: 'Authentication',
                subItems: [
                    { name: 'Signin', path: '/signin', pro: false },
                    { name: 'Signup', path: '/signup', pro: false },
                ],
            },
            // ... Add other menu items here
        ],
    },
];


const toggleSubmenu = (groupIndex, itemIndex) => {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isAnySubmenuRouteActive = computed(() => {
    return menuGroups.some((group) => group.items.some((item) => item.subItems && item.subItems.some(() => false)));
});

const isSubmenuOpen = (groupIndex, itemIndex) => {
    const key = `${groupIndex}-${itemIndex}`;
    return (
        openSubmenu.value === key ||
        (isAnySubmenuRouteActive.value && menuGroups[groupIndex].items[itemIndex].subItems?.some(() => false))
    );
};

const startTransition = (el) => {
    el.style.height = 'auto';
    const height = el.scrollHeight;
    el.style.height = '0px';
    el.offsetHeight; // force reflow
    el.style.height = height + 'px';
};

const endTransition = (el) => {
    el.style.height = '';
};
</script>
