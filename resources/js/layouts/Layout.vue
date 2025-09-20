<script setup lang="ts">
import '../../css/admin.css'
import '../../css/data-table.css'
import { Head } from '@inertiajs/vue3';
import { ModalsContainer } from 'vue-final-modal'
import AppSidebar from "@/components/AppSidebar.vue"
import ColorMode from "@/components/ColorMode.vue"
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb"
import { Separator } from "@/components/ui/separator"
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/components/ui/sidebar"
import Toaster from '@/components/ui/toast/Toaster.vue';

const path = window.location.pathname // "/vnwa/abv/x/t/z"

const segments = path.split("/").filter(p => p !== "") // ["vnwa","abv","x","t","z"]

const breadcrumbs = segments.map((seg, index) => {
    return {
        label: seg,
        to: "/" + segments.slice(0, index + 1).join("/") // ghép từ đầu đến hiện tại
    }
})
const props = defineProps<{
    title?: string
}>()
</script>

<template>
    <div>
        <ModalsContainer />
        <Toaster />

        <Head :title="title || 'Dashboard'" />

        <SidebarProvider>
            <AppSidebar />
            <SidebarInset>
                <header
                    class="flex h-16 shrink-0 items-center justify-between gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 border-b border-gray-500">
                    <div class="flex items-center justify-start gap-2 px-4">
                        <SidebarTrigger class="-ml-1" />
                        <Separator orientation="vertical" class="mr-2 h-4" />
                        <Breadcrumb>
                            <BreadcrumbList>
                                <template v-for="(item, index) in breadcrumbs" :key="index">
                                    <template v-if="index != breadcrumbs.length - 1">
                                        <BreadcrumbItem class="hidden md:block capitalize">
                                            <BreadcrumbLink :href="item.to">
                                                {{ item.label }}
                                            </BreadcrumbLink>
                                        </BreadcrumbItem>
                                        <BreadcrumbSeparator class="hidden md:block" />
                                    </template>
                                    <template v-else>
                                        <BreadcrumbItem>
                                            <BreadcrumbPage><span class="capitalize font-bold">{{ item.label }}</span>
                                            </BreadcrumbPage>
                                        </BreadcrumbItem>
                                    </template>

                                </template>




                            </BreadcrumbList>
                        </Breadcrumb>
                    </div>
                    <div class="flex items-center jusify-end gap-2 px-4">
                        <ColorMode />
                    </div>

                </header>

                <div class="flex flex-1 flex-col gap-4 p-6  ">
                    <slot />
                </div>
            </SidebarInset>
        </SidebarProvider>
    </div>
</template>
<style>

</style>