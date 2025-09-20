<script setup lang="ts">
import type { SidebarProps } from '@/components/ui/sidebar'


import NavUser from '@/components/NavUser.vue'
import TeamSwitcher from '@/components/TeamSwitcher.vue'

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarRail,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar'
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible'
import { NavItem } from '@/types/vnwa'
import { ref } from 'vue'
import { Icon } from '@iconify/vue'
import { AudioWaveform, ChevronRight, Command, GalleryVerticalEnd } from 'lucide-vue-next'
import VnwaLink from './VnwaLink.vue'

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: "icon",
})


const currentUrl = window.location.origin + window.location.pathname
const isActive = (item: NavItem): boolean => {
    if (item.to && currentUrl == item.to) {
        return true
    }

    // Nếu có children thì đệ quy check
    if (item.childs && item.childs.length > 0) {
        return item.childs.some(child => isActive(child))
    }

    return false
}
const items = ref<NavItem[]>([
    {
        label: 'Dashboard',
        to: route('vnwa.dashboard'),
        icon: 'material-symbols:analytics-outline',
    },
    {
        label: 'Ecommerce',
        icon: 'material-symbols:shopping-basket-outline',
        childs: [
            {
                label: 'Products',
                to: route('vnwa.product.index'),
            },
            {
                label: 'Categories',
                to: route('vnwa.product.category.index'),
            },
            {
                label: 'Brands',
                to: route('vnwa.product.brand.index'),
            }
        ]
    },
    {
        label: 'Users',
        icon: 'material-symbols:user-attributes',
        to: route('vnwa.user.list'),
    },

    {
        label: 'VMedia ',
        icon: 'material-symbols:perm-media',
        to: route('vnwa.media.index'),
    },
])




</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <a href="#">
                            <div
                                class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                <GalleryVerticalEnd class="size-4" />
                            </div>
                            <div class="flex flex-col gap-0.5 leading-none">
                                <span class="font-semibold">Documentation</span>
                                <span class="">v1.0.0</span>
                            </div>
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupLabel>Platform</SidebarGroupLabel>
                <SidebarMenu>
                    <template v-for="(item, index) in items" :key="index">
                        <template v-if="item.childs && item.childs.length > 0">
                            <Collapsible :key="index" as-child :default-open="isActive(item)" class="group/collapsible">
                                <SidebarMenuItem>
                                    <CollapsibleTrigger as-child>
                                        <SidebarMenuButton :tooltip="item.label">
                                            <template v-if="item.to">
                                                <VnwaLink :to="item.to" class="flex items-center justify-start gap-2">
                                                    <Icon v-if="item.icon" :icon="item.icon"
                                                        class=" h-[1.2rem] w-[1.2rem]" />
                                                    <span>{{ item.label }}</span>
                                                </VnwaLink>
                                            </template>
                                            <template v-else class="flex items-center justify-start gap-2">
                                                <Icon v-if="item.icon" :icon="item.icon"
                                                    class=" h-[1.2rem] w-[1.2rem]" />
                                                <span>{{ item.label }}</span>
                                            </template>

                                            <ChevronRight
                                                class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                        </SidebarMenuButton>
                                    </CollapsibleTrigger>
                                    <CollapsibleContent>
                                        <SidebarMenuSub>
                                            <SidebarMenuSubItem v-for="(child, n) in item.childs" :key="n">
                                                <SidebarMenuButton :tooltip="child.label">
                                                    <div :class="isActive(child) ? 'font-bold text-primary' : ''">

                                                        <template v-if="child.to">
                                                            <VnwaLink :to="child.to"
                                                                class="flex items-center justify-start gap-2">
                                                                <Icon v-if="child.icon" :icon="child.icon"
                                                                    class=" h-[1.2rem] w-[1.2rem]" />
                                                                <span>{{ child.label }}</span>
                                                            </VnwaLink>
                                                        </template>
                                                        <template v-else class="flex items-center justify-start gap-2">
                                                            <Icon v-if="child.icon" :icon="child.icon"
                                                                class=" h-[1.2rem] w-[1.2rem]" />
                                                            <span>{{ child.label }}</span>
                                                        </template>
                                                    </div>


                                                </SidebarMenuButton>
                                            </SidebarMenuSubItem>
                                        </SidebarMenuSub>
                                    </CollapsibleContent>
                                </SidebarMenuItem>
                            </Collapsible>
                        </template>


                        <template v-else>
                            <SidebarMenuItem :key="index">
                                <SidebarMenuButton as-child>
                                    <div :class="isActive(item) ? 'font-bold text-primary' : ''">

                                        <VnwaLink v-if="item.to" :to="item.to"
                                            class="flex items-center jusfiy-start gap-2 ">
                                            <Icon v-if="item.icon" :icon="item.icon" class=" h-[1.2rem] w-[1.2rem]" />
                                            <span class="">{{ item.label }}</span>
                                        </VnwaLink>
                                        <div v-else class="flex items-center jusfiy-start gap-2">
                                            <Icon v-if="item.icon" :icon="item.icon" class=" h-[1.2rem] w-[1.2rem]" />
                                            <span class="">{{ item.label }}</span>
                                        </div>
                                    </div>

                                </SidebarMenuButton>
                            </SidebarMenuItem>

                        </template>
                    </template>

                </SidebarMenu>
            </SidebarGroup>

        </SidebarContent>
        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
