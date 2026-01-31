<script setup lang="ts">
import { useSidebar } from '@/composables/useSidebar';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { t as $t } from '../../helpers/i18n';
import { ChevronDownIcon, GridIcon, HorizontalDots, ListIcon } from '../../icons';
import Logo from './Logo.vue';
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
const menuGroups = computed<MenuGroupType[]>(() => [
    {
        title: $t('menu'),
        items: [
            { icon: GridIcon, name: $t('statistics'), path: '/domainsk/' },
            { icon: ListIcon, name: $t('domains'), path: '/domainsk/domain-list' },
            { icon: ListIcon, name: $t('owners'), path: '/domainsk/owners-marketshare' },
            { icon: ListIcon, name: $t('registrars'), path: '/domainsk/registrars-marketshare' },
            { icon: ListIcon, name: $t('nameservers'), path: '/domainsk/nameserver-marketshare' },
        ],
    },
]);

const isActive = (path: string) => window.location.pathname === path;

const toggleSubmenu = (groupIndex: number, itemIndex: number) => {
    const key = `${groupIndex}-${itemIndex}`;
    openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isAnySubmenuRouteActive = computed(() => {
    return menuGroups.value.some((group) => group.items.some((item) => item.subItems && item.subItems.some((subItem) => isActive(subItem.path))));
});

const isSubmenuOpen = (groupIndex: number, itemIndex: number) => {
    const key = `${groupIndex}-${itemIndex}`;
    return (
        openSubmenu.value === key ||
        (isAnySubmenuRouteActive.value && menuGroups.value[groupIndex].items[itemIndex].subItems?.some((subItem) => isActive(subItem.path)))
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
            'fixed top-0 left-0 z-99999 mt-16 flex h-screen flex-col border-r border-dashed border-gray-200 dark:brand-border bg-white px-5 brand-text transition-all duration-300 ease-in-out lg:mt-0 dark:border-gray-800 dark:brand-bg',
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
        <div :class="['flex justify-start py-8 lg:justify-center']">
            <Link href="/">
                <Logo class="w-full" />
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
                                                'brand-text rotate-180': isSubmenuOpen(groupIndex, index),
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
